@extends('other.layout.layout')
@section('content')
<!--begin::Content-->
<div class="content fs-6 d-flex flex-column flex-column-fluid" id="kt_content">
	<!--begin::Toolbar-->
	<div class="toolbar" id="kt_toolbar">
		<div class="container-fluid d-flex flex-stack flex-wrap flex-sm-nowrap">
			<!--begin::Info-->
			<div class="d-flex flex-column align-items-start justify-content-center flex-wrap me-2">
				<!--begin::Title-->
				<h1 class="text-dark fw-bold my-1 fs-2">{{$page_title}}</h1>
				<!--end::Title-->
				<!--begin::Breadcrumb-->
				<ul class="breadcrumb fw-semibold fs-base my-1">
					<li class="breadcrumb-item text-muted">
						<a href="{{route('admindashboard')}}" class="text-muted text-hover-primary">Home</a>
					</li>
					<li class="breadcrumb-item text-dark">{{$page_title}}</li>
				</ul>
				<!--end::Breadcrumb-->
			</div>
			<a class="btn btn-warning" href="{{ url('/other/all_group_reg_form/'.$event_id) }}">Download Form</a>
			<a class="btn btn-warning" href="{{ url('/other/all_group_card/'.$event_id) }}">Download Card</a>
			<!--end::Info-->
			@if(session()->has('success'))
			    <div class="alert alert-success delete_success success-alert">
			      <strong>Success! </strong>{{ session()->get('success') }}
			    </div>
			    @endif

			    @if($errors->any())
			        <div class="alert alert-danger danger-alert">
			          <strong>Error! </strong>{{$errors->first()}}
			        </div>  
			@endif
		</div>
	</div>
	<!--end::Toolbar-->

	<!--begin::Post-->
	<div class="post fs-6 d-flex flex-column-fluid" id="kt_post">
		<!--begin::Container-->
		<div class="container-xxl">
			<!--begin::Products-->

			<div class="card card-flush">
				<!--begin::Card header-->
				<div class="card-header align-items-center py-5 gap-2 gap-md-5">
					<!--begin::Card title-->
					<div class="card-title">
						<!--begin::Search-->
						<div class="d-flex align-items-center position-relative my-1">
							<!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
							<span class="svg-icon svg-icon-1 position-absolute ms-4">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
									<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
								</svg>
							</span>
							<!--end::Svg Icon-->
							<input type="text" data-kt-ecommerce-product-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search" />
						</div>
						<!--end::Search-->
					</div>
					
					<div class="card-toolbar  justify-content-end gap-5">
						<form method="post" action="{{$saveurl}}">
						@csrf
						<div class="row">
							<div class="col-md-3">
								<label class=" form-label">Tournament Name</label>
								
								<select class="form-select mb-2" data-control="select2" data-hide-search="true" name="tournamentname">

									<option value="">All</option>
									@foreach($eventserch as $eventserch)
									<option value="{{$eventserch->name}}">{{$eventserch->name}}</option>
									@endforeach
									
								</select>
							</div>
							<div class="col-md-3">
								<label class=" form-label">Category</label>
								
								<select class="form-select mb-2" data-control="select2" data-hide-search="true" name="category">

									<option value="">All</option>
									@foreach($category as $cat)
									<option value="{{$cat->id}}">{{$cat->name}}</option>
									@endforeach
									
								</select>
							</div>

							<div class="col-md-3">
								<label class=" form-label">Sub Category</label>
								
								<select class="form-select mb-2" data-control="select2" data-hide-search="true" name="subcategory">

									<option value="">All</option>
									@foreach($subcategory as $subcat)
									<option value="{{$subcat->id}}">{{$subcat->title}}</option>
									@endforeach
									
								</select>
							</div>

							<div class="col-md-3">
								<label class=" form-label">State</label>
								
								<select class="form-select mb-2" data-control="select2" data-hide-search="true" name="state">

									<option value="">All</option>
									@foreach($state as $state)
									<option value="{{$state->id}}">{{$state->name}}</option>
									@endforeach
									
								</select>
							</div>
							

							<div class="col-md-3">
								<label class="required form-label">Event Name</label>
								
								<select class="form-select mb-2" data-control="select2" data-hide-search="true" name="eventname">

									<option value="">All</option>
									@foreach($eventname as $name)
									<option value="{{$name->id}}">{{$name->name}}</option>
									@endforeach
								</select>
							</div>

							<div class="col-md-3">
								<label class="required form-label">Gender</label>
								
								<select class="form-select mb-2" data-control="select2" data-hide-search="true" name="gender">

									
									<option value="">All</option>
									<option value="1">Male</option>
									<option value="2">Female</option>
									<option value="3">Transgender</option>
									
								</select>
							</div>
							

							<div class="col-md-3">
								<label class="required form-label">Start Date</label>
								<input type="date" name="date_from" class="form-control mb-2"/>
							</div>

							<div class="col-md-3">
								<label class="required form-label">End Date</label>
								<input type="date" name="date_to" class="form-control mb-2"/>
							</div>
							
							<div class="col-md-3">
								<label class=" form-label"></label><br>
								<input type="submit" value="Search" class="btn btn-warning">
								<a href="{{$saveurl}}"><button class="btn btn-warning">Refresh</button></a>
							</div>
						</div>
					</form>
						
					</div>
					<!--end::Card toolbar-->

				</div>
				<!--end::Card header-->

				<!--begin::Card body-->
				<div class="card-body pt-0">
					<!--begin::Table-->
					<table class="table" id="kt_ecommerce_products_table">
						
							<tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
								<th class="w-10px pe-2">
									<div class="form-check form-check-sm form-check-custom form-check-solid me-3">
										<input class="form-check-input" type="hidden" data-kt-check="true" data-kt-check-target="#kt_ecommerce_products_table .form-check-input" value="1" />
									</div>
								</th>
								<th>State</th>
								<th>Reg Id</th>
								<th>Event</th>
								<th>First Name</th>
								<th>Last Name</th>
								<th>Father's Name</th>
								<th>Gender</th>
								<th>Date of Birth</th>
								<th>Category</th>
								<th>Status</th>
								<th>Actions</th>
							</tr>
							<!--end::Table row-->
						
							@foreach($get_data as $data)
							<tr>
								<td><input class="form-check-input" type="hidden" value="{{$data->id}}" /></td>
								<td>{{$data['get_user']['get_state']->name}}</td>
								<td>{{$data['get_user']->code}}</td>
								<td>{{$data['get_newevent']['get_event']->name}}</td>
								
								
								<td>{{$data['get_user']->name}}</td>
								<td>{{$data['get_user']->lastname}}</td>
								<td>{{$data['get_user']->fathername}}</td>
								<td>@if($data['get_user']->gender == 1)
									Male
									@elseif($data['get_user']->gender == 2)
									Female
									@else
									Transgender
									@endif
								</td>
								<td>{{$data['get_user']->dob}}</td>
								<td>{{$data['get_newevent']['get_subcat']->title}}</td>
								
								
								<!--begin::Status=-->
								<td>
									@if($data->request_status==1)
									<div class="badge badge-light-warning">Pending</div>
									@elseif($data->request_status==2)
									<div class="badge badge-light-success">Approved</div>
									@else
									<div class="badge badge-light-danger">Rejected</div>
									@endif
									
								</td>
								<!--end::Status=-->
								<!--begin::Action=-->
								<td class="text-end">
									<a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions 
									</a>
									<!--begin::Menu-->
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true" style="">
										<div class="menu-item px-3">
											<a href="{{url('/other/reg_form_single/'.$data->id)}}" class="menu-link px-3">Download Form</a>
										</div>

										<div class="menu-item px-3">
											<a href="{{url('/other/athletes-group_card/'.$data->id)}}" class="menu-link px-3">Download Card</a>
										</div>
										
										
										<!--end::Menu item-->
									</div>
									<!--end::Menu-->
								</td>

								<!--end::Action=-->
							</tr>
							@endforeach
							<!--end::Table row-->
							
							
							
						
						<!--end::Table body-->
					</table>
					<!--end::Table-->
				</div>
				<!--end::Card body-->
			</div>
			<!--end::Products-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Post-->
</div>
<!--end::Content-->

@endsection  