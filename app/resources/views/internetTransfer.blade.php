@extends('layout.main')
@extends('layout.headerPrivate')

@section('title') Main Page @endsection

@section('style')
    <link rel="stylesheet" href="./css/main.css">
@endsection

@section('content')
    <h1 class="index-title">Make internet replenishment</h1>
    @if(session()->has('success'))
        <p class="success">{{ session('success')}}</p>
    @endif
    <div class="container to-center">
        <form action="{{ route('user.internetTransfer') }}" method="post" class="transfer-form">
            @csrf
            <div class="form-group">
                <label for="numberFrom" class="form-label">Card from</label>
                <select name="numberFrom" id="numberFrom" class="input-field" placeholder="Card number" required>
                    @foreach($cards as $card)
                        <option value="{{ $card['id'] }}"> {{ $card['number'] }} </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="numberTo" class="form-label">Internet provider</label>
                <select name="numberTo" id="numberTo" class="input-field" placeholder="Provider" required>
                    <option value="lanet"> Lanet </option>
                    <option value="kyivstar"> Kyivstar </option>
                    <option value="volia"> Volia </option>
                    <option value="vega"> Vega </option>
                    <option value="triolan"> Triolan </option>
                    <option value="datagroup"> Datagroup </option>
                    <option value="kpitelecom"> KPITelecom </option>
                </select>
            </div>
            <div class="form-group">
                <label for="sum" class="form-label">Sum</label>
                <input type="number" id="sum" min="1" step="0.01" name="sum" class="input-field" placeholder="Sum" required>
            </div>
            <div class="form-group">
                <label for="comment" class="form-label">Comment</label>
                <textarea id="comment" name="comment" class="input-field">Internet replenishment.</textarea>
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
            <div class="form-group" id="button">
                <button name="send" type="submit" class="form-button">Submit</button>
            </div>
        </form>
    </div>

@endsection
