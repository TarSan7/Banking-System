@extends('layout.main')

@section('title') {{ __('takeLoan.take') }}@endsection

@section('style')
    <link rel="stylesheet" href="/css/main.css">
@endsection

@section('content')
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('user.private', app()->getLocale()) }}">YourBank</a>
            <div class="d-flex">
                <div class="nav-item active">
                    <div class="nav-item">
                        <a class="nav-link active" href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), ['en', 'id' => $loan['id']])}}">
                    <span class="sr-only">
                        EN
                    </span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link active" href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), ['ru', 'id' => $loan['id']]) }}">
                    <span class="sr-only">
                        RU
                    </span>
                        </a>
                    </div>
                    <a class="nav-link" href="{{ route('user.private', app()->getLocale()) }}"><span class="sr-only">{{ __('index.home') }}</span></a>
                    <a class="nav-link" href="{{ route('user.logout', app()->getLocale()) }}"><span class="sr-only">{{ __('index.out') }}</span></a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container">
        <a href="{{ url()->previous(app()->getLocale()) }}" class="arrow">
            <img src="/img/arr.png" alt="arrow" class="arrow">
        </a>
    </div>

    <h1 class="index-title">{{ __('takeLoan.calculate') }}<br>"{{ $loan['title'] }}"</h1>
    @if (session()->has('success'))
        <p class="success">{{ session('success') }}</p>
    @endif
    <div class="container">
        <div class="to-center">
            <form action="{{ route('user.takeLoan', [app()->getLocale(), 'id' => $loan['id']]) }}" method="post" class="loan-form">
                @csrf
                <div class="form-group">
                    <label for="sum" class="form-label"> {{ __('takeLoan.sum') }} </label>
                    <input type="number" id="loan-sum" min="100" step="1" max="{{ $loan['max_sum'] }}"
                           name="sum" class="input-field" placeholder="{{ __('takeLoan.sum') }}" required>
                    <label for="sum" class="form-label"> {{ $loan['currency'] }} </label>
                </div>
                <div class="form-group">
                    <label for="duration" class="form-label"> {{ __('takeLoan.duration') }}</label>
                    <label for="duration" class="form-label"><span class="bold">{{ $loan['duration'] }}</span> {{ __('takeLoan.months') }}</label>
                </div>
                <div class="form-group">
                    <label for="percents" class="form-label"> {{ __('takeLoan.percents') }}</label>
                    <label for="percents" class="form-label"><span class="bold">{{ $loan['percent'] }}%</span></label>
                </div>
                @error('sum')
                    <div class="alert">{{ $message }}</div>
                @enderror
                @error('error')
                <div class="alert">{{ $message }}</div>
                @enderror
                <div class="form-group" id="button">
                    <button name="send" type="submit" class="form-button">{{ __('takeLoan.see') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
