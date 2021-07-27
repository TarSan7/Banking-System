@extends('layout.main')
@extends('layout.header1')

@section('title') Main Page @endsection

@section('style')
    <link rel="stylesheet" href="./css/main.css">
@endsection

@section('content')
    <div class="container">
        <ul class="menu">
            <li class="item"><a href="" class="text"> Transactions</a></li>
            <li class="item"><a href="" class="text"> Transfer to another card</a></li>
            <li class="item"><a href="" class="text"> Phone replenishment</a></li>
            <li class="item"><a href="" class="text"> Loans </a></li>
            <li class="item"><a href="" class="text"> Deposits </a></li>
        </ul>
        <hr>
        <h1 class="index-title">Your Cards</h1>
        <div class="to-center">
            <div class="cards">
                @if(count($cards) > 0)
                    @foreach($cards as $card)
                        <a href="" class="card-link">
                            <div class="card col-md-6 col-12">
                                <div class="spacing">
                                    <h2 class="card-title"> Card </h2>
                                    <h2 class="card-title"> {{ $card['number'] }} </h2>
                                </div>
                                <h3 class="card-info"> CVV {{ $card['cvv'] }} </h3>
                                <h3 class="card-info"> Expires end:  {{ $card['expires_end'] }} </h3>
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

