@extends('layout.main')

@section('title') {{ __('private.info') }} @endsection

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
                            <a class="nav-link active" href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), [$locale, 'id' => $card['id']])}}">
                        <span class="sr-only">
                            @if (app()->getLocale() == $locale) @endif {{ strtoupper($locale) }}
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
        {{--    @if ()--}}
        <a href="{{ url()->previous(app()->getLocale()) }}" class="arrow">
            <img src="/img/arr.png" alt="arrow" class="arrow">
        </a>
    </div>

    <div class="container">
        <div class="to-center">
            <div class="card cards">
                <div class="spacing">
                    <h2 class="card-title"> {{ __('private.card') }} </h2>
                    <h2 class="card-title"> {{ $card['number'] }} </h2>
                </div>
                <div class="spacing">
                    <h3 class="card-info"> {{ __('private.type') }} </h3>
                    <h2 class="card-title"> {{ $card['type'] }} </h2>
                </div>
                <div class="spacing">
                    <h3 class="card-info"> {{ __('private.cvv') }} </h3>
                    <h2 class="card-title"> {{ $card['cvv'] }} </h2>
                </div>
                <div class="spacing">
                    <h3 class="card-info"> {{ __('private.end') }}  </h3>
                    <h2 class="card-title"> {{ $card['expires_end'] }} </h2>
                </div>
                <h2 class="card-title"> {{ $card['sum'] }} {{ $card['currency'] }} </h2>
            </div>
        </div>
        <h1 class="index-title">{{ __('allTransactions.transactions') }}</h1>
        <div class="transactions">
            @if (count($transactions))
                @foreach ($transactions as $transaction)
                    <div class="transaction">
                        <div class="spacing">
                            <h2 class="card-title"> <b>{{ $transaction['comment'] }} </b></h2>
                            <h2 class="card-title"> {{ $transaction['date'] }} </h2>
                        </div>
                        @if($card['number'] == $transaction['card_to'])
                            <h3 class="card-info"> {{ __('allTransactions.from') }} {{ $transaction['card_from'] }} </h3>
                            <h3 class="card-info"> {{ __('allTransactions.sum') }} <b class="card-to">+{{ $transaction['sum'] }}
                                {{ $transaction['currency'] }} </b></h3>
                        @else
                            @if($transaction['comment'] == 'Transfer to another card.')
                                <h3 class="card-info"> {{ __('allTransactions.to') }} {{ $transaction['card_to'] }} </h3>
                            @endif
                            <h3 class="card-info"> {{ __('allTransactions.sum') }} <b class="card-from">-{{ $transaction['sum'] }}
                                {{ $transaction['currency'] }}</b></h3>
                        @endif
                    </div>
                @endforeach
            @else
                <h2 class="text">{{ __('allTransactions.none') }}</h2>
            @endif
        </div>
    </div>

@endsection
