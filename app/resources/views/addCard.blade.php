@extends('layout.main')
@extends('layout.headerPrivate')

@section('title') {{ __('add.add') }} @endsection

@section('style')
    <link rel="stylesheet" href="/css/main.css">
@endsection

@section('content')
    <h1 class="index-title">{{ __('add.add') }}</h1>
    @if (session()->has('success'))
        <p class="success">{{ session('success') }}</p>
    @endif
    <div class="container">
        <div class="form">
            <form action="{{ route('user.addCard', app()->getLocale()) }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="number" class="form-label">{{ __('add.number') }}</label>
                    <input type="text" id="number" name="number" class="input-field" placeholder="{{ __('add.number') }}" required>
                </div>
                <div class="form-group">
                    <label for="cvv" class="form-label">{{ __('add.cvv') }}</label>
                    <input type="text" id="cvv" name="cvv" class="input-field" placeholder="{{ __('add.cvv') }}" required>
                </div>
                <div class="form-group">
                    <label for="date" class="form-label">{{ __('add.end') }}</label>
                    <input type="date" id="expires-end" name="expires-end" class="input-field" placeholder="{{ __('add.end') }}" required>
                </div>
                @error('number')
                <div class="alert">{{ $message }}</div>
                @enderror
                @error('cvv')
                <div class="alert">{{ $message }}</div>
                @enderror
                @error('expires-end')
                <div class="alert">{{ $message }}</div>
                @enderror
                @error('error')
                <div class="alert">{{ $message }}</div>
                @enderror
                <div class="form-group" id="button">
                    <button name="send" type="submit" class="form-button">{{ __('add.button') }}</button>
                </div>
            </form>

        </div>
    </div>
@endsection

