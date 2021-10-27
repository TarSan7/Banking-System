@extends('layout.main')
@extends('layout.headerPrivate')

@section('title') {{ __('allTransactions.transactions') }} @endsection

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/main.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <div class="container">
        <div class="transactions" id="transactions">
            @if (count($transactions))
                @foreach ($transactions as $cardTransactions)
                    <h1 class="index-title">{{ __('allTransactions.of-card') }}  {{ $cardTransactions['number'] }}</h1>
                    <table id="table_id" class="display transactions">
                        <thead>
                        <tr>
                            <th>{{ __('allTransactions.appointment') }}</th>
                            <th>{{ __('allTransactions.from') }}</th>
                            <th>{{ __('allTransactions.to') }}</th>
                            <th>{{ __('allTransactions.sum') }}</th>
                            <th>{{ __('allTransactions.currency') }}</th>
                            <th>{{ __('allTransactions.date') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($cardTransactions['oneCard'] as $transaction)
                            <tr>
                                <td> {{ $transaction['comment'] }} </td>
                                @if($cardTransactions['number'] == $transaction['card_to'])
                                    <td> {{ $transaction['card_from'] }} </td>
                                    <td> {{ $transaction['card_to'] }} </td>
                                    <td> +{{ $transaction['sum'] }} </td>
                                    <td> {{ $transaction['currency'] }} </td>
                                @else
                                    @if($transaction['comment'] == 'Transfer to another card.' || $transaction['comment'] == 'Провести перевод на карту.')
                                        <td> {{ $transaction['card_from'] }} </td>
                                        <td> {{ $transaction['card_to'] }} </td>
                                    @else
                                        <td> {{ $transaction['card_from'] }} </td>
                                        <td> {{ $transaction['card_to'] }} </td>
                                    @endif
                                    <td> -{{ $transaction['sum'] }} </td>
                                    <td> {{ $transaction['currency'] }} </td>
                                @endif
                                <td> {{ $transaction['date'] }} </td>
                            </tr>
                        @endforeach
                            </tbody>
                    </table>
                @endforeach
            @else
                <h2 class="text">{{ __('allTransactions.none') }} </h2>
            @endif
        </div>
    </div>

    <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('table.display').DataTable();
        } );
    </script>
@endsection
