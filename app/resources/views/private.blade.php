@extends('layout.main')
@extends('layout.headerPrivate')

@section('title') Main Page @endsection

@section('style')
    <link rel="stylesheet" href="./css/main.css">
@endsection

@section('content')
    <div class="container">
        <ul class="menu">
            <li class="item"><a href="" class="text"> Your transactions</a></li>
            <li class="item"><a href="{{ route('user.transfers') }}" class="text"> Make transfer</a></li>
            <li class="item"><a href="" class="text"> Loans </a></li>
            <li class="item"><a href="" class="text"> Deposits </a></li>
        </ul>
        <hr>
        <h1 class="index-title">Hi, {{$username}}!<br>Your cards:</h1>
        <div class="to-center">
            <div class="cards">
                @if(count($cards) > 0)
                    @foreach($cards as $card)
                        <a href="{{ route('user.card', ['id' => $card['id']]) }}" class="card-link">
                            <div class="card col-md-6 col-12">
                                <div class="spacing">
                                    <h2 class="card-title"> Card </h2>
                                    <h2 class="card-title"> {{ $card['number'] }} </h2>
                                </div>
                                <div class="spacing">
                                    <h3 class="card-info"> Type: </h3>
                                    <h2 class="card-title"> {{ $card['type'] }} </h2>
                                </div>
                                <div class="spacing">
                                    <h3 class="card-info"> CVV </h3>
                                    <h2 class="card-title"> {{ $card['cvv'] }} </h2>
                                </div>
                                <div class="spacing">
                                    <h3 class="card-info"> Expires end: </h3>
                                    <h2 class="card-title"> {{ $card['expires_end'] }} </h2>
                                </div>
                                <h2 class="card-title"> {{ $card['sum'] }} {{ $card['currency'] }} </h2>
                            </div>
                        </a>
                    @endforeach
                @else
                    <h1 class="text">You have no cards...</h1>
                @endif
            </div>
        </div>
        <a href="{{ route('user.addCard') }}" class="add-card">Add Card</a>

    </div>
@endsection

