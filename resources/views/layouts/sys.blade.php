@extends('layouts.base')
@section('sbodyClass', 'g-sidenav-show  bg-gray-100')

@section('baseFonts')
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Noto+Sans:300,400,500,600,700,800|PT+Mono:300,400,500,600,700" rel="stylesheet" />

    <!-- Nucleo Icons -->
    <link href="{{ mix('assets/corporate-ui/css/nucleo-icons.css') }}" rel="stylesheet">
    <link href="{{ mix('assets/corporate-ui/css/nucleo-svg.css') }}" rel="stylesheet">

    <!-- Fontawesome -->
    <link href="{{ mix('assets/font/fontawesome/css/all.css') }}" rel="stylesheet">
@endsection

{{-- Styling --}}
@section('baseCSSPlugins')
    <!-- Corporate UI -->
    <link href="{{ mix('assets/corporate-ui/css/siaji.css') }}" rel="stylesheet" />
    <link href="{{ mix('assets/corporate-ui/css/corporate-ui-dashboard.css') }}" rel="stylesheet" />

    @yield('css_plugins')
@endsection

{{-- InlineStyling --}}
@section('baseCSSInline')
    @yield('css_inline')
@endsection

{{-- Content --}}
@section('body')
	@include('layouts.partials.sys.sidebar')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        @include('layouts.partials.sys.navbar')

        @yield('content')
    </main>
@endsection

{{-- Script --}}
@section('baseJsPlugins')
    <!-- Coreporate Ui -->
    <script src="{{ mix('assets/corporate-ui/js/core/popper.min.js') }}"></script>
    <script src="{{ mix('assets/corporate-ui/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ mix('assets/corporate-ui/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ mix('assets/corporate-ui/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ mix('assets/corporate-ui/js/plugins/chartjs.min.js') }}"></script>
    <script src="{{ mix('assets/corporate-ui/js/plugins/swiper-bundle.min.js') }}" type="text/javascript"></script>
	<!-- Control Center for Corporate UI Dashboard: parallax effects, scripts for the example pages etc -->
	<script src="{{ mix('assets/corporate-ui/js/corporate-ui-dashboard.min.js') }}"></script>

    @yield('js_plugins')
@endsection

{{-- Inline Script --}}
@section('baseJsInline')
    @yield('js_inline')

  <script>
		if (document.getElementsByClassName('mySwiper')) {
			var swiper = new Swiper(".mySwiper", {
				effect: "cards",
				grabCursor: true,
				initialSlide: 1,
				navigation: {
					nextEl: '.swiper-button-next',
					prevEl: '.swiper-button-prev',
				},
			});
		};

		var win = navigator.platform.indexOf('Win') > -1;
		if (win && document.querySelector('#sidenav-scrollbar')) {
			var options = {
				damping: '0.5'
			}
			Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
		}
  </script>
@endsection