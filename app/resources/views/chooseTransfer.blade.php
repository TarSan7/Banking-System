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
            <li class="item"><a href="{{ route('user.cardTransfer') }}" class="text"> To another card</a></li>
            <li class="item"><a href="" class="text"> Phone replenishment</a></li>
            <li class="item"><a href="" class="text"> Internet payment </a></li>
        </ul>
    </div>

@endsection
