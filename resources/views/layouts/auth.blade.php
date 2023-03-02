@extends('layouts.base')

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
    <main class="main-content  mt-0">
        @yield('content')

        @yield('content_modal')
    </main>
@endsection

{{-- Script --}}
@section('baseJsPlugins')
    <!-- Coreporate Ui -->
    <script src="{{ mix('assets/corporate-ui/js/core/popper.min.js') }}"></script>
    <script src="{{ mix('assets/plugins/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ mix('assets/corporate-ui/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ mix('assets/corporate-ui/js/plugins/smooth-scrollbar.min.js') }}"></script>
	<!-- Control Center for Corporate UI Dashboard: parallax effects, scripts for the example pages etc -->
	<script src="{{ mix('assets/corporate-ui/js/corporate-ui-dashboard.min.js') }}"></script>

    @yield('js_plugins')
@endsection

{{-- Inline Script --}}
@section('baseJsInline')
    @yield('js_inline')
@endsection