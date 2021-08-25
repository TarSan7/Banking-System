@extends('layout.main')
@extends('layout.headerPrivate')

@section('title') Take a loan @endsection

@section('style')
    <link rel="stylesheet" href="../css/main.css">
@endsection

@section('content')
    <h1 class="index-title">Details of your loan<br>"{{ $loan['title'] }}"</h1>
    @if(session()->has('success'))
        <p class="success">{{ $session('success') }}</p>
    @endif
    <div class="container">
        <div class="to-center">
            <form action="{{ route('user.acceptLoan', ['id' => $loan['id']]) }}" method="post" class="loan-form">
                @csrf
                <div class="form-group">
                    <label for="sum" class="form-label"> Sum </label>
                    <input type="number" id="sum" name="sum" class="input-field" value="{{ $sum }}" readonly>
                    <label for="sum" class="form-label"> {{ $loan['currency'] }} </label>
                </div>
                <div class="form-group">
                    <label for="duration" class="form-label"> Duration</label>
                    <label for="duration" class="form-label"><span class="bold">{{ $loan['duration'] }}</span> months</label>
                </div>
                <div class="form-group">
                    <label for="percents" class="form-label"> Percents </label>
                    <label for="percents" class="form-label"><span class="bold">{{ $loan['percent'] }}%</span></label>
                </div>
                <div class="form-group">
                    <label for="totalSum" class="form-label"> Total price of loan </label>
                    <label for="totalSum" class="form-label">
                        <span class="bold">{{ (($loan['percent'] * $loan['duration']) / 12 * 0.01 * $sum) + $sum }}</span>
                        {{ $loan['currency'] }}
                    </label>
                </div>
                <div class="form-group">
                    <label for="totalExp" class="form-label"> Total loan expenses </label>
                    <label for="totalExp" class="form-label">
                        <span class="bold">{{ (($loan['percent'] * $loan['duration']) / 12 * 0.01 * $sum) }}</span> {{ $loan['currency'] }}
                    </label>
                </div>
                <div class="form-group">
                    <label for="monthPay" class="form-label"> Monthly payment </label>
                    <label for="monthPay" class="form-label">
                        <span class="bold">{{ round((($loan['percent'] * 0.01 * $sum) + $sum) / $loan['duration'], 2)}}
                            {{ $loan['currency'] }}</span>
                    </label>
                </div>
                <div class="form-group" id="button">
                    <button name="send" type="submit" class="form-button">Apply for a loan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
