@extends ('layout.main')

@section ('title') {{ __('otherTrans.other') }}@endsection

@section ('style')
    <link rel="stylesheet" href="/css/main.css">
@endsection

@section ('content')
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('user.private', app()->getLocale()) }}">YourBank</a>
            <div class="d-flex">
                <div class="nav-item active">
                    @foreach (config('app.available_locales') as $locale)
                        <div class="nav-item">
                            <a class="nav-link active" href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), [$locale, 'id' => $id])}}">
                        <span class="sr-only">
                            @if (app()->getLocale() == $locale) @endif {{ strtoupper($locale) }}
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
        {{--    @if ()--}}
        <a href="{{ url()->previous(app()->getLocale()) }}" class="arrow">
            <img src="/img/arr.png" alt="arrow" class="arrow">
        </a>
    </div>

    <h1 class="index-title">{{ __('otherTrans.make') }}
        @if ('en' === app()->getLocale())
            {{ $id }}
        @endif
        {{ __('otherTrans.replenish') }}</h1>
    @if (session()->has('success'))
        <p class="success">{{ session('success')}}</p>
    @endif
    <div class="container to-center">
        <form action="{{ route('user.otherTransfer', [app()->getLocale(), 'id' => $id]) }}" method="post" class="transfer-form">
            @csrf
            <div class="form-group">
                <label for="numberFrom" class="form-label">{{ __('otherTrans.from') }}</label>
                <select name="numberFrom" id="numberFrom" class="input-field" required>
                    @foreach ($cards as $card)
                        <option value="{{ $card['id'] }}"> {{ $card['number'] }} </option>
                    @endforeach
                </select>
            </div>
            @if ($id === 'phone')
                <div class="form-group">
                    <label for="numberTo" class="form-label">{{ __('otherTrans.phone') }}</label>
                    <input type="text" id="numberTo" name="numberTo" class="input-field" placeholder="+380" required>
                </div>
            @else
                <div class="form-group">
                    <label for="numberTo" class="form-label">{{ __('otherTrans.provider') }}</label>
                    <select name="numberTo" id="numberTo" class="input-field" required>
                        <option value="Lanet"> Lanet </option>
                        <option value="Kyivstar"> Kyivstar </option>
                        <option value="Volia"> Volia </option>
                        <option value="Vega"> Vega </option>
                        <option value="Triolan"> Triolan </option>
                        <option value="Datagroup"> Datagroup </option>
                        <option value="KPITelecom"> KPITelecom </option>
                    </select>
                </div>
            @endif
            <div class="form-group">
                <label for="sum" class="form-label">{{ __('otherTrans.sum') }}</label>
                <input type="number" id="sum" min="1" step="0.01" name="sum" class="input-field" placeholder="{{ __('otherTrans.sum') }}" required>
            </div>
            <div class="form-group">
                <label for="comment" class="form-label">{{ __('otherTrans.comm') }}</label>
                @if ($id == 'phone')
                    <textarea id="comment" name="comment" class="input-field">{{ __('otherTrans.com-phone') }}</textarea>
                @else
                    <textarea id="comment" name="comment" class="input-field">{{ __('otherTrans.com-intern') }}</textarea>
                @endif
            </div>
            @error ('numberFrom')
            <div class="alert">{{ $message }}</div>
            @enderror
            @error ('numberTo')
            <div class="alert">{{ $message }}</div>
            @enderror
            @error ('sum')
            <div class="alert">{{ $message }}</div>
            @enderror
            @error('error')
            <div class="alert">{{ $message }}</div>
            @enderror
            <div class="form-group" id="button">
                <button name="send" type="submit" class="form-button">{{ __('otherTrans.submit') }}</button>
            </div>
        </form>
    </div>

@endsection
