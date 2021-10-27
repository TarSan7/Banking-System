@extends('layout.main')
@extends('layout.headerPrivate')

@section('title') {{ __('allTransactions.choose') }} @endsection

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/main.css') }}">
@endsection

@section('content')
    <h1 class="index-title">{{ __('allTransactions.choose') }}</h1>

    <div class="container to-center">
        <ul class="menu-transfer">
            <li class="item"><a href="{{ route('user.cardTransfer', app()->getLocale()) }}" class="text" id="text-transfers"> {{ __('allTransactions.to-cards') }} </a></li>
            <li class="item">
                <a href="{{ route('user.otherTransfer', [app()->getLocale(), 'id' => 'phone']) }}" class="text" id="text-transfers"> {{ __('allTransactions.to-phone') }}</a>
            </li>
            <li class="item">
                <a href="{{ route('user.otherTransfer', [app()->getLocale(), 'id' => 'internet']) }}" class="text" id="text-transfers"> {{ __('allTransactions.to-intern') }} </a>
            </li>
        </ul>
    </div>

@endsection
