@extends('layouts.base', [
    'sbodyClass' => 'g-sidenav-show bg-gray-100 tw__relative'
])

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
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
    <link href="{{ mix('assets/corporate-ui/css/corporate-ui-dashboard.css') }}" rel="stylesheet" />
    <link href="{{ mix('assets/corporate-ui/css/siaji.css') }}" rel="stylesheet" />

    @yield('css_plugins')
@endsection

{{-- InlineStyling --}}
@section('baseCSSInline')
    @yield('css_inline')
@endsection

{{-- Content --}}
@section('body')
	@include('layouts.partials.sys.sidebar')

    <main class="main-content position-relative h-100 tw__min-h-screen border-radius-lg tw__pb-40 lg:tw__pb-24">
        @include('layouts.partials.sys.navbar')

        <div class="container py-4 ">
            @yield('content')
        </div>

        @yield('content_modal')
        @livewire('component.record.record-modal', ['user' => \Auth::user()], key(\Auth::user()->id))

        @include('layouts.partials.sys.footer')
    </main>

    <!-- Floating Button -->
    <div class=" tw__fixed tw__right-6 tw__bottom-2">
        <button class=" btn btn-sm btn-primary" x-on:click="window.livewire.emitTo('component.record.record-modal', 'showModal')">
            <span>
                <i class="fa-solid fa-plus"></i>
                <span>Create new record</span>
            </span>
        </button>
    </div>

    <!-- Logout Form -->
    @if (\Auth::check())
        @livewire('auth.logout', ['user' => \Auth::user()], key(generateRandomString()))
    @endif
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
    <script src="{{ mix('assets/corporate-ui/js/siaji.js') }}"></script>

    @include('layouts.plugins.imask.js')
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