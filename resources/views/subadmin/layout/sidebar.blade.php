<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                <li>
                    <a href="{{route('subadmin_dashboard')}}">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Dashboard</span>
                    </a>
                </li>

                @if(!empty($subadminPermissions['athlete_detail']) || !empty($subadminPermissions['athlete_edit']))
                <li>
                    <a href="{{route('subadmin_user')}}">
                        <i class="fas fa-user"></i>
                        <span key="t-dashboards">Athletes</span>
                    </a>
                </li>
                @endif

                @if(!empty($subadminPermissions['add_weight']) || !empty($subadminPermissions['can_apply']))
                <li>
                    <a href="{{route('subadmin_applyuser')}}">
                        <i class="fas fa-weight"></i>
                        <span key="t-dashboards">New Tournament Weight Fill</span>
                    </a>
                </li>
                @endif

                @if(!empty($subadminPermissions['can_create_tournament']) && empty($subadminPermissions['select_all_district']) && (string) ($admin->district ?? '') !== 'all')
                <li>
                    <a href="{{route('subadmin_tournament')}}">
                        <i class="fas fa-calendar-alt"></i>
                        <span key="t-dashboards">Create Tournament</span>
                    </a>
                </li>
                @endif

                @if(!empty($subadminPermissions['apply_tournament']))
                <li>
                    <a href="{{route('subadmin_apply_tournament')}}">
                        <i class="fas fa-trophy"></i>
                        <span key="t-dashboards">Apply Tournament</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('subadmin_district_apply_tournament')}}">
                        <i class="fas fa-trophy"></i>
                        <span key="t-dashboards">Applied District Tournament</span>
                    </a>
                </li>
                @endif

                @if(!empty($subadminPermissions['can_coach']))
                <li>
                    <a href="{{route('subadmin_coach')}}">
                        <i class="fas fa-user-tie"></i>
                        <span key="t-dashboards">Coach</span>
                    </a>
                </li>
                @endif

                @if(!empty($subadminPermissions['can_referee']))
                <li>
                    <a href="{{route('subadmin_referee')}}">
                        <i class="fas fa-whistle"></i>
                        <span key="t-dashboards">Referee</span>
                    </a>
                </li>
                @endif

                @if(!empty($subadminPermissions['can_draw_sheet']))
                <li>
                    <a href="{{route('subadmin_draw_sheet')}}">
                        <i class="fas fa-sitemap"></i>
                        <span key="t-dashboards">Draw Sheet</span>
                    </a>
                </li>
                @endif
                
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
