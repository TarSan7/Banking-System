@extends('layout.main')
@extends('layout.header')

@section('title') Registration @endsection

@section('style')
    <link rel="stylesheet" href="./css/main.css">
@endsection

@section('content')
    <h1 class="index-title">Sign up</h1>
    <div class="container to-center">
        <form action="{{ route('user.registration') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="name" class="form-label">Your Name</label>
                <input type="text" id="name" name="name" class="input-field" placeholder="Name" required>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Your Email</label>
                <input type="text" id="email" name="email" class="input-field" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Create Password</label>
                <input type="password" id="email" name="password" class="input-field" required>
            </div>
            @error('name')
            <div class="alert">{{ $message }}</div>
            @enderror
            @error('email')
            <div class="alert">{{ $message }}</div>
            @enderror
            @error('password')
            <div class="alert">{{ $message }}<br>Needs all of: A-Z, a-z, 0-9, non-alphanumeric symbols.</div>
            @enderror
            <div class="form-group" id="button">
                <button name="send" type="submit" class="form-button">Create</button>
            </div>
        </form>
    </div>
@endsection

