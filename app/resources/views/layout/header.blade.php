<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home', app()->getLocale()) }}">YourBank</a>
        <div class="d-flex">
            @foreach (config('app.available_locales') as $locale)
                <div class="nav-item">
                    <a class="nav-link active" href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), $locale)}}">
                        <span class="sr-only">
                            {{ strtoupper($locale) }}
                        </span>
                    </a>
                </div>
            @endforeach
            <div class="nav-item">
                <a class="nav-link active" href="{{ route('user.login', app()->getLocale()) }}">
                    <span class="sr-only">
                        {{ __('index.sign-in') }}
                    </span>
                </a>
            </div>
            <div class="nav-item active">
                <a class="nav-link" href="{{ route('user.registration', app()->getLocale()) }}">
                    <span class="sr-only">
                        {{ __('index.sign-up') }}
                    </span>
                </a>
            </div>
        </div>
    </div>
</nav>
