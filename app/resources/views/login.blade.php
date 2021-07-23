@extends('layout.main')
@extends('layout.header')

@section('title') Login @endsection

@section('style')
    <link rel="stylesheet" href="./css/main.css">
@endsection


@section('content')
    <h1 class="index-title">Sign in</h1>
    <div class="container to-center">
        <form action="{{ route('user.login') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">Your Email</label>
                <input type="text" id="email" name="email" class="input-field" placeholder="Email" required>
                @error('email')
                    <div class="alert">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Your Password</label>
                <input type="password" id="email" name="password" class="input-field" required>
                @error('password')
                <div class="alert">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group" id="button">
                <button name="send" type="submit" class="form-button">Apply</button>
            </div>
        </form>
    </div>
@endsection
