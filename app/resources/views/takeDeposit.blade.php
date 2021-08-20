@extends('layout.main')
@extends('layout.headerPrivate')

@section('title') Take deposit @endsection

@section('style')
    <link rel="stylesheet" href="../css/main.css">
@endsection

@section('content')
    <h1 class="index-title">Calculate your deposit<br>"{{ $deposit['title'] }}"</h1>
    @if (session()->has('success'))
        <p class="success">{{ session('success') }}</p>
    @endif
    <div class="container">
        <div class="to-center">
            <form action="{{ route('user.takeDeposit', ['id' => $deposit['id']]) }}" method="post" class="deposit-form">
                @csrf
                <div class="form-group">
                    <label for="numberFrom" class="form-label">Card from</label>
                    <select name="numberFrom" id="numberFrom" class="input-field" required>
                        @foreach($cards as $card)
                            <option value="{{ $card['id'] }}"> {{ $card['number'] }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="sum" class="form-label"> Sum </label>
                    <input type="number" id="deposit-sum" min="100" step="1" max="{{ $deposit['max_sum'] }}"
                           name="sum" class="input-field" placeholder="Sum" required>
                    <select name="currency" id="currency" class="input-field" required>
                        <option value="UAH"> UAH </option>
                        <option value="EUR"> EUR </option>
                        <option value="USD"> USD </option>
                        <option value="RUR"> RUR </option>
                        <option value="GBP"> GBP </option>
                        <option value="PLN"> PLN </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="duration" class="form-label"> Duration</label>
                    <select name="duration" id="duration" class="input-field" required>
                        @for ($i = $deposit['min_duration']; $i <= $deposit['max_duration']; $i++)
                            <option value="{{ $i }}"> {{ $i }} mm</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group">
                    <fieldset>
                        <label for="duration" class="form-label"> Percents:</label>
                        <div class="check">
                            <input class="form-label" type="radio" id="early_percent"
                                   name="percents" value="{{ $deposit['early_percent'] }}" checked>
                            <label class="form-label" for="early_percent">Early percent:
                                <span class="bold">{{ $deposit['early_percent'] }}% </span>
                            </label>
                        </div>
                        <div class="check">
                            <input class="form-label" type="radio" id="intime_percent"
                                   name="percents" value="{{ $deposit['intime_percent'] }}">
                            <label class="form-label" for="intime_percent">Intime percent:
                                <span class="bold">{{ $deposit['intime_percent'] }}% </span>
                            </label>
                        </div>
                    </fieldset>
                </div>
                @error('sum')
                <div class="alert">{{ $message }}</div>
                @enderror
                @error('error')
                <div class="alert">{{ $message }}</div>
                @enderror
                <div class="form-group" id="button">
                    <button name="send" type="submit" class="form-button">See details</button>
                </div>
            </form>
        </div>
    </div>
@endsection
