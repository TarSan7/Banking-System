@extends('layout.main')
@extends('layout.headerPrivate')

@section('title') {{ __('cardTrans.card-transfer') }} @endsection

@section('style')
    <link rel="stylesheet" href="../css/main.css">
@endsection

@section('content')
    <h1 class="index-title">{{ __('cardTrans.make') }}</h1>
    @if(session()->has('success'))
        <p class="success">{{ session('success')}}</p>
    @endif
    <div class="container to-center">
        <form action="{{ route('user.cardTransfer', app()->getLocale()) }}" method="post" class="transfer-form">
            @csrf
            <div class="form-group">
                <label for="numberFrom" class="form-label">{{ __('cardTrans.from') }}</label>
                <select name="numberFrom" id="numberFrom" class="input-field" required>
                    @foreach($cards as $card)
                        <option value="{{ $card['id'] }}"> {{ $card['number'] }} </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="numberTo" class="form-label">{{ __('cardTrans.card-to') }}</label>
                <input type="text" id="numberTo" name="numberTo" class="input-field" placeholder="{{ __('cardTrans.card-to') }}" required>
            </div>
            <div class="form-group">
                <label for="sum" class="form-label">{{ __('cardTrans.sum') }}</label>
                <input type="number" id="sum" min="1" step="0.01" name="sum" class="input-field" placeholder="{{ __('cardTrans.sum') }}" required>
            </div>
            <div class="form-group">
                <label for="comment" class="form-label">{{ __('cardTrans.comm') }}</label>
                <textarea id="comment" name="comment" class="input-field">{{ __('cardTrans.comment') }}</textarea>
            </div>
            @error('numberFrom')
            <div class="alert">{{ $message }}</div>
            @enderror
            @error('numberTo')
            <div class="alert">{{ $message }}</div>
            @enderror
            @error('sum')
            <div class="alert">{{ $message }}</div>
            @enderror
            @error('error')
            <div class="alert">{{ $message }}</div>
            @enderror
            <div class="form-group" id="button">
                <button name="send" type="submit" class="form-button">{{ __('cardTrans.submit') }}</button>
            </div>
        </form>
    </div>

@endsection
