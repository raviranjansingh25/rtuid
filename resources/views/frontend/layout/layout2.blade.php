<!-- Start : Header -->
@include('frontend/layout/header2')

<!-- End : Header -->
@yield('content')

<!-- Start: Footer -->

@include('frontend/layout/footer')
@stack('js')
<!-- End: Footer -->