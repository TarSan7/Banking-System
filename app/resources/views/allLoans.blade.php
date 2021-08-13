@extends('layout.main')
@extends('layout.headerPrivate')

@section('title') Loans @endsection

@section('style')
    <link rel="stylesheet" href="./css/main.css">
@endsection

@section('content')
    <div class="container">
        <h1 class="index-title">List of loans:</h1>
        <div class="to-center">
            <div class="loans">
                @if(count($loans) > 0)
                    @foreach($loans as $loan)
                        <a href="{{ route('user.takeLoan', ['id' => $loan['id']]) }}" class="card-link">
                            <div class="loan col-md-6 col-12">
                                <h2 class="loan-title"> "{{ $loan['title'] }}" </h2>
                                <div class="spacing">
                                    <h3 class="card-info"> Percents: </h3>
                                    <h2 class="card-title"> {{ $loan['percent'] }}% </h2>
                                </div>
                                <div class="spacing">
                                    <h3 class="card-info"> Duration: </h3>
                                    <h2 class="card-title"> {{ $loan['duration'] }} mm</h2>
                                </div>
                                <div class="spacing">
                                    <h3 class="card-info"> Maximal sum to take: </h3>
                                    <h2 class="card-title"> {{ $loan['max_sum'] }} {{ $loan['currency'] }}</h2>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
        <h1 class="index-title">Your loans:</h1>
        <div class="to-center">
            <div class="loans">
{{--                @if(count($yourLoans) > 0)--}}
{{--                    @foreach($yourLoans as $one)--}}
{{--                        <div class="loan col-md-6 col-12">--}}
{{--                            <h2 class="loan-title"> "{{ $one['loan_id'] }}" </h2>--}}
{{--                            <div class="spacing">--}}
{{--                                <h3 class="card-info"> Percents: </h3>--}}
{{--                                <h2 class="card-title"> {{ $one['sum'] }}% </h2>--}}
{{--                            </div>--}}
{{--                            <div class="spacing">--}}
{{--                                <h3 class="card-info"> Duration: </h3>--}}
{{--                                <h2 class="card-title"> {{ $one['total_sum'] }} mm</h2>--}}
{{--                            </div>--}}
{{--                            <div class="spacing">--}}
{{--                                <h3 class="card-info"> Duration: </h3>--}}
{{--                                <h2 class="card-title"> {{ $one['month_pay'] }}</h2>--}}
{{--                            </div>--}}
{{--                            <div class="spacing">--}}
{{--                                <h3 class="card-info"> Duration: </h3>--}}
{{--                                <h2 class="card-title"> {{ $one['month_left'] }} mm</h2>--}}
{{--                            </div>--}}
{{--                            <div class="spacing">--}}
{{--                                <h3 class="card-info"> Duration: </h3>--}}
{{--                                <h2 class="card-title"> {{ $one[''] }} mm</h2>--}}
{{--                            </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    @endforeach--}}
{{--                @endif--}}
            </div>
        </div>
    </div>
@endsection

