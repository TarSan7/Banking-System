@extends('layout.main')

@section('title') {{ __('takeDeposit.take') }} @endsection

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
    <div class="container">
        <a href="{{ url()->previous(app()->getLocale()) }}" class="arrow">
                <picture>
                    <source srcset="/img/arr.webp" type="image/webp" class="arrow">
                    <img src="/img/arr.png" alt="arrow" class="arrow">
                </picture>
        </a>
    </div>

    <h1 class="index-title">{{ __('takeDeposit.calculate') }}<br>"{{ $deposit['title'] }}"</h1>
    @if (session()->has('success'))
        <p class="success">{{ session('success') }}</p>
    @endif
    <div class="container">
        <div class="to-center">
            <form action="{{ route('user.takeDeposit', [app()->getLocale(), 'id' => $deposit['id']]) }}"
                  method="post" class="deposit-form">
                @csrf
                <div class="form-group">
                    <label for="numberFrom" class="form-label">{{ __('takeDeposit.from') }}</label>
                    <select name="numberFrom" id="numberFrom" class="input-field" required>
                        @foreach($cards as $card)
                            <option value="{{ $card['id'] }}"> {{ $card['number'] }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="sum" class="form-label"> {{ __('takeDeposit.sum') }} </label>
                    <input type="number" id="deposit-sum" min="100" step="1" max="{{ $deposit['max_sum'] }}"
                           name="sum" class="input-field" placeholder="Sum" required>
                    <select name="currency" id="currency" class="input-field" required>
                        <option value="UAH"> UAH </option>
                        <option value="EUR"> EUR </option>
                        <option value="USD"> USD </option>
                        <option value="RUR"> RUR </option>
                        <option value="GBP"> GBP </option>
                        <option value="PLN"> PLN </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="duration" class="form-label"> {{ __('takeDeposit.duration') }}</label>
                    <select name="duration" id="duration" class="input-field" required>
                        @for ($i = $deposit['min_duration']; $i <= $deposit['max_duration']; $i++)
                            <option value="{{ $i }}"> {{ $i }} mm</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group">
                    <fieldset>
                        <label for="duration" class="form-label"> {{ __('takeDeposit.percents') }}:</label>
                        <div class="check">
                            <input class="form-label" type="radio" id="early_percent"
                                   name="percents" value="{{ $deposit['early_percent'] }}" checked>
                            <label class="form-label" for="early_percent">{{ __('takeDeposit.early') }}
                                <span class="bold">{{ $deposit['early_percent'] }}% </span>
                            </label>
                        </div>
                        <div class="check">
                            <input class="form-label" type="radio" id="intime_percent"
                                   name="percents" value="{{ $deposit['intime_percent'] }}">
                            <label class="form-label" for="intime_percent">{{ __('takeDeposit.intime') }}
                                <span class="bold">{{ $deposit['intime_percent'] }}% </span>
                            </label>
                        </div>
                    </fieldset>
                </div>
                @error('sum')
                <div class="alert">{{ $message }}</div>
                @enderror
                @error('error')
                <div class="alert">{{ $message }}</div>
                @enderror
                <div class="form-group" id="button">
                    <button name="send" type="submit" class="form-button">{{ __('takeDeposit.see') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
