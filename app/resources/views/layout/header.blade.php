<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">YourBank</a>
        <div class="d-flex">
            <div class="nav-item">
                <a class="nav-link active" href="{{ route('user.login') }}"><span class="sr-only">Sign in</span></a>
            </div>
            <div class="nav-item active">
                <a class="nav-link" href="{{ route('user.registration') }}"><span class="sr-only">Sign up</span></a>
            </div>
        </div>
    </div>
</nav>
