<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sm btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-sm btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>
        @if(Auth::User()->activeTimers->count() > 0)
            <li class="nav-item dropdown">
                <a href="#" class="nav-link" data-toggle="modal" data-target="#timers-preview-modal">
                    <i class="fas fa-clock mr-1"></i>{{ Auth::User()->activeTimers->count() }}
                </a>
            </li>
        @endif
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar->path) : asset('dist/img/user.png') }}" class="img-circle mr-2" alt="User Image" style="width: 25px;height: 25px;">
                {{ Auth::User()->name }}
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('users.show', ['user' => Auth::User()->id]) }}">Profile</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                    @method('POST')
                </form>
            </div>
        </li>
    </ul>
</nav>