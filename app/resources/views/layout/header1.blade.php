<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">YourBank</a>
        <div class="d-flex">
            <div class="nav-item active">
                <a class="nav-link" href="{{ route('user.logout') }}"><span class="sr-only">Log out</span></a>
            </div>
        </div>
    </div>
</nav>
