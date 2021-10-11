@extends('layout.main')

@section('title') {{ __('takeDeposit.take') }} @endsection

@section('style')
    <link rel="stylesheet" href="/css/main.css">
@endsection

@section('content')
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('user.private', app()->getLocale()) }}">YourBank</a>
            <div class="d-flex">
                <div class="nav-item active">
                    @foreach (config('app.available_locales') as $locale)
                        <div class="nav-item">
                            <a class="nav-link active" href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), [$locale, 'id' => $deposit['id']])}}">
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
    <h1 class="index-title">{{ __('takeDeposit.details') }}<br>"{{ $deposit['title'] }}"</h1>
    @if(session()->has('success'))
        <p class="success">{{ $session('success') }}</p>
    @endif
    <div class="container">
        <div class="to-center">
            <form action="{{ route('user.acceptDeposit', [app()->getLocale(), 'id' => $deposit['id']]) }}" method="post" class="deposit-form">
                @csrf
                <div class="form-group">
                    <label for="numberFrom" class="form-label">{{ __('takeDeposit.from') }}</label>
                    <input type="text" id="numberFrom" name="numberFrom" class="input-field" value="{{ $cardFrom }}" readonly>
                </div>
                <div class="form-group">
                    <label for="sum" class="form-label"> {{ __('takeDeposit.sum') }} </label>
                    <input type="number" id="sum" name="sum" class="input-field" value="{{ $sum }}" readonly>
                    <input type="text" id="currency" name="currency" class="form-label-r" value="{{ $currency }}" readonly>
                </div>
                <div class="form-group">
                    <label for="duration" class="form-label"> {{ __('takeDeposit.duration') }}</label>
                    <div class="duration">
                        <span class="bold">
                            <input type="text" id="duration" name="duration" class="form-label-r" value="{{ $duration }}" readonly>
                        </span>
                        <p class="form-label" id="month">{{ __('takeDeposit.months') }}</p>
                    </div>
                </div>
                <div class="form-group">
                    @if ($percent == $deposit['early_percent'])
                        <label for="percents" class="form-label"> {{ __('takeDeposit.early') }} </label>
                    @else
                        <label for="percents" class="form-label"> {{ __('takeDeposit.intime') }} </label>
                    @endif
                    <div class="percent">
                        <span class="bold">
                        <input type="text" id="percent" name="percent" class="form-label-r" value="{{ $percent }}" readonly>
                        </span>
                        <p class="form-label-r" id="duration">%</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="totalSum" class="form-label"> {{ __('takeDeposit.total') }} </label>
                    <label for="totalSum" class="form-label-r">
                        <span class="bold">
                            {{ round((($percent * $duration) / 12 * 0.01 * $sum) + $sum, 2) }}
                        </span>
                        {{ $currency }}
                    </label>
                </div>
                <div class="form-group">
                    <label for="totalExp" class="form-label"> {{ __('takeDeposit.interest') }} </label>
                    <label for="totalExp" class="form-label-r">
                        <span class="bold">
                            {{ round(($percent * $duration) / 12 * 0.01 * $sum, 2) }}
                        </span>
                        {{ $currency }}
                    </label>
                </div>
                <div class="form-group">
                    <label for="monthPay" class="form-label"> {{ __('takeDeposit.monthly') }}  </label>
                    <label for="monthPay" class="form-label-r">
                        <span class="bold">
                            {{ round(($percent * 0.01 * $sum) / $duration, 2)}}
                        </span> {{ $currency }}
                    </label>
                </div>
                <div class="form-group" id="button">
                    <button name="send" type="submit" class="form-button">{{ __('takeDeposit.apply') }} </button>
                </div>
            </form>
        </div>
    </div>
@endsection
