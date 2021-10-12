@extends('layout.main')
@extends('layout.headerPrivate')

@section('title') {{ __('allTransactions.transactions') }} @endsection

@section('style')
    <link rel="stylesheet" href="../css/main.css">
@endsection

@section('content')
    <div class="container">

        <div class="transactions">
            @if (count($transactions))
                @foreach ($transactions as $cardTransactions)
                        <h1 class="index-title">{{ __('allTransactions.of-card') }}  {{ $cardTransactions['number'] }}</h1>
                        @foreach ($cardTransactions['oneCard'] as $transaction)
                            <div class="transaction">
                                <div class="spacing">
                                    <h2 class="card-title"> <b>{{ $transaction['comment'] }} </b></h2>
                                    <h2 class="card-title"> {{ $transaction['date'] }} </h2>
                                </div>
                                @if($cardTransactions['number'] == $transaction['card_to'])
                                    <h3 class="card-info"> {{ __('allTransactions.from') }} {{ $transaction['card_from'] }} </h3>
                                    <h3 class="card-info"> {{ __('allTransactions.sum') }} <b class="card-to">+{{ $transaction['sum'] }}
                                            {{ $transaction['currency'] }} </b></h3>
                                @else
                                    @if($transaction['comment'] == 'Transfer to another card.' || $transaction['comment'] == 'Провести перевод на карту.')
                                        <h3 class="card-info"> {{ __('allTransactions.to') }} {{ $transaction['card_to'] }} </h3>
                                    @endif
                                    <h3 class="card-info"> {{ __('allTransactions.sum') }} <b class="card-from">-{{ $transaction['sum'] }}
                                            {{ $transaction['currency'] }}</b></h3>
                                @endif
                            </div>
                        @endforeach
                @endforeach
            @else
                <h2 class="text">{{ __('allTransactions.none') }} </h2>
            @endif
        </div>
    </div>
@endsection
