<?php namespace App\Http\Controllers\Textbook;

use App\BuyerOrder;
use App\Events\ProductWasCreated;
use App\Helpers\Price;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Product;
use App\ProductCondition;
use App\ProductImage;
use App\SellerOrder;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Input;
use Response;
use Session;
use URL;
use Validator;

class ProductController extends Controller
{
    /**
     * The page for book confirmation after sell search.
     *
     * @param $book
     * @return \Illuminate\View\View
     */
    public function confirm($book)
    {
        return view('product.confirm')
            ->withBook($book);
    }

    /**
     * Show the form for creating a new product.
     *
     * @return Response
     */
    public function create($book)
    {
        return view('product.create')
            ->withBook($book)
            ->withPaypal(Auth::user()->profile->paypal);
    }

    /**
     * AJAX: Store a product.
     *
     * @return Response
     */
    public function store()
    {
        $images = Input::file('file');
        $payout_method = Input::get('payout_method');

        // validation
        $v = Validator::make(Input::all(), Product::rules($images));

        if ($v->fails())
        {
            return Response::json([
                'success' => false,
                'fields' => $v->errors()
            ]);
        }

        // create product
        $product = Product::create([
            'book_id'           => Input::get('book_id'),
            'seller_id'         => Auth::user()->id,
            'price'             => Input::get('price'),
            'available_at'      => Carbon::parse(Input::get('available_at')),
            'payout_method'     => $payout_method,
            'accept_trade_in'   => Input::has('accept_trade_in') ? true : false,
            'verified'          => true
        ]);

        // update book price range
        $product->book->addPrice(Input::get('price'));

        ProductCondition::create([
            'product_id' => $product->id,
            'general_condition' => Input::get('general_condition'),
            'highlights_and_notes' => Input::get('highlights_and_notes'),
            'damaged_pages' => Input::get('damaged_pages'),
            'broken_binding' => Input::get('broken_binding'),
            'description' => Input::get('description'),
        ]);

        // save multiple product images
        foreach ($images as $image)
        {
            // create product image instance
            $product_image             = new ProductImage();
            $product_image->product_id = $product->id;
            $product_image->save();

            // save product image paths with different sizes
            $product_image->small_image  = $product_image->generateFilename('small', $image);
            $product_image->medium_image = $product_image->generateFilename('medium', $image);
            $product_image->large_image  = $product_image->generateFilename('large', $image);
            $product_image->save();

            // resize image
            $product_image->resize($image);

            // upload image with different sizes to aws s3
            $product_image->uploadToAWS();
        }

        // update user's Paypal email address
        if ($payout_method == 'paypal')
        {
            Auth::user()->profile->update([
                'paypal'    => Input::get('paypal')
            ]);
        }

        event(new ProductWasCreated($product));

        return Response::json([
            'success' => true,
            'redirect' => '/textbook/buy/product/' . $product->id,
        ]);
    }

    /**
     * Display the specified product.
     *
     * @param Product $product
     *
     * @return Response
     */
    public function show($product)
    {
//        if ($product->seller_id != Auth::id())
//        {
//            // increment product views by 1
//            Redis::hincrby('product:'.$product->id, 'views', 1);
//            Redis::sadd('list:product_ids', $product->id);
//        }

        if ($product->deleted_at)
        {
            return redirect('textbook/buy/'.$product->book_id)
                ->with('error', 'This book has been deleted.');
        }

        return view('product.show')
            ->withProduct($product)
            ->withQuery(Input::get('query'))
            ->with('university_id', Input::get('university_id'));
    }

