@extends('layout.main')
@extends('layout.headerPrivate')

@section('title') {{ __('allLoans.loans') }} @endsection

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/main.css') }}">
@endsection

@section('content')
    <div class="container">
        <h1 class="index-title">{{ __('allLoans.list') }}</h1>
        <div class="to-center">
            <div class="loans ">
                @if(count($loans) > 0)
                    @foreach($loans as $loan)
                        <a href="{{ route('user.takeLoan', [app()->getLocale(), 'id' => $loan['id']]) }}" class="card-link">
                            <div class="standard loan col-md-6 col-12">
                                <h2 class="loan-title"> "{{ $loan['title'] }}" </h2>
                                <div class="spacing">
                                    <h3 class="card-info"> {{ __('allLoans.percents') }} </h3>
                                    <h2 class="card-title"> {{ $loan['percent'] }}% </h2>
                                </div>
                                <div class="spacing">
                                    <h3 class="card-info"> {{ __('allLoans.duration') }} </h3>
                                    <h2 class="card-title"> {{ $loan['duration'] }} mm</h2>
                                </div>
                                <div class="spacing">
                                    <h3 class="card-info"> {{ __('allLoans.max-sum') }} </h3>
                                    <h2 class="card-title"> {{ $loan['max_sum'] }} {{ $loan['currency'] }}</h2>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
        <h1 class="index-title">{{ __('allLoans.your') }}</h1>
        <div class="to-center">
            <div class="loans">
                @if(count($yourLoans) > 0)
                    @foreach($yourLoans as $one)
                        <div class="loan">
                            <h2 class="loan-title"> "{{ $one['title'] }}" </h2>
                            <div class="spacing">
                                <h3 class="card-info"> {{ __('allLoans.percents') }} </h3>
                                <h2 class="card-title"> {{ $one['percent'] }}% </h2>
                            </div>
                            <div class="spacing">
                                <h3 class="card-info"> {{ __('allLoans.duration') }} </h3>
                                <h2 class="card-title"> {{ $one['duration'] }} mm</h2>
                            </div>
                            <div class="spacing">
                                <h3 class="card-info"> {{ __('allLoans.total') }} </h3>
                                <h2 class="card-title"> {{ $one['total-sum'] }} {{ $one['currency'] }}</h2>
                            </div>
                            <div class="spacing">
                                <h3 class="card-info"> {{ __('allLoans.month') }} </h3>
                                <h2 class="card-title"> {{ $one['month-pay'] }} {{ $one['currency'] }}</h2>
                            </div>
                            <div class="spacing">
                                <h3 class="card-info"> {{ __('allLoans.left') }} </h3>
                                <h2 class="card-title"> {{ $one['month-left'] }} mm</h2>
                            </div>
                            <div class="spacing">
                                <h3 class="card-info"> {{ __('allLoans.number') }} </h3>
                                <h2 class="card-title"> {{ $one['card-number'] }}</h2>
                            </div>
                        </div>
                    @endforeach
                @else
                    <h2 class="text">{{ __('allLoans.none') }}</h2>
                @endif
            </div>
        </div>
    </div>

    <script src = "{{ mix('/js/moveOther.js') }}"></script>
@endsection

