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

                            @if (in_array('BookingController', $permission) || $admin->id==1)
                            <li>
                                <a href="{{route('booking')}}">
                                    <i class="bx bx-file"></i>
                                    <span key="t-dashboards">Booking</span>
                                </a> 
                            </li>
                            @endif

                            @if (in_array('AmitiesController', $permission) || in_array('CollectionController', $permission)  || in_array('VenderController', $permission) || $admin->id==1)
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="fas fa-hotel"></i>
                                    <span key="t-dashboards">Hotels</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @if (in_array('VenderController', $permission) || $admin->id==1)
                                    <li><a href="{{route('vender')}}">Hotels List</a></li>
                                    @endif

                                    @if (in_array('VenderController', $permission) || $admin->id==1)
                                    <li><a href="{{route('account')}}">Hotels Account</a> 
                                    </li>
                                    @endif

                                    @if (in_array('AmitiesController', $permission) || $admin->id==1)
                                    <li><a href="{{route('amities')}}" key="t-full-calendar">Amenities</a></li>
                                    @endif

                                    @if (in_array('CollectionController', $permission) || $admin->id==1)
                                    <li><a href="{{route('collection')}}" key="t-full-calendar">Collection</a></li>  
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if (in_array('FaqController', $permission) || in_array('PageController', $permission) || in_array('CategoryController', $permission) || in_array('CityController', $permission) || $admin->id==1)
                            <li>
                                
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="bx bx-calendar"></i>
                                    <span key="t-dashboards">CMS</span>
                                </a>
                               
                                <ul class="sub-menu" aria-expanded="false">
                                    @if (in_array('PageController', $permission) || $admin->id==1)
                                    <li><a href="{{route('page')}}" key="t-tui-calendar">Pages</a></li>
                                    @endif

                                    @if (in_array('PageController', $permission) || $admin->id==1)
                                    <li><a href="{{route('admin_about_us')}}" key="t-tui-calendar">About Us</a></li>
                                    @endif
                                    
                                    @if (in_array('CategoryController', $permission) || $admin->id==1)
                                    <li><a href="{{route('category')}}" key="t-full-calendar">Category</a></li>
                                    @endif

                                    @if (in_array('FaqController', $permission) || $admin->id==1)
                                    <li><a href="{{route('faq')}}" key="t-full-calendar">FAQ</a></li>
                                    @endif

                                    @if (in_array('WhyChooseUsController', $permission) || $admin->id==1)
                                    <li><a href="{{route('why_choose_us')}}" key="t-full-calendar">Why Choose Us</a></li>
                                    @endif

                                    @if (in_array('TeamController', $permission) || $admin->id==1)
                                    <li><a href="{{route('team')}}" key="t-full-calendar">Team</a></li>
                                    @endif

                                    @if (in_array('CityController', $permission) || $admin->id==1)
                                    <li><a href="{{route('city')}}" key="t-full-calendar">Cities</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if (in_array('UserController', $permission) || $admin->id==1)
                            <li>
                                <a href="{{route('user')}}">
                                    <i class="fas fa-user"></i>
                                    <span key="t-dashboards">Customer</span>
                                </a> 
                            </li>
                            @endif


                            

                            @if (in_array('EnqueryController', $permission) || $admin->id==1)
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="bx bxs-user-detail"></i>
                                    <span key="t-dashboards">Enquiry and Request</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{route('contact_request')}}" key="t-tui-calendar">Contact Enquiry</a></li>
                                    <li><a href="{{route('associate')}}" key="t-tui-calendar">Associate Request</a></li> 
                                </ul>
                            </li>
                            @endif

                            

                            @if (in_array('PromoCodeController', $permission) || $admin->id==1)
                            <li>
                                <a href="{{route('promo_code')}}">
                                    <i class="bx bx-file"></i>
                                    <span key="t-dashboards">Promo Code</span>
                                </a> 
                            </li>
                            @endif
                            
                            @if (in_array('EnqueryController', $permission) || $admin->id==1)
                            <li>
                                <a href="{{route('review')}}">
                                    <i class="bx bx-file"></i>
                                    <span key="t-dashboards">Review</span>
                                </a> 
                            </li>
                            @endif
                        </ul>
                    </div>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->

            