@extends('layout.main')
@extends('layout.header')

@section('title'){{ __('login.login') }}@endsection

@section('style')
    <link rel="stylesheet" href="../css/main.css">
@endsection


@section('content')
    <h1 class="index-title">{{ __('index.sign-in') }}</h1>
    <div class="container to-center">
        <form action="{{ route('user.login', app()->getLocale()) }}" method="post" class="form-auth">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">{{ __('login.email') }}</label>
                <input type="text" id="email" name="email" class="input-field" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">{{ __('login.password') }}</label>
                <input type="password" id="email" name="password" class="input-field" required>
            </div>
            @error('email')
            <div class="alert">{{ $message }}</div>
            @enderror
            @error('password')
            <div class="alert">{{ $message }}</div>
            @enderror
            <div class="form-group" id="button">
                <button name="send" type="submit" class="form-button">{{ __('login.apply') }}</button>
            </div>
        </form>
    </div>
@endsection
