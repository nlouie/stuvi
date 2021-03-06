@extends('layouts.textbook')

@section('title', 'Add a new address')

@section('content')
    <div class="container">
        <h2>Add a new address</h2>

        <div class="row">
            <form action="/order/seller/storeAddress" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="seller_order_id" value="{{ $seller_order->id }}"/>

                <div class="form-group">
                    <label>Full name</label>
                    <input type="string" name="addressee" value="{{ Input::old('addressee') }}" class="form-control"/>
                </div>

                <div class="form-group">
                    <label>Address line 1</label>
                    <input type="string" name="address_line1" value="{{ Input::old('address_line1') }}"
                           class="form-control"/>
                </div>

                <div class="form-group">
                    <label>Address line 2</label>
                    <input type="string" name="address_line2" value="{{ Input::old('address_line2') }}"
                           class="form-control"/>
                </div>

                <div class="form-group">
                    <label>City</label>
                    <input type="string" name="city" value="{{ Input::old('city') }}" class="form-control"/>
                </div>

                <div class="form-group">
                    <label>State</label>
                    <input type="string" name="state_a2" value="{{ Input::old('state_a2') }}" class="form-control"/>
                </div>

                <div class="form-group">
                    <label>Zip</label>
                    <input type="string" name="zip" value="{{ Input::old('zip') }}" class="form-control"/>
                </div>

                @if(config('addresses.show_country'))
                    <div class="form-group">
                        <label>Country</label>
                        <input type="string" name="country" value="{{ Input::old('country') }}" class="form-control"/>
                    </div>
                @endif

                <div class="form-group">
                    <label>Phone number</label>
                    <input type="string" name="phone_number" value="{{ Input::old('phone_number') }}"
                           class="form-control"/>
                </div>

                <input type="submit" name="submit" class="btn btn-primary" value="Add this address"/>

            </form> <br>
        </div>
    </div>
@endsection