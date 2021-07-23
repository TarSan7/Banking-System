@extends('layout.main')
@extends('layout.header')

@section('title') Hello! @endsection

@section('style')
    <link rel="stylesheet" href="./css/main.css">
@endsection


@section('content')
    <div class="container index-title-div">
        <h1 class = "index-title">Hello! <br> You are in online-banking "YourBank" <br> To start working
            <a href="{{ route('user.login') }}"><b>Sign in</b></a> or pass
            <a href="{{ route('user.registration') }}"><b>Registration</b></a>.
        </h1>
    </div>
@endsection
