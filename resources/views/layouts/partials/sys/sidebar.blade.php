<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 bg-slate-900 fixed-start tw__h-screen tw__z-[1039]" id="sidenav-main">
	<div class="sidenav-header">
		<i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
		<a class="navbar-brand d-flex align-items-center m-0" href="{{ env('APP_URL') }}">
			<span class="font-weight-bold text-lg">{{ env('APP_NAME') }}</span>
		</a>
	</div>

    <div class="collapse navbar-collapse px-4 w-auto xl:tw__h-[calc(100vh-80px)]" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @php
                $sidebarmenu = config('siaji.view.sys.sidebar');
            @endphp

            @if (!empty($sidebarmenu) && is_array($sidebarmenu))
                @foreach ($sidebarmenu as $menu)
                    @if ($menu['is_header'])
                        {{-- Header --}}
                        <li class="menu-header small text-uppercase">
                            <span class="menu-header-text">{{ $menu['name'] }}</span>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link tw__flex tw__items-center {{ isset($menu['is_disabled']) && $menu['is_disabled'] ? 'tw__opacity-50 tw__cursor-not-allowed' : '' }} {{ isset($menuState) && isset($menu['state']) && !empty($menu['state']) ? ($menuState === $menu['state'] ? 'active' : null) : null }}" href="{{ isset($menu['route']) && !empty($menu['route']) ? route($menu['route']) : 'javascript:void(0)' }}">
                                <div class="icon icon-shape icon-sm tw__flex tw__justify-center">
                                    @if (isset($menu['icon']) && !empty($menu['icon']))
                                        <i class="{{ $menu['icon'] }}"></i>
                                    @endif
                                    <title>{{ strtolower(str_replace(' ', '-', $menu['name'])) }}</title>
                                </div>
                                <span class="nav-link-text ms-1">{{ $menu['name'] }}</span>
                            </a>
                        </li>

                        @if (isset($menu['sub']) && is_array($menu['sub']) && count($menu['sub']) > 0)
                            @foreach ($menu['sub'] as $sub)
                                <li class="nav-item border-start my-0 tw__relative">
                                    <a class="nav-link tw__flex tw__items-center {{ isset($sub['is_disabled']) && $sub['is_disabled'] ? 'tw__opacity-50 tw__cursor-not-allowed' : '' }} {{ isset($submenuState) && isset($sub['state']) && !empty($sub['state']) ? ($submenuState === $sub['state'] ? 'active' : null) : null }}" href="{{ isset($sub['route']) && !empty($sub['route']) ? route($sub['route']) : 'javascript:void(0)' }}">
                                        <span class="nav-link-text ms-1">{{ $sub['name'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    @endif
                @endforeach
            @else
                <li class="nav-item">
                    <a class="nav-link tw__flex tw__items-center {{ isset($menuState) ? ($menuState === 'dashboard' ? 'active' : null) : null }}" href="{{ route('sys.index') }}">
                        <div class="icon icon-shape icon-sm tw__flex tw__justify-center">
                            <i class="fa-solid fa-house"></i>
                            <title>dashboard</title>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</aside>