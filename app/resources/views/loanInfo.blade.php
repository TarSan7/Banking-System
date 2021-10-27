@extends('layout.main')

@section('title') {{ __('takeLoan.take') }} @endsection

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/main.css') }}">
@endsection

@section('content')
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('user.private', app()->getLocale()) }}">YourBank</a>
            <div class="d-flex">
                <div class="nav-item active">
                    @foreach (config('app.available_locales') as $locale)
                        <div class="nav-item">
                            <a class="nav-link active" href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), [$locale, 'id' => $loan['id']])}}">
                        <span class="sr-only">
                            {{ strtoupper($locale) }}
                        </span>
                            </a>
                        </div>
                    @endforeach

                    <a class="nav-link" href="{{ route('user.private', app()->getLocale()) }}"><span class="sr-only">{{ __('index.home') }}</span></a>
                    <a class="nav-link" href="{{ route('user.logout', app()->getLocale()) }}"><span class="sr-only">{{ __('index.out') }}</span></a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container">
        <a href="{{ url()->previous(app()->getLocale()) }}" class="arrow">
            <picture>
                <source srcset="/img/arr.webp" type="image/webp" class="arrow">
                <img src="/img/arr.png" alt="arrow" class="arrow">
            </picture>
        </a>
    </div>

    <h1 class="index-title">{{ __('takeLoan.details') }}<br>"{{ $loan['title'] }}"</h1>
    @if(session()->has('success'))
        <p class="success">{{ $session('success') }}</p>
    @endif
    <div class="container">
        <div class="to-center">
            <form action="{{ route('user.acceptLoan', [app()->getLocale(), 'id' => $loan['id']]) }}" method="post" class="loan-form">
                @csrf
                <div class="form-group">
                    <label for="sum" class="form-label"> {{ __('takeLoan.sum') }} </label>
                    <input type="number" id="sum" name="sum" class="input-field" value="{{ $sum }}" readonly>
                    <label for="sum" class="form-label"> {{ $loan['currency'] }} </label>
                </div>
                <div class="form-group">
                    <label for="duration" class="form-label"> {{ __('takeLoan.duration') }}</label>
                    <label for="duration" class="form-label"><span class="bold">{{ $loan['duration'] }}</span> {{ __('takeLoan.months') }}</label>
                </div>
                <div class="form-group">
                    <label for="percents" class="form-label"> {{ __('takeLoan.percents') }} </label>
                    <label for="percents" class="form-label"><span class="bold">{{ $loan['percent'] }}%</span></label>
                </div>
                <div class="form-group">
                    <label for="totalSum" class="form-label"> {{ __('takeLoan.total') }} </label>
                    <label for="totalSum" class="form-label">
                        <span class="bold">{{ (($loan['percent'] * $loan['duration']) / 12 * 0.01 * $sum) + $sum }}</span>
                        {{ $loan['currency'] }}
                    </label>
                </div>
                <div class="form-group">
                    <label for="totalExp" class="form-label"> {{ __('takeLoan.total-expenses') }} </label>
                    <label for="totalExp" class="form-label">
                        <span class="bold">{{ (($loan['percent'] * $loan['duration']) / 12 * 0.01 * $sum) }}</span> {{ $loan['currency'] }}
                    </label>
                </div>
                <div class="form-group">
                    <label for="monthPay" class="form-label"> {{ __('takeLoan.monthly') }} </label>
                    <label for="monthPay" class="form-label">
                        <span class="bold">{{ round((($loan['percent'] * 0.01 * $sum) + $sum) / $loan['duration'], 2)}}
                            {{ $loan['currency'] }}</span>
                    </label>
                </div>
                <div class="form-group" id="button">
                    <button name="send" type="submit" class="form-button">{{ __('takeLoan.apply') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
