<div class="header">
    <div class="header-left active">
        <a href="{{ route('home') }}" class="logo logo-normal">
            <img src="{{ asset('assets/backend/img/logo.png') }}" alt>
        </a>
        <a href="{{ route('home') }}" class="logo logo-white">
            <img src="{{ asset('assets/backend/img/logo-white.png') }}" alt>
        </a>
        <a href="{{ route('home') }}" class="logo-small">
            <img src="{{ asset('assets/backend/img/logo-small.png') }}" alt>
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
            <i data-feather="chevrons-left" class="feather-16"></i>
        </a>
    </div>

    <a id="mobile_btn" class="mobile_btn" href="#sidebar">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <ul class="nav user-menu">
        <li class="nav-item nav-searchinputs">
        </li>

        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-info">
                    <span class="user-letter">
                        <img src="{{ asset('assets/backend/img/profiles/avator1.png') }}" alt class="img-fluid">
                    </span>
                    <span class="user-detail">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        @hasrole('Super-Admin')
                            <span class="user-role">Super Admin</span>
                        @else
                            <span class="user-role">Admin</span>
                        @endhasrole
                    </span>
                </span>
            </a>
            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    <div class="profileset">
                        <span class="user-img"><img src="{{ asset('assets/backend/img/profiles/avator1.png') }}" alt>
                            <span class="status online"></span></span>
                        <div class="profilesets">
                            <h6>{{ Auth::user()->name }}</h6>
                            @hasrole('Super-Admin')
                                <h5>Super Admin</h5>
                            @else
                                <h5>Admin</h5>
                            @endhasrole
                        </div>
                    </div>
                    <hr class="m-0">
                    <a class="dropdown-item" href="{{ route('profile') }}"> <i class="me-2" data-feather="user"></i>
                        Upadte Profile</a>
                    <a class="dropdown-item" href="{{ route('settings') }}"><i class="me-2"
                            data-feather="settings"></i>Change password</a>
                    <hr class="m-0">
                    <a class="dropdown-item logout pb-0" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><img
                            src="{{ asset('assets/backend/img/icons/log-out.svg') }}" class="me-2"
                            alt="img">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </li>
    </ul>

    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{ route('profile') }}">Upadte Profile</a>
            <a class="dropdown-item" href="{{ route('settings') }}">Change password</a>
            <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>

</div>