    /**
     * Show product edit page.
     *
     * @param $request
     * @param Product $product
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function edit(Requests\EditProductRequest $request, $product)
    {
        if ($product->sold || $product->isDeleted())
        {
            return back()
                ->with('error', 'This book is not available.');
        }

        return view('product.edit')
            ->with('book', $product->book)
            ->with('product', $product)
            ->with('paypal', Auth::user()->profile->paypal);
    }

    /**
     * @need_refactor
     * Update product info.
     *
     * If AJAX, we'll update images.
     *
     * @param $request
     * @return mixed
     */
    public function update(Requests\UpdateProductRequest $request)
    {
        $product = Product::find(Input::get('product_id'));
        $images = Input::file('file');

        // validation
        $v = Validator::make(Input::all(), Product::rules($images));

        $v->after(function($v) use ($product)
        {
            if ($product->sold || $product->isDeleted())
            {
                $v->errors()->add('product', 'This book is not available.');
            }
        });

        if ($v->fails())
        {
            if ($request->ajax())
            {
                return Response::json([
                    'success' => false,
                    'fields' => $v->errors(),
                ]);
            }
            else
            {
                return redirect()->back()
                        ->withErrors($v->errors());
            }

        }

        $payout_method = Input::get('payout_method');
        $old_price = $product->price;
        $new_available_at = Carbon::parse(Input::get('available_at'));

        $product->update([
            'price'             => Input::get('price'),
            'available_at'      => $new_available_at,
            'payout_method'     => $payout_method,
            'accept_trade_in'   => Input::has('accept_trade_in') ? true : false
        ]);

        // remove old price if it exists
        if ($old_price)
        {
            $product->book->removePrice($old_price);
        }

        $product->book->addPrice(Input::get('price'));

        // update user's Paypal email address
        if ($payout_method == 'paypal')
        {
            Auth::user()->profile->update([
                'paypal'    => Input::get('paypal')
            ]);
        }

        // update product condition
        $product->condition->update([
            'general_condition'     => Input::get('general_condition'),
            'highlights_and_notes'  => Input::get('highlights_and_notes'),
            'damaged_pages'         => Input::get('damaged_pages'),
            'broken_binding'        => Input::get('broken_binding'),
            'description'           => Input::get('description'),
        ]);

        // if AJAX request, save images
        if ($request->ajax())
        {
            foreach ($images as $image)
            {
                // create product image instance
                $product_image = new ProductImage();
                $product_image->product_id = $product->id;
                $product_image->save();

                // save product image paths with different sizes
                $product_image->small_image = $product_image->generateFilename('small', $image);
                $product_image->medium_image = $product_image->generateFilename('medium', $image);
                $product_image->large_image = $product_image->generateFilename('large', $image);
                $product_image->save();

                // resize image
                $product_image->resize($image);

                // upload image with different sizes to aws s3
                $product_image->uploadToAWS();
            }

            return Response::json([
                'success' => true,
                'redirect' => '/textbook/buy/product/' . $product->id,
            ]);
        }
        else
        {
            // if the request is not AJAX (Dropzone does not contain any image)
            // we do not need to save any image, just redirect to the product page
            return redirect('/textbook/buy/product/' . $product->id)
                ->with('success', 'The product is updated successfully.');
        }
    }

    /**
     * Delete a product record.
     *
     * @param $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Requests\DestroyProductRequest $request)
    {
        $product = Product::find($request->get('id'));

        // check if it is sold.
        if ($product->sold || $product->isDeleted())
        {
            return redirect('/user/bookshelf')
                ->with('error', 'Cannot delete this book.');
        }

        $book = $product->book;
        $price = $product->price;

        // soft delete.
        $product->update([
            'deleted_at' => Carbon::now(),
         ]);

        // update book's lowest or highest price if necessary
        $book->removePrice($price);

        return redirect('/user/bookshelf')
            ->with('success', $product->book->title.' has been deleted.');
    }

    /**
     * AJAX: get product images.
     *
     * @return mixed
     */
    public function getImages()
    {
        $product = Product::find(Input::get('product_id'));
        $product_images = $product->images;

        return Response::json([
            'success'   => true,
            'env'       => app()->environment(),
            'images'    => $product_images
        ]);
    }

    /**
     * AJAX: delete a product image according to the product image ID.
     *
     * @return mixed
     */
    public function deleteImage()
    {
        $product_image = ProductImage::find(Input::get('productImageID'));
        $product_image->deleteFromAWS();
        $product_image->delete();

        return Response::json([
            'success'   => true
        ]);
    }

    /**
     * Accept a product for Stuvi Book Trade-in Program.
     *
     * @param $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function joinTradeIn(Requests\JoinTradeInRequest $request)
    {
        $product = Product::find($request->get('product_id'));

        if ($product->verified && !$product->sold)
        {
            if (!$product->accept_trade_in)
            {
                $product->update([
                    'accept_trade_in'   => true
                ]);
            }

            return redirect()->back()
                ->with('success', 'You have successfully joined the Stuvi Book Trade-in Program, we will send you an email once we approved your book.');
        }

        return redirect()->back()
            ->with('error', 'You cannot trade in this book.');
    }
}
