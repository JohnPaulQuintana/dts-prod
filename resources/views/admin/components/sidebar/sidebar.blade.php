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
            <li>
                <a href="{{ route('history') }}" class="waves-effect">
                    <i class="ri-history-line"></i>
                    <span>Logs</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault();this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt align-middle me-1 text-danger"></i> 
                        {{ __('Logout') }}
                    </a>
                </form>
            </li>

            <li class="menu-title">Date and Time</li>
            {{-- <hr class="text-dark"> --}}
            <li>
                <a href="{{ route('administrator.dashboard') }}" class="waves-effect">
                    <i class="far fa-calendar-alt text-dark"></i>
                    
                    <span class="current-date text-dark">12:00 PM</span>
                </a>
            </li>
            {{-- <hr class="text-dark"> --}}
        </ul>
        <!-- Add a container for the time display -->
        {{-- <div class="time-display bg-dark" style="text-align: center;padding: 10px; background-color: #f5f5f5;border-top: 1px solid #ddd;display:flex;flex-direction:column; position: absolute;width:100%;bottom:0;">
            <!-- Display the time here -->
            <span class="current-date text-white" style="font-size: 18px;color: #333;">12:00 PM</span>
            <!-- Display the time here -->
            <span class="current-time text-white" style="font-size: 18px;color: #333;">12:00 PM</span>
        </div> --}}
    </div>
    <!-- Sidebar -->
</div>