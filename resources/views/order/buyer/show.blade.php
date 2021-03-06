@extends('layouts.textbook')

@section('title', 'Order #'.$buyer_order->id)

@section('content')

    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ url('order/buyer') }}">Your orders</a></li>
                <li class="active">Order #{{ $buyer_order->id }}</li>
            </ol>
        </div>

        <div class="page-header">
            <h1>Order Details</h1>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <span>Ordered on {{ $buyer_order->created_at }}</span>
                        </div>
                        <div class="col-md-2">
                            <span>Order #{{ $buyer_order->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- order details --}}
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <?php $addr = $buyer_order->shipping_address ?>

                            <div class="row">
                                <h4>Shipping Address</h4>
                            </div>
                            <div class="row">
                                <span>{{ $addr->addressee }}</span>
                            </div>
                            <div class="row">
                                <span>{{ $addr->address_line1 }}</span>
                            </div>
                            <div class="row">
                                <span>{{ $addr->city }}</span>, <span>{{ $addr->state_a2 }}</span> <span>{{ $addr->zip }}</span>
                            </div>
                        </div>

                        {{--<div class="col-md-4">--}}
                            {{--<div class="row">--}}
                                {{--<h4>Payment Method</h4>--}}
                            {{--</div>--}}

                            {{--<div class="row">--}}
                                 {{--TODO--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div class="col-sm-6">
                            @if(!$buyer_order->cancelled)
                                <div class="row">
                                    <h4>Order Summary</h4>
                                </div>

                                <div class="row">
                                    <span class="pull-left">Item(s) subtotal:</span>
                                    <span class="pull-right">${{ number_format($buyer_order->subtotal, 2, '.', '') }}</span>
                                </div>

                                <div class="row">
                                    <span class="pull-left">Shipping & Handling:</span>
                                    <span class="pull-right">${{ number_format($buyer_order->shipping, 2, '.', '') }}</span>
                                </div>

                                @if($buyer_order->discount > 0)
                                    <div class="row">
                                        <span class="pull-left">Discount:</span>
                                        <span class="pull-right">-${{ number_format($buyer_order->discount, 2, '.', '') }}</span>
                                    </div>
                                @endif

                                <div class="row">
                                    <span class="pull-left">Total before tax:</span>
                                    <span class="pull-right">${{ number_format($buyer_order->subtotal + $buyer_order->shipping - $buyer_order->discount, 2, '.', '') }}</span>
                                </div>

                                <div class="row">
                                    <span class="pull-left">Estimated tax to be collected:</span>
                                    <span class="pull-right">${{ number_format($buyer_order->tax, 2, '.', '') }}</span>
                                </div>

                                <div class="row">
                                    <span class="pull-left"><strong>Grand Total:</strong></span>
                                    <span class="pull-right"><strong>${{ number_format($buyer_order->amount, 2, '.', '') }}</strong></span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="container-fluid">
                    {{-- order status --}}
                    <div class="row">
                        <h3>{{ $buyer_order->getOrderStatus()['status'] }}</h3>
                        <span>{{ $buyer_order->getOrderStatus()['detail'] }}</span>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-9">
                            <!-- product list -->
                            @foreach($buyer_order->seller_orders as $seller_order)
                                <?php $product = $seller_order->product; ?>

                                @include('includes.textbook.product-details')
                                <br>
                            @endforeach
                        </div>

                        {{-- action buttons --}}
                        <div class="col-md-3">
                            @if($buyer_order->isDeliverySchedulable())
                                <a class="btn btn-primary btn-block" href="{{ url('order/buyer/' . $buyer_order->id . '/scheduleDelivery') }}">Update delivery details</a>
                            @endif

                            {{-- cancel order --}}
                            @if ($buyer_order->isCancellable())
                                <button type="button" class="btn btn-danger btn-block" data-toggle="modal"
                                        data-target="#cancel-buyer-order"
                                        data-buyer-order-id="{{ $buyer_order->id }}">Cancel order</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@include('includes.modal.cancel-buyer-order')