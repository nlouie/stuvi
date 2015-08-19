@extends('app')

@section('title', 'Your Sold books')

@section('css')
    <link href="{{ asset('/css/order_index.css') }}" rel="stylesheet">
@endsection

@section('content')

<<<<<<< HEAD
    <!-- Message -->
    <div class="container" xmlns="http://www.w3.org/1999/html">
        @if (Session::has('message'))
            <br>
            <div class="flash-message warning bg-warning">{{ Session::get('message') }}</div>
        @endif
    </div>

    <div class="container">
        <div class="page-header">
            <h1>Sold books</h1>
        </div>

        {{-- order list --}}
        @foreach ($orders as $order)
            <div class="panel panel-default">
                <div class="panel-heading">

                    {{-- order details --}}
                    <div class="container-fluid text-muted">
                        <div class="col-xs-2">
                            <div class="row">
                                <span>ORDER SOLD</span>
                            </div>

                            <div class="row">
                                <span>{{ date('M d, Y', strtotime($order->created_at)) }}</span>
                            </div>
=======
    <!-- Main container -->
    <div class="container seller-order-container container-main-content">
        @include('includes.textbook.flash-message')
        <h1>Your sold books</h1>
        @forelse ($orders as $order)
            <div class="row">
                <div class="container order-container">
                    <div class="row order-row">
                        <div class="col-xs-12 col-sm-2 order-date">
                            <h5>Order Sold</h5>

                            <p>{{ date('M d, Y', strtotime($order->created_at)) }}</p>
>>>>>>> front-end
                        </div>

                        <div class="col-xs-2">
                            <div class="row">
                                <span>TOTAL</span>
                            </div>

                            <div class="row">
                                <span>${{ $order->product->decimalPrice() }}</span>
                            </div>
                        </div>

                        <div class="col-xs-2 col-xs-offset-6 text-right">
                            <div class="row">
                                <span>ORDER #{{ $order->id }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="container-fluid">
                        {{-- order status --}}
                        <div class="row">
                            <h3>{{ $order->getOrderStatus()['status'] }}</h3>
                            <small>{{ $order->getOrderStatus()['detail'] }}</small>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-md-9">
                                <!-- product list -->
                                    <div class="row">
                                        <?php $product = $order->product; ?>

                                        {{-- book image --}}
                                        <div class="col-md-2">
                                            <a href="{{ url('/textbook/buy/product/'.$product->id) }}">
                                                @if($product->book->imageSet->small_image)
                                                    <img class="img-responsive img-small"
                                                         src="{{ config('aws.url.stuvi-book-img') . $product->book->imageSet->small_image}}">
                                                @else
                                                    <img class="img-responsive img-small"
                                                         src="{{ config('book.default_image_path.large') }}">
                                                @endif
                                            </a>
                                        </div>

                                        {{-- book details --}}
                                        <div class="col-md-10">
                                            <div class="row">
                                                <span>
                                                    <a href="{{ url('/textbook/buy/product/'.$product->id) }}">{{ $product->book->title }}</a>
                                                </span>
                                            </div>

                                            <div class="row">
                                                <span>ISBN-10: {{ $product->book->isbn10 }}</span>
                                            </div>

                                            <div class="row">
                                                <span>ISBN-13: {{ $product->book->isbn13 }}</span>
                                            </div>

                                            <div class="row">
                                                <span class="price">${{ $product->decimalPrice() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                            </div>

                            {{-- action buttons --}}
                            <div class="col-md-3">
                                {{-- order details --}}
                                <a class="btn primary-btn btn-block" href="/order/seller/{{$order->id}}">Order Details</a>

                                {{-- cancel order --}}
                                @if ($order->isCancellable())
                                    <a class="btn btn-default btn-block" href="/order/seller/cancel/{{ $order->id }}" role="'button">Cancel Order</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{--<p>You haven't sold any books. Why not <a href="{{ url('/textbook/sell') }}">sell some</a>?</p>--}}

    </div>
@endsection

@section('javascript')
@endsection