@extends('layouts.textbook')

@section('title', 'FAQ - Account')

@section('content')
    <div class="container page-content">
        @include('faq.side-nav')
        <div class="col-sm-7 col-sm-offset-1 qa-container">
            <h2>FAQ - Account</h2>
            <hr>
            <a name="edit-account-info"></a>
            <div class="question-answer">
                <h3>How do I edit my account information?</h3>
                <p>
                    Please go to the <a href="{{ url('/user/account') }}">account page</a> to edit your account
                    information.
                </p>
            </div>
            <a name="change-email-settings"></a>
            <div class="question-answer">
                <h3>How do I change my email settings?</h3>
                <p>
                    You can change all email preferences on the <a href="{{ url('/user/email') }}">email settings
                        page.</a>
                </p>
            </div>
        </div>
    </div>
@endsection