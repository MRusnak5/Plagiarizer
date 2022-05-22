<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Plagiarizer</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item @if(request()->routeIs('home')) active @endif">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('Dashboard') }}</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item @if(request()->routeIs('users.index')) active @endif">
        <a class="nav-link" href="{{ route('users.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>{{ __('Users') }}</span></a>
    </li>

{{--    <li class="nav-item @if(request()->routeIs('about')) active @endif">--}}
{{--        <a class="nav-link" href="{{ route('about') }}">--}}
{{--            <i class="fas fa-fw fa-eye"></i>--}}
{{--            <span>{{ __('About') }}</span></a>--}}
{{--    </li>--}}

    <li class="nav-item @if(request()->routeIs('courses.index')) active @endif">
        <a class="nav-link" href="{{ route('courses.index') }}">
            <i class="fas fa-fw fa-eye"></i>
            <span>{{ __('Course List') }}</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Pages Collapse Menu -->

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline pt-4">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
