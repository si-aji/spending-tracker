<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 bg-slate-900 fixed-start tw__h-screen" id="sidenav-main">
	<div class="sidenav-header">
		<i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
		<a class="navbar-brand d-flex align-items-center m-0" href="{{ env('APP_URL') }}">
			<span class="font-weight-bold text-lg">{{ env('APP_NAME') }}</span>
		</a>
	</div>

    <div class="collapse navbar-collapse px-4 w-auto xl:tw__h-[calc(100vh-80px)]" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link tw__flex tw__items-center {{ isset($menuState) ? ($menuState === 'dashboard' ? 'active' : null) : null }}" href="#">
                    <div class="icon icon-shape icon-sm tw__flex tw__justify-center">
                        <i class="fa-solid fa-house"></i>
                        <title>dashboard</title>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Feature</span>
            </li>
            <li class="nav-item">
                <a class="nav-link tw__flex tw__items-center" href="#">
                    <div class="icon icon-shape icon-sm tw__flex tw__justify-center">
                        <i class="fa-solid fa-receipt"></i>
                        <title>record</title>
                    </div>
                    <span class="nav-link-text ms-1">Record</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link tw__flex tw__items-center" href="#">
                    <div class="icon icon-shape icon-sm tw__flex tw__justify-center">
                        <i class="fa-solid fa-clock"></i>
                        <title>planned-payment</title>
                    </div>
                    <span class="nav-link-text ms-1">Planned Payment</span>
                </a>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Master Data</span>
            </li>
            <li class="nav-item">
                <a class="nav-link tw__flex tw__items-center" href="#">
                    <div class="icon icon-shape icon-sm tw__flex tw__justify-center">
                        <i class="fa-solid fa-clipboard"></i>
                        <title>record-template</title>
                    </div>
                    <span class="nav-link-text ms-1">Record Template</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link tw__flex tw__items-center {{ isset($menuState) ? ($menuState === 'wallet' ? 'active' : null) : null }}" href="{{ route('sys.wallet.index') }}">
                    <div class="icon icon-shape icon-sm tw__flex tw__justify-center">
                        <i class="fa-solid fa-wallet"></i>
                        <title>wallet</title>
                    </div>
                    <span class="nav-link-text ms-1">Wallet</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link tw__flex tw__items-center" href="#">
                    <div class="icon icon-shape icon-sm tw__flex tw__justify-center">
                        <i class="fa-solid fa-layer-group"></i>
                        <title>wallet-group</title>
                    </div>
                    <span class="nav-link-text ms-1">Wallet Group</span>
                </a>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">MISCELLANEOUS</span>
            </li>
            <li class="nav-item">
                <a class="nav-link tw__flex tw__items-center" href="#">
                    <div class="icon icon-shape icon-sm tw__flex tw__justify-center">
                        <i class="fa-solid fa-circle-user"></i>
                        <title>account</title>
                    </div>
                    <span class="nav-link-text ms-1">Account</span>
                </a>
            </li>
            <li class="nav-item border-start my-0">
                <a class="nav-link tw__flex tw__items-center" href="#">
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li>
            <li class="nav-item border-start my-0">
                <a class="nav-link tw__flex tw__items-center" href="#">
                    <span class="nav-link-text ms-1">Category</span>
                </a>
            </li>
            <li class="nav-item border-start my-0">
                <a class="nav-link tw__flex tw__items-center" href="#">
                    <span class="nav-link-text ms-1">Tags</span>
                </a>
            </li>
            <li class="nav-item border-start my-0">
                <a class="nav-link tw__flex tw__items-center" href="#">
                    <span class="nav-link-text ms-1">Preference</span>
                </a>
            </li>
        </ul>
    </div>
</aside>