{{-- Privacy Policy Page --}}

        <!-- extends the nav bar and the footer -->
@extends('layouts.textbook')

@section('title', 'Privacy Policy')

        <!-- put your css link refs here -->
@section('css')
     <link type="text/css" href="{{ asset('css/tos-privacy.css') }}" rel="stylesheet">
@endsection

        <!-- all page content here. in-between nav and footer -->
@section('content')
    @include('includes.textbook.flash-message')

    <div class="container-fluid background">

        <div class="privacy-body col-sm-8 col-sm-offset-2">
            <br>
            <button class="button secondary-btn" onclick="goBack()"><i class="fa fa-arrow-circle-left"></i>
                Go Back
            </button>
            @include('includes.textbook.privacy-content')

        </div>
    </div>

    @endsection

<!-- all javascript is loaded after the footer -->
@section('javascript')
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
@endsection