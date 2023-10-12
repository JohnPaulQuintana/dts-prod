<div data-simplebar class="h-100">

    <!-- User details -->
    <div class="user-profile text-center mt-3">
        <div class="">
            <img src="{{ asset('assets/images/users/default-user.png') }}" alt="" class="avatar-md rounded-circle">
        </div>
        <div class="mt-3">
            <h4 class="font-size-16 mb-1">{{ Auth::user()->name }}</h4>
            {{-- <i class="ri-record-circle-line align-middle font-size-14 text-success"></i> --}}
            <span class="text-muted dept"></span>
        </div>
    </div>

    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu list-unstyled" id="side-menu">
            <li class="menu-title">Menu</li>

            <li>
                <a href="{{ route('departments.dashboard') }}" class="waves-effect">
                    <i class="ri-dashboard-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('departments.dashboard.incoming') }}" class="waves-effect">
                    <i class="fas fa-file"></i><span class="badge rounded-pill bg-danger float-end"></span>
                    <span>Requests</span>
                </a>
            </li>

            <li>
                <a href="{{ route('history.department') }}" class="waves-effect">
                    <i class="ri-history-line"></i>
                    <span>Logs</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="dropdown-item text-danger" :href="route('logout')" onclick="event.preventDefault();this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt align-middle me-1 text-danger"></i> 
                        {{ __('Logout') }}
                    </a>
                </form>
                {{-- <a class="waves-effect text-danger">
                    <i class="fas fa-sign-out-alt text-danger"></i>
                    <span>Logout</span>
                </a> --}}
            </li>

            <li class="menu-title">Date and Time</li>
            {{-- <hr class="text-dark"> --}}
            <li>
                <a href="{{ route('departments.dashboard') }}" class="waves-effect">
                    <i class="far fa-calendar-alt text-dark"></i>
                    
                    <span class="current-date text-dark">12:00 PM</span>
                </a>
            </li>
            
            {{-- <li>
                <a href="{{ route('departments.dashboard.department') }}" class="waves-effect">
                    <i class="fas fa-building waves-effect waves-light"></i>
                    <span>Offices</span>
                </a>
            </li> --}}
            {{-- <li>
                <a id="send-documents-btn" class="waves-effect">
                    <i class="ri-dashboard-line waves-effect waves-light"></i>
                    <span>Send Document</span>
                </a>
            </li>
            <li>
                <a href="{{ route('document.progress') }}" class="waves-effect">
                    <i class="ri-dashboard-line waves-effect waves-light"></i>
                    <span>Document Progress</span>
                </a>
            </li> --}}

            

        </ul>
    </div>
    <!-- Sidebar -->
</div>