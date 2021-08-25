@extends('layout.main')
@extends('layout.headerPrivate')

@section('title') Main Page @endsection

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
                <h3 class="card-info"> CVV {{ $card['cvv'] }} </h3>
                <h3 class="card-info"> Expires end:  {{ $card['expires_end'] }} </h3>
                <h2 class="card-title"> {{ $card['sum'] }} {{ $card['currency'] }} </h2>
            </div>
        </div>
        <h1 class="index-title">Transactions</h1>
        <div class="transactions">

        </div>
    </div>

@endsection
