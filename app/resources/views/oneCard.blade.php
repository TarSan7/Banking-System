@extends('layout.main')

@section('title') {{ __('private.info') }} @endsection

@section('style')
    <link rel="stylesheet" href="{{ mix('/css/main.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('user.private', app()->getLocale()) }}">YourBank</a>
            <div class="d-flex">
                <div class="nav-item active">
                    @foreach (config('app.available_locales') as $locale)
                        <div class="nav-item">
                            <a class="nav-link active" href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), [$locale, 'id' => $card['id']])}}">
                        <span class="sr-only">
                            {{ strtoupper($locale) }}
                        </span>
                            </a>
                        </div>
                    @endforeach
                    <a class="nav-link" href="{{ route('user.private', app()->getLocale()) }}"><span class="sr-only">{{ __('index.home') }}</span></a>
                    <a class="nav-link" href="{{ route('user.logout', app()->getLocale()) }}"><span class="sr-only">{{ __('index.out') }}</span></a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container">
        <a href="{{ url()->previous(app()->getLocale()) }}" class="arrow">
            <picture>
                <source srcset="/img/arr.webp" type="image/webp" class="arrow">
                <img src="/img/arr.png" alt="arrow" class="arrow">
            </picture>
        </a>
    </div>

    <div class="container" id="transactions">
        <div class="to-center">
            <div class="card active" style="background-image: url({{ $path }});">
                <div class="cardText">
                    <div class="spacing">
                        <h2 class="card-title"> {{ __('private.card') }} </h2>
                        <h2 class="card-title"> {{ $card['number'] }} </h2>
                    </div>
                    <div class="spacing">
                        <h3 class="card-info"> {{ __('private.type') }} </h3>
                        <h2 class="card-title"> {{ $card['type'] }} </h2>
                    </div>
                    <div class="spacing">
                        <h3 class="card-info"> {{ __('private.cvv') }} </h3>
                        <h2 class="card-title"> {{ $card['cvv'] }} </h2>
                    </div>
                    <div class="spacing">
                        <h3 class="card-info"> {{ __('private.end') }}  </h3>
                        <h2 class="card-title"> {{ $card['expires_end'] }} </h2>
                    </div>
                    <h2 class="card-title"> {{ $card['sum'] }} {{ $card['currency'] }} </h2>
                </div>
                <div class="displayNone">
                    <form action="{{ route('user.changeImg', [app()->getLocale(), 'id' => $card['id']]) }}" method="post" target="hiddenframe" enctype="multipart/form-data">
                        @csrf
                        <h2 class="card-title">Change card image:</h2>
                        <input type="file" name="AddImage" id="AddImage" class="card-button" accept=".jpg, .jpeg, .png"/>
                        <div class="buttons-card">
                            <input type="submit" class="card-button" name="upload" id="upload" value="Set image"/>
                            <input type="reset" class="card-button" name="reset" id="reset" value="Reset to initial"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @error('error')
        <div class="alert">{{ $message }}</div>
        @enderror
        <h1 class="index-title">{{ __('allTransactions.transactions') }}</h1>
        <table id="table_id" class="display transactions">
            @if (count($transactions))
                <thead>
                <tr>
                    <th>{{ __('allTransactions.appointment') }}</th>
                    <th>{{ __('allTransactions.from') }}</th>
                    <th>{{ __('allTransactions.to') }}</th>
                    <th>{{ __('allTransactions.sum') }}</th>
                    <th>{{ __('allTransactions.currency') }}</th>
                    <th>{{ __('allTransactions.date') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td> {{ $transaction['comment'] }} </td>
                        @if($card['number'] == $transaction['card_to'])
                            <td> {{ $transaction['card_from'] }} </td>
                            <td> {{ $transaction['card_to'] }} </td>
                            <td> +{{ $transaction['sum'] }} </td>
                            <td> {{ $transaction['currency'] }} </td>
                        @else
                            @if($transaction['comment'] == 'Transfer to another card.' || $transaction['comment'] == 'Провести перевод на карту.')
                                <td> {{ $transaction['card_from'] }} </td>
                                <td> {{ $transaction['card_to'] }} </td>
                            @else
                                <td> {{ $transaction['card_from'] }} </td>
                                <td> {{ $transaction['card_to'] }} </td>
                            @endif
                            <td> -{{ $transaction['sum'] }} </td>
                            <td> {{ $transaction['currency'] }} </td>
                        @endif
                        <td> {{ $transaction['date'] }} </td>
                    </tr>
                @endforeach
                </tbody>
            @else
                <h2 class="text">{{ __('allTransactions.none') }}</h2>
            @endif
        </table>
    </div>

    <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#table_id').DataTable();
        } );
    </script>
    <script src = "{{ mix('/js/cardRotate.js') }}"></script>

@endsection
