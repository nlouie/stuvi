<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Book;
use App\BookImageSet;
use App\BookBinding;
use App\BookLanguage;

use Auth;
use Input;
use App\Helpers\FileUploader;
use App\Helpers\SearchClassifier;
use ISBNdb\Book as IsbndbBook;


class TextbookController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('textbook.buy');
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('textbook.create');
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        // validations could be done in Validation class
        if (Input::hasFile('image'))
        {
            if (!Input::file('image')->isValid())
            {
                return response('Please upload a valid image.');
            }

            // get the uploaded file
            $image = Input::file('image');
			$title = Input::get('title');
			$folder = '/img/book/';
			$file_uploader = new FileUploader($image, $title, $folder);
        }
        else
        {
            return response('Please upload a textbook image.');
        }

        $image_set = new BookImageSet();
        $image_set->large_image = $file_uploader->path;
        $image_set->save();

        // TODO: upload book information for verification
        $book = new Book();
        $book->isbn             = Input::get('isbn');
        $book->title            = Input::get('title');
        $book->author           = Input::get('author');
        $book->edition          = Input::get('edition');
        $book->publisher        = Input::get('publisher');
        $book->publication_date = Input::get('publication_date');
        $book->manufacturer     = Input::get('manufacturer');
        $book->num_pages        = Input::get('num_pages');
        $book->binding_id       = Input::get('binding');
        $book->image_set_id     = $image_set->id;
        $book->language_id      = Input::get('language');

        // save the book image
		$file_uploader->saveFile();

        $book->save();

        return view('textbook.createProduct', [
			'book' 	=> $book,
			'image' => $image_set
			]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($book)
	{
		return view("textbook.show", [
			'book' => $book
		]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}




    /***************************************************/
    /******************   Sell Part   ******************/
    /***************************************************/

    /**
     * Show the sell page, which is a search box of isbn.
     *
     * @return Response
     */
    public function sell()
    {
        return view('textbook.sell');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function isbnSearch(Request $request)
    {
        $isbn = Input::get('isbn');

		if ($this->validateIsbn($isbn) == false)
		{
			return redirect('textbook/sell')->with('message', 'Please enter a valid 10 or 13 digits ISBN.');
		}

        $db_book = DB::table('books')->where('isbn', $isbn)->first();

        // if the book is in our db, show the book information and let seller edit it
        if ($db_book)
        {
            return view('textbook.result')->withBook($db_book);
        }
        else
        {
			// search book in isbndb
			$token = 'YPKFSSUW';
			$isbndb_book = new IsbndbBook($token, $isbn);

			if ($isbndb_book->isFound())
			{
				$book = new Book();
				$book->isbn = $isbndb_book->getIsbn13();
				$book->title = $isbndb_book->getTitle();
				$book->author = $isbndb_book->getAuthorName();
				$book->publisher = $isbndb_book->getPublisherName(); // Text or Name?
				$book->num_pages = $isbndb_book->getNumPages();
				// TODO: language conversion
				// $book->language = $isbndb_book->getLanguage();

				return view('textbook.create')->withBook($book);
			}

			// allow the seller fill in book information and create a new book record
            return redirect('textbook/sell/create')->with(
                'message',
                'Looks like your textbook is currently not in our database, please fill in the textbook information below.');
        }
    }

	/**
	* Validate the input ISBN (10 or 13 digits)
	*
	* @param String $isbn
	* @return Bool
	*/
	public function validateIsbn($isbn)
	{
		$len = strlen($isbn);

		return ($len == 10 || $len == 13);
	}



    /***************************************************/
    /******************   Buy Part   *******************/
    /***************************************************/

    /**
     * Show the buy page.
     *
     * @return Response
     */
    public function buy()
    {
        return view('textbook.buy');
    }

	/**
     * Search function for the buy page.
     *
     * @return Response
     */
	public function buySearch()
	{
		$info = Input::get('info');

		$classifier = new SearchClassifier($info);

		return view('textbook.buy');
	}
}
