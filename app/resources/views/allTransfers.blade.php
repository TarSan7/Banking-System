@extends('layout.main')
@extends('layout.headerPrivate')

@section('title') Transactions @endsection

@section('style')
    <link rel="stylesheet" href="./css/main.css">
@endsection

@section('content')
    <div class="container">
        <div class="transactions">
            @if (count($transactions))
                @foreach ($transactions as $cardTransactions)
                        <h1 class="index-title">Transactions of card {{ $cardTransactions['number'] }}</h1>
                        @foreach ($cardTransactions['oneCard'] as $transaction)
                            <div class="transaction">
                                <div class="spacing">
                                    <h2 class="card-title"> <b>{{ $transaction['comment'] }} </b></h2>
                                    <h2 class="card-title"> {{ $transaction['date'] }} </h2>
                                </div>
                                @if($cardTransactions['number'] == $transaction['card_to'])
                                    <h3 class="card-info"> From card: {{ $transaction['card_from'] }} </h3>
                                    <h3 class="card-info"> Sum: <b class="card-to">+{{ $transaction['sum'] }}
                                            {{ $transaction['currency'] }} </b></h3>
                                @else
                                    @if($transaction['comment'] == 'Transfer to another card.')
                                        <h3 class="card-info"> To card: {{ $transaction['card_to'] }} </h3>
                                    @endif
                                    <h3 class="card-info"> Sum: <b class="card-from">-{{ $transaction['sum'] }}
                                            {{ $transaction['currency'] }}</b></h3>
                                @endif
                            </div>
                        @endforeach
                @endforeach
            @else
                <h2 class="text">None...</h2>
            @endif
        </div>
    </div>
@endsection
