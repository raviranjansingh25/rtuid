@php
$admin= Auth::guard('vender')->user();
@endphp
<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                <li>
                    <a href="{{route('referee_dashboard')}}">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('referee_idcard')}}">
                        <i class="fas fa-id-card"></i>
                        <span key="t-dashboards">My ID Card</span>
                    </a>
                </li>

                <!-- <li>
                    <a href="{{route('referee_user')}}">
                        <i class="fas fa-user"></i>
                        <span key="t-dashboards">Athletes</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('referee_applyuser')}}">
                        <i class="fas fa-user"></i>
                        <span key="t-dashboards">New Tournament Weight Fill</span>
                    </a>
                </li>

                
                <li>
                    <a href="{{route('referee_apply_tournament')}}">
                        <i class="fas fa-user"></i>
                        <span key="t-dashboards">Applied View Tournament</span>
                    </a>
                </li> -->
                
                
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->