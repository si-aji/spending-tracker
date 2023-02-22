@extends('layouts.base')

{{-- Styling --}}
@section('baseCSS')
    @yield('css_plugins')
@endsection
@section('baseCSSInline')
    @yield('css_inline')
@endsection

{{-- Content --}}
@section('body')
    @yield('content')
    
    @isset($slot)
        {{ $slot }}
    @endisset
@endsection

{{-- Script --}}
@section('baseJs')
    @yield('js_plugins')
@endsection
@section('baseJsInline')
    @yield('js_inline')
@endsection