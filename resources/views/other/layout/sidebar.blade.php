@php
$admin= Auth::guard('shoper')->user();
@endphp
<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                <li>
                    <a href="{{route('shoper_dashboard')}}">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('shoperapply_tournament')}}">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Apply Tourament</span>
                    </a>
                </li>
                
                
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->