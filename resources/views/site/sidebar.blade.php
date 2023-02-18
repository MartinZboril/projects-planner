<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard.index') }}" class="brand-link">
        <img src="{{ asset('dist/img/icon.png') }}" alt="Projects planner logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Projects Planner</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard.index') }}" class="nav-link @if(request()->is('/') || str_contains(url()->current(), 'dashboard')){{ 'active' }}@endif">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('clients.index') }}" class="nav-link @if(str_contains(url()->current(), 'clients')){{ 'active' }}@endif">
                        <i class="nav-icon fas fa-address-book"></i>
                        <p>
                            Clients
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('projects.index') }}" class="nav-link @if(str_contains(url()->current(), 'projects') && (!str_contains(url()->current(), 'report') && !str_contains(url()->current(), 'analysis') && !str_contains(url()->current(), 'dashboard'))){{ 'active' }}@endif">
                        <i class="nav-icon fas fa-clock"></i>
                        <p>
                            Projects
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tasks.index') }}" class="nav-link @if(str_contains(url()->current(), 'tasks') && !str_contains(url()->current(), 'projects')  && (!str_contains(url()->current(), 'report') && !str_contains(url()->current(), 'analysis') && !str_contains(url()->current(), 'dashboard'))){{ 'active' }}@endif">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>
                            Tasks
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tickets.index') }}" class="nav-link @if(str_contains(url()->current(), 'tickets') && !str_contains(url()->current(), 'projects')  && (!str_contains(url()->current(), 'report') && !str_contains(url()->current(), 'analysis') && !str_contains(url()->current(), 'dashboard'))){{ 'active' }}@endif">
                        <i class="nav-icon fas fa-life-ring"></i>
                        <p>
                            Tickets
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link @if(str_contains(url()->current(), 'users')){{ 'active' }}@endif">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('reports.index') }}" class="nav-link @if(str_contains(url()->current(), 'report') || str_contains(url()->current(), 'analysis')){{ 'active' }}@endif">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>
                            Reports
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('notes.index') }}" class="nav-link @if(str_contains(url()->current(), 'notes') && !str_contains(url()->current(), 'client') && !str_contains(url()->current(), 'project')){{ 'active' }}@endif">
                        <i class="nav-icon far fa-sticky-note"></i>
                        <p>
                            Notes
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>