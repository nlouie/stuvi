@extends('layouts.textbook')

@section('title', 'FAQ - Textbooks')

@section('content')
    <div class="container page-content">
        @include('faq.side-nav')
        <div class="col-sm-7 col-sm-offset-1 qa-container">
            <h2>FAQ - Textbook</h2>
            <hr>
            <a name="text-condition"></a>
            <div class="question-answer">
                <h3>Will the textbooks be in good condition?</h3>
                <p>
                    Stuvi guarantees that every book's condition matches the seller's description.
                </p>
            </div>
        </div>
    </div>
@endsection