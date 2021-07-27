@extends('layout.main')
@extends('layout.header1')

@section('title') Main Page @endsection

@section('style')
    <link rel="stylesheet" href="./css/main.css">
@endsection

@section('content')
    <h1 class="index-title">Add your card</h1>
    <div class="container">
        <div class="form">
            <form action="{{ route('user.addCard') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="number" class="form-label">Card number</label>
                    <input type="text" id="number" name="number" class="input-field" placeholder="Card number" required>
                </div>
                <div class="form-group">
                    <label for="cvv" class="form-label">CVV</label>
                    <input type="text" id="cvv" name="cvv" class="input-field" placeholder="CVV" required>
                </div>
                <div class="form-group">
                    <label for="date" class="form-label">Card expires end</label>
                    <input type="date" id="expires-end" name="expires-end" class="input-field" placeholder="Expires end" required>
                </div>
                @error('number')
                <div class="alert">{{ $message }}</div>
                @enderror
                @error('cvv')
                <div class="alert">{{ $message }}</div>
                @enderror
                @error('expires-end')
                <div class="alert">{{ $message }}</div>
                @enderror
                <div class="form-group" id="button">
                    <button name="send" type="submit" class="form-button">Add</button>
                </div>
            </form>

        </div>
    </div>
@endsection

