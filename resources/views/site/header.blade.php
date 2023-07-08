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
        <li style="{{ Auth::User()->activeTimers->count() > 0 ? '' : 'display: none;' }}" id="timer-nav" class="nav-item dropdown">
            <a href="#" id="timer-counter" class="nav-link" data-toggle="modal" data-target="#timers-preview-modal">
                <i class="fas fa-clock mr-1"></i>{{ Auth::User()->activeTimers->count() }}
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge unread-notifications-count">{{ auth()->user()->unreadNotifications()->count() }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header"><span class="unread-notifications-count">{{ auth()->user()->unreadNotifications()->count() }}</span> Notifications</span>
            <div class="dropdown-divider"></div>
            @foreach (auth()->user()->unreadNotifications()->get() as $notification)
                <a href="#" class="dropdown-item notification-{{ $notification->id }}-item" @if ($notification->data['link'] ?? false) onclick="viewNotificationLink('{{ route('notifications.seen', $notification) }}', '{{ $notification->data['link'] }}')" @else onclick="seenNotification('{{ route('notifications.seen', $notification) }}', '{{ $notification->id }}')" @endif class="dropdown-item">
                    <span class="text-wrap">{!! Str::limit($notification->data['content'], 100, ' ...') !!}</span>
                    <span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
                </a>
            @endforeach
            <div class="dropdown-divider"></div>
            <div class="dropdown-divider"></div>
                <a href="{{ route('notifications.index') }}" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                <img src="{{ (Auth::user()->avatar ?? false) ? asset('storage/' . Auth::user()->avatar->path) : asset('dist/img/user.png') }}" class="img-circle mr-2" alt="User Image" style="width: 25px;height: 25px;">
                {{ Auth::User()->name }}
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('users.show', Auth::User()) }}">Profile</a>
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
