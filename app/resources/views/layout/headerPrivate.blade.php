<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ route('user.private', app()->getLocale()) }}">YourBank</a>
        <div class="d-flex">
            <div class="nav-item active">
                @foreach (config('app.available_locales') as $locale)
                    <div class="nav-item">
                        <a class="nav-link active" href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), $locale)}}">
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
