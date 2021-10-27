@extends('layout.main')
@extends('layout.headerPrivate')

@section('title') {{ __('private.main') }} @endsection

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/main.css') }}">
@endsection

@section('content')
    <div class="container">
        <ul class="menu">
            <li class="item"><a href="{{ route('user.transactions', app()->getLocale()) }}" class="text"> {{ __('private.transactions') }} </a></li>
            <li class="item"><a href="{{ route('user.transfers', app()->getLocale()) }}" class="text"> {{ __('private.make-trans') }} </a></li>
            <li class="item"><a href="{{ route('user.allLoans', app()->getLocale()) }}" class="text"> {{ __('private.loans') }} </a></li>
            <li class="item"><a href="{{ route('user.allDeposits', app()->getLocale()) }}" class="text"> {{ __('private.deposits') }} </a></li>
        </ul>
        <hr>
        <h1 class="index-title">{{ __('private.hi') }}  {{$username}}!<br> {{ __('private.cards') }} </h1>
        <div class="cards to-center">
            @if(count($cards) > 0)
                <div class="slider-wrapper">
                    <img src="/img/leftArr.png" alt="arrow" class="lArrow">
                    @foreach($cards as $card)
                        <div class="card" style="background-image: url({{ $card['image'] }});">
                            <a href="{{ route('user.card', [app()->getLocale(), 'id' => $card['id']]) }}" class="card-link">
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
                                    <h3 class="card-info"> {{ __('private.end') }} </h3>
                                    <h2 class="card-title"> {{ $card['expires_end'] }} </h2>
                                </div>
                                <h2 class="card-title"> {{ $card['sum'] }} {{ $card['currency'] }} </h2>
                            </a>
                        </div>
                    @endforeach
                    <img src="/img/rightArr.png" alt="arrow" class="rArrow">
                </div>
            </div>
            <div class="dots to-center">
                @for ($i = 0; $i < count($cards); $i++)
                    <div class="dot"></div>
                @endfor
            </div>
            @else
                <h1 class="text">{{ __('private.no-cards') }} </h1>
            </div>
            @endif
        <a href="{{ route('user.addCard', app()->getLocale()) }}" class="add-card">{{ __('private.add') }} </a>

    <script src = "{{ mix('/js/slider.js') }}"></script>
    <script src = "{{ mix('/js/cardMove.js') }}"></script>
    </div>
@endsection

