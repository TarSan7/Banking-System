@extends('layout.main')
@extends('layout.headerPrivate')

@section('title') Deposits @endsection

@section('style')
    <link rel="stylesheet" href="./css/main.css">
@endsection

@section('content')
    <div class="container">
        @if(session()->has('success'))
            <p class="success">{{ session('success')}}</p>
        @endif
        <h1 class="index-title">List of deposits:</h1>
        @error('error')
            <div class="alert">{{ $message }}</div>
        @enderror
        <div class="to-center">
            <div class="deposits">
                @if(count($deposits) > 0)
                    @foreach($deposits as $deposit)
                        <a href="{{ route('user.takeDeposit', ['id' => $deposit['id']]) }}" class="card-link">
                            <div class="deposit col-md-6 col-12">
                                <h2 class="deposit-title"> "{{ $deposit['title'] }}" </h2>
                                <div class="spacing">
                                    <h3 class="card-info"> Early percents: </h3>
                                    <h2 class="card-title"> {{ $deposit['early_percent'] }}% </h2>
                                </div>
                                <div class="spacing">
                                    <h3 class="card-info"> In time percents: </h3>
                                    <h2 class="card-title"> {{ $deposit['intime_percent'] }}% </h2>
                                </div>
                                <div class="spacing">
                                    <h3 class="card-info"> Minimal duration: </h3>
                                    <h2 class="card-title"> {{ $deposit['min_duration'] }} mm</h2>
                                </div>
                                <div class="spacing">
                                    <h3 class="card-info"> Maximal duration: </h3>
                                    <h2 class="card-title"> {{ $deposit['max_duration'] }} mm</h2>
                                </div>
                                <div class="spacing">
                                    <h3 class="card-info"> Maximal sum to put: </h3>
                                    <h2 class="card-title"> {{ $deposit['max_sum'] }} cur</h2>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
        <h1 class="index-title">Your deposits:</h1>
        <div class="to-center">
            <div class="loans">
                @if(count($yourDeposits) > 0)
                    @foreach($yourDeposits as $one)
                        <div class="deposit">
                            <h2 class="deposit-title"> "{{ $one['title'] }}" </h2>
                            <div class="spacing">
                                @if ($one['early_percent'] > 0)
                                    <h3 class="card-info"> Early percents: </h3>
                                    <h2 class="card-title"> {{ $one['early_percent'] }}% </h2>
                                @else
                                    <h3 class="card-info"> In time percents: </h3>
                                    <h2 class="card-title"> {{ $one['intime_percent'] }}% </h2>
                                @endif
                            </div>
                            <div class="spacing">
                                <h3 class="card-info"> Duration: </h3>
                                <h2 class="card-title"> {{ $one['duration'] }} mm</h2>
                            </div>
                            <div class="spacing">
                                <h3 class="card-info"> Current sum: </h3>
                                <h2 class="card-title"> {{ $one['total-sum'] }} {{ $one['currency'] }}</h2>
                            </div>
                            <div class="spacing">
                                <h3 class="card-info"> Month pay: </h3>
                                <h2 class="card-title"> {{ $one['month-pay'] }} {{ $one['currency'] }}</h2>
                            </div>
                            <div class="spacing">
                                <h3 class="card-info"> Month left: </h3>
                                <h2 class="card-title"> {{ $one['month-left'] }} mm</h2>
                            </div>
                            <div class="spacing">
                                @if ($one['early_percent'] > 0)
                                    <form action="{{ route('user.closeDeposit', ['id' => $one['id']]) }}"
                                          method="post" class="deposit-form">
                                        @csrf
                                        <div class="form-group" id="button">
                                            <button name="send" type="submit" class="form-button">Close deposit</button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <h2 class="text">None...</h2>
                @endif
            </div>
        </div>
    </div>
@endsection

