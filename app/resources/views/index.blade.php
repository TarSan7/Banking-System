@extends('layout.main')
@extends('layout.header')

@section('title') {{ __('index.hello') }} @endsection

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/main.css') }}">
@endsection


@section('content')
    <div class="container index-title-div">
        <h1 class = "index-title">{{ __('index.hello') }}<br> {{ __('index.in-bank') }} <br> {{ __('index.start') }}
            <a href="{{ route('user.login', app()->getLocale()) }}">
                <b>{{ __('index.sign-in') }}</b>
            </a> {{ __('index.or-pass') }}
            <a href="{{ route('user.registration', app()->getLocale()) }}">
                <b>{{ __('index.registration') }}</b>
            </a>.
        </h1>
    </div>
@endsection

