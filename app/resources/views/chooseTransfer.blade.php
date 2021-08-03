@extends('layout.main')
@extends('layout.headerPrivate')

@section('title') Main Page @endsection

@section('style')
    <link rel="stylesheet" href="./css/main.css">
@endsection

@section('content')
    <h1 class="index-title">Choose transfer</h1>

    <div class="container to-center">
        <ul class="menu-transfer">
            <li class="item"><a href="{{ route('user.cardTransfer') }}" class="text" id="text-transfers"> To another card</a></li>
            <li class="item"><a href="{{ route('user.phoneTransfer', ['id' => 'phone']) }}" class="text" id="text-transfers"> Phone replenishment</a></li>
            <li class="item"><a href="{{ route('user.phoneTransfer', ['id' => 'internet']) }}" class="text" id="text-transfers"> Internet payment </a></li>
        </ul>
    </div>

@endsection
