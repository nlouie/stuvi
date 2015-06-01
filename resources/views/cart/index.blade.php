@extends('app')

@section('content')
    <div class="container" xmlns="http://www.w3.org/1999/html">
        @if (Session::has('message'))
            <div class="flash-message">{{ Session::get('message') }}</div>
        @endif
    </div>
    <div class="container">
        <h1>Cart items:  <a href="{{ url('/cart/empty') }}">!!Empty Cart!!</a></h1></br>
        @forelse ($items as $item)
            Book title: {{ $item->name }} </br>
                 isbn:  {{ $item->options['item']->book->isbn }} </br>
                 price: {{ $item->price }} </br>
            <a href="{{ url('/cart/rmv/'.$item->rowid) }}">Remove from Cart</a></br>
            ----------------------------------------------------------------------------------------
        @empty
            <p>You don't have any product in shopping cart.</p>
        @endforelse

        ----------------------------------------------------------------------------------------
        <h3>Subtotal: {{ $total_price }}</h3>
    </div>
@endsection