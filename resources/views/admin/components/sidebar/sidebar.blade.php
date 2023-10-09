<div data-simplebar class="h-100">

    <!-- User details -->
    <div class="user-profile text-center mt-3">
        <div class="">
            <img src="{{ asset('assets/images/users/default-admin.png') }}" alt="" class="avatar-md rounded-circle">
        </div>
        <div class="mt-3">
            <h4 class="font-size-16 mb-1">{{ Auth::user()->name }}</h4>
            {{-- <i class="ri-record-circle-line align-middle font-size-14 text-success"></i> --}}
            <span class="text-muted">{{ Auth::user()->department }}</span>
        </div>
    </div>

    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu list-unstyled" id="side-menu">
            <li class="menu-title">Menu</li>

            <li>
                <a href="{{ route('administrator.dashboard') }}" class="waves-effect">
                    <i class="ri-dashboard-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('administrator.dashboard.incoming.request') }}" class="waves-effect">
                    <i class="fas fa-file"></i><span class="badge rounded-pill bg-success float-end"></span>
                    <span>Requests</span>
                </a>
            </li>
            <li>
                <a href="{{ route('administrator.dashboard.offices') }}" class="waves-effect">
                    <i class="fas fa-building"></i>
                    <span>Offices</span>
                </a>
            </li>
            {{-- <li>
                <a href="#" class="waves-effect">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
            </li> --}}

        </ul>
    </div>
    <!-- Sidebar -->
</div>