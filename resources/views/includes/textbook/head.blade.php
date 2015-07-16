<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Student Village, college service provider">
    <meta name="author" content="Stuvi">
    <meta name="csrf_token" content="{{ csrf_token() }}">

    <title>Stuvi - @yield('title')</title>

    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/css/main.css') }}"/>

    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- Footer & Nav required -->
    <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet">


    <!-- material design -->
{{--
    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.teal-deep_orange.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
--}}


    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    @yield('css')
</head>