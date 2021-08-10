@extends('layout.main')
@extends('layout.headerPrivate')

@section('title') Take a loan @endsection

@section('style')
    <link rel="stylesheet" href="../css/main.css">
@endsection

@section('content')
    <h1 class="index-title">Calculate your loan<br>"{{ $loan['title'] }}"</h1>
    @if (session()->has('success'))
        <p class="success">{{ session('success') }}</p>
    @endif
    <div class="container">
        <div class="to-center">
            <form action="{{ route('user.takeLoan', ['id' => $loan['id']]) }}" method="post" class="loan-form">
                @csrf
                <div class="form-group">
                    <label for="sum" class="form-label"> Sum </label>
                    <input type="number" id="loan-sum" min="100" step="1" max="{{ $loan['max_sum'] }}"
                           name="sum" class="input-field" placeholder="Sum" required>
                    <label for="sum" class="form-label"> {{ $loan['currency'] }} </label>
                </div>
                <div class="form-group">
                    <label for="duration" class="form-label"> Duration</label>
                    <label for="duration" class="form-label"><span class="bold">{{ $loan['duration'] }}</span> months</label>
                </div>
                <div class="form-group">
                    <label for="percents" class="form-label"> Percents</label>
                    <label for="percents" class="form-label"><span class="bold">{{ $loan['percent'] }}%</span></label>
                </div>
                @error('sum')
                    <div class="alert">{{ $message }}</div>
                @enderror
                <div class="form-group" id="button">
                    <button name="send" type="submit" class="form-button">See details</button>
                </div>
            </form>
        </div>
    </div>
@endsection
