@extends('layout.main')
@extends('layout.header1')

@section('title') Main Page @endsection

@section('style')
    <link rel="stylesheet" href="./css/main.css">
@endsection

@section('content')
    <h1 class="index-title">Your Cards</h1>
    <div class="container">
        <div class="cards">
            @if(Car)
        </div>
    </div>
@endsection
