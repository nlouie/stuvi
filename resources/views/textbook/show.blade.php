@extends('app')

@section('title',$book->title)

@section('css')
    <link href="{{ asset('/css/textbook_show.css') }}" rel="stylesheet">
@endsection


@section('content')

    @include('textbook/textbook-nav')

    <div class="container">
        <div class="row textbook-row">
            <div class="col-sm-6">
                @if($book->imageSet->small_image)
                    <img id="textbook-img" src="{{ $book->imageSet->large_image or config('book.default_image_path.large') }}" alt="Book Image"/>
                @endif
            </div>

            <div class="col-sm-6 textbook-info">
                <h1>{{ $book->title }}</h1>

                <div class="authors-container">
                    <span>by </span>
                    @foreach($book->authors as $author)
                        <span id="authors"><button class="btn btn-default author-btn">{{ $author->full_name }}</button></span>
                    @endforeach
                </div>
                <p>ISBN10: {{ $book->isbn10 }}</p>
                <p>ISBN13: {{ $book->isbn13 }}</p>
                <p>Number of Pages: {{ $book->num_pages }}</p>
            </div>
        </div>

        @if(count($book->availableProducts()) > 0)

            <div class="row table-row">

                <h3>Select one of our available books</h3>
                <table class="table table-responsive textbook-table" style="width:100%" border="1">
                    <thead>
                    <tr class="active">
                        <th>Price</th>
                        <th>Condition</th>
                        <th>Details</th>
                        <th>Add to Cart</th>
                    </tr>
                    </thead>
                    @foreach($book->availableProducts() as $product)
                        <tr>
                            <td>
                                <p id="price">${{ $product->price }}</p>
                            </td>
                            <td>
                                {{ $product->general_condition() }}
                            </td>
                            <td>
                                <a href="{{ url('textbook/buy/product/'.$product->id) }}">View Details</a>
                            </td>
                            <td class="cart-btn-col">
                                @if($product->isInCart(Auth::user()->id))
                                    <a class="btn secondary-btn add-cart-btn disabled" href="#" role="button">Added to cart</a>
                                @elseif($product->seller == Auth::user())
                                    <a class="btn secondary-btn add-cart-btn disabled" href="#" role="button">Posted by you</a>
                                @else
                                    <a class="btn secondary-btn add-cart-btn" href="{{ url('cart/add/'.$product->id) }}" role="button">Add to cart</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </table>
            </div>
        @else
            <h3>Sorry, this book is not available for now.</h3>
        @endif
    </div>

@endsection

@section('javascript')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
@endsection