@extends('layout.main')
@extends('layout.header')

@section('title') Registration @endsection

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/main.css') }}">
@endsection

@section('content')
    <h1 class="index-title">{{ __('index.sign-up') }}</h1>
    <div class="container to-center">
        <form action="{{ route('user.registration', app()->getLocale()) }}" method="post" class="form-auth">
            @csrf
            <div class="form-group">
                <label for="name" class="form-label">{{ __('registration.name') }}</label>
                <input type="text" id="name" name="name" class="input-field" placeholder="Name" required>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">{{ __('login.email') }}</label>
                <input type="text" id="email" name="email" class="input-field" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">{{ __('login.password') }}</label>
                <input type="password" id="email" name="password" class="input-field" required>
            </div>
            @error('name')
            <div class="alert">{{ $message }}</div>
            @enderror
            @error('email')
            <div class="alert">{{ $message }}</div>
            @enderror
            @error('password')
            <div class="alert">{{ $message }}<br>{{ __('registration.alert') }}</div>
            @enderror
            <div class="form-group" id="button">
                <button name="send" type="submit" class="form-button">{{ __('registration.create') }}</button>
            </div>
        </form>
    </div>
@endsection

