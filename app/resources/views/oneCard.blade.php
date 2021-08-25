@extends('layout.main')
@extends('layout.headerPrivate')

@section('title') Card Info @endsection

@section('style')
    <link rel="stylesheet" href="../css/main.css">
@endsection

@section('content')
    <div class="container">
        <div class="to-center">
            <div class="card cards">
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
                    <h3 class="card-info"> Expires end:  </h3>
                    <h2 class="card-title"> {{ $card['expires_end'] }} </h2>
                </div>
                <h2 class="card-title"> {{ $card['sum'] }} {{ $card['currency'] }} </h2>
            </div>
        </div>
        <h1 class="index-title">Transactions</h1>
        <div class="transactions">
            @if (count($transactions))
                @foreach ($transactions as $transaction)
                    <div class="transaction">
                        <div class="spacing">
                            <h2 class="card-title"> <b>{{ $transaction['comment'] }} </b></h2>
                            <h2 class="card-title"> {{ $transaction['date'] }} </h2>
                        </div>
                        @if($card['number'] == $transaction['card_to'])
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
            @else
                <h2 class="text">None...</h2>
            @endif
        </div>
    </div>

@endsection
