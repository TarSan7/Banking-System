@extends('layout.main')
@extends('layout.headerPrivate')

@section('title') Take a deposit @endsection

@section('style')
    <link rel="stylesheet" href="../css/main.css">
@endsection

@section('content')
    <h1 class="index-title">Details of your deposit<br>"{{ $deposit['title'] }}"</h1>
    @if(session()->has('success'))
        <p class="success">{{ $session('success') }}</p>
    @endif
    <div class="container">
        <div class="to-center">
            <form action="{{ route('user.acceptDeposit', ['id' => $deposit['id']]) }}" method="post" class="deposit-form">
                @csrf
                <div class="form-group">
                    <label for="numberFrom" class="form-label">Card from</label>
                    <input type="text" id="numberFrom" name="numberFrom" class="input-field" value="{{ $cardFrom }}" readonly>
                </div>
                <div class="form-group">
                    <label for="sum" class="form-label"> Sum </label>
                    <input type="number" id="sum" name="sum" class="input-field" value="{{ $sum }}" readonly>
                    <input type="text" id="currency" name="currency" class="form-label-r" value="{{ $currency }}" readonly>
                </div>
                <div class="form-group">
                    <label for="duration" class="form-label"> Duration</label>
                    <div class="duration">
                        <span class="bold">
                            <input type="text" id="duration" name="duration" class="form-label-r" value="{{ $duration }}" readonly>
                        </span>
                        <p class="form-label" id="month">months</p>
                    </div>
                </div>
                <div class="form-group">
                    @if ($percent == $deposit['early_percent'])
                        <label for="percents" class="form-label"> Early percents: </label>
                    @else
                        <label for="percents" class="form-label"> In time percents: </label>
                    @endif
                    <div class="percent">
                        <span class="bold">
                        <input type="text" id="percent" name="percent" class="form-label-r" value="{{ $percent }}" readonly>
                        </span>
                        <p class="form-label-r" id="duration">%</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="totalSum" class="form-label"> Total sum of deposit </label>
                    <label for="totalSum" class="form-label-r">
                        <span class="bold">
                            {{ round((($percent * $duration) / 12 * 0.01 * $sum) + $sum, 2) }}
                        </span>
                        {{ $currency }}
                    </label>
                </div>
                <div class="form-group">
                    <label for="totalExp" class="form-label"> Interest amount of deposit </label>
                    <label for="totalExp" class="form-label-r">
                        <span class="bold">
                            {{ round(($percent * $duration) / 12 * 0.01 * $sum, 2) }}
                        </span>
                        {{ $currency }}
                    </label>
                </div>
                <div class="form-group">
                    <label for="monthPay" class="form-label"> Monthly payment </label>
                    <label for="monthPay" class="form-label-r">
                        <span class="bold">
                            {{ round(($percent * 0.01 * $sum) / $duration, 2)}}
                        </span> {{ $currency }}
                    </label>
                </div>
                <div class="form-group" id="button">
                    <button name="send" type="submit" class="form-button">Apply the deposit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
