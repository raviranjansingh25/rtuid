@php
$admin= Auth::guard('admin')->user();

$pardata = App\Models\GroupPermission::where('subadmin_id',$admin->id)->where('permission',1)->get();
$permission = $pardata->pluck('controller')->toArray();
@endphp
<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                <li>
                    <a href="{{route('admin_dashboard')}}">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Dashboard</span>
                    </a>
                </li>

                @if (in_array('SubadminController', $permission) || $admin->id==1)
                <li>
                    <a href="{{route('subadmin')}}">
                        <i class="fas fa-user"></i>
                        <span key="t-dashboards">Sub Admin</span>
                    </a>
                </li>
                @endif

                @if (in_array('PermissionController', $permission) || $admin->id==1)
                <li>
                    <a href="{{route('viewpermission')}}">
                        <i class="bx bx-lock-open-alt"></i>
                        <span key="t-dashboards">Permissions</span>
                    </a>
                </li>
                @endif

                @if (in_array('UserController', $permission) || $admin->id==1)
                <li>
                    <a href="{{route('user')}}">
                        <i class="fas fa-user"></i>
                        <span key="t-dashboards">Athletes</span>
                    </a>
                </li>
                @endif

                @if (in_array('CoachController', $permission) || $admin->id==1)
                <li>
                    <a href="{{route('referee')}}">
                        <i class="fas fa-user"></i>
                        <span key="t-dashboards">Referee</span>
                    </a>
                </li>
                @endif
                
                @if (in_array('RefereeController', $permission) || $admin->id==1)
                <li>
                    <a href="{{route('coach')}}">
                        <i class="fas fa-user"></i>
                        <span key="t-dashboards">Coach</span>
                    </a>
                </li>
                @endif

                @if (in_array('ShoperController', $permission) || $admin->id==1)
                <li>
                    <a href="{{route('shoper')}}">
                        <i class="fas fa-user"></i>
                        <span key="t-dashboards">Shoper</span>
                    </a>
                </li>
                @endif
                
                @if (in_array('ApplyTournamentController', $permission) || $admin->id==1)
                <li>
                    <a href="{{route('apply_tournament')}}">
                        <i class="fas fa-user"></i>
                        <span key="t-dashboards">Apply Tournament</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('district_apply_tournament')}}">
                        <i class="fas fa-user"></i>
                        <span key="t-dashboards">District Apply Tournament</span>
                    </a>
                </li>
                @endif

                @if (in_array('DrawSheetController', $permission) || $admin->id==1)
                <li>
                    <a href="{{route('draw_sheet')}}">
                        <i class="fas fa-user"></i>
                        <span key="t-dashboards">Draw Sheet</span>
                    </a>
                </li>
                @endif
                
                @if (in_array('EventController', $permission) || in_array('EventCategoryController', $permission) || $admin->id==1)
                <li>

                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-calendar"></i>
                        <span key="t-dashboards">Tournament</span>
                    </a>

                    <ul class="sub-menu" aria-expanded="false">
                        @if (in_array('EventController', $permission) || $admin->id==1)
                        <li>
                            <a href="{{route('event')}}">
                                Events
                            </a>
                        </li>
                        @endif
                        @if (in_array('EventCategoryController', $permission) || $admin->id==1)
                        <li><a href="{{route('event-category')}}" key="t-tui-calendar">Event Category</a></li>
                        @endif

                        @if (in_array('TournamentController', $permission) || $admin->id==1)
                        <li><a href="{{route('tournament')}}" key="t-full-calendar">Tournament</a></li>
                        <li><a href="{{route('district_tournament')}}" key="t-full-calendar">District Tournament</a></li>
                        @endif

                        

                        

                    </ul>
                </li>
                @endif

                @if (in_array('FaqController', $permission) || in_array('PageController', $permission) || in_array('CategoryController', $permission) || in_array('BannerController', $permission) || in_array('LanguageController', $permission) || in_array('AriaIntrestController', $permission) || in_array('QualificationsController', $permission) || in_array('FeatureController', $permission) || in_array('TestimonialController', $permission) || $admin->id==1)
                <li>

                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-calendar"></i>
                        <span key="t-dashboards">CMS</span>
                    </a>

                    <ul class="sub-menu" aria-expanded="false">
                        @if (in_array('BannerController', $permission) || $admin->id==1)
                        <li>
                            <a href="{{route('banner')}}">
                                Banners
                            </a>
                        </li>
                        @endif
                        @if (in_array('PageController', $permission) || $admin->id==1)
                        <li><a href="{{route('page')}}" key="t-tui-calendar">Pages</a></li>
                        @endif

                        @if (in_array('FaqController', $permission) || $admin->id==1)
                        <li><a href="{{route('faq')}}" key="t-full-calendar">FAQ</a></li>
                        @endif

                        @if (in_array('TestimonialController', $permission) || $admin->id==1)
                        <li>
                            <a href="{{route('testimonial')}}">
                                Testimonial
                            </a>
                        </li>
                        @endif

                        @if (in_array('GalleryController', $permission) || $admin->id==1)
                        <li><a href="{{route('gallery')}}" key="t-full-calendar">Gallery</a></li>
                        @endif

                        @if (in_array('TestimonialController', $permission) || $admin->id==1)
                        <li>
                            <a href="{{route('category')}}">
                            Category
                            </a>
                        </li>
                        @endif
                        
                        @if (in_array('WeightCategoryController', $permission) || $admin->id==1)
                        <li>
                            <a href="{{route('weightcategory')}}">
                            Weight Category
                            </a>
                        </li>
                        @endif

                        

                    </ul>
                </li>
                @endif

               

                @if (in_array('EnqueryController', $permission) || $admin->id==1)
                <li>
                    <a href="{{route('contact_request')}}">
                        <i class="fa fa-question-circle"></i>
                        <span key="t-dashboards">Contact Inquiry</span>
                    </a>
                </li>
                @endif

                



            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->