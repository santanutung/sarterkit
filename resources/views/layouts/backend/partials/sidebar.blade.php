<div class="app-sidebar sidebar-shadow bg-asteroid sidebar-text-light">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                        data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button"
                    class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <x-backend-sidebar />
                {{-- <li class="app-sidebar__heading">Dashboard</li>
                <li>
                    <a href="{{route('app.dashbord')}}" class="{{Request::is('app/dashbord')?'mm-active':''}}">
                        <i class="metismenu-icon pe-7s-rocket"></i>
                        Dashboard
                    </a>
                </li>
                 <li>
                    <a href="{{route('app.roles.index')}}" class="{{Request::is('app/roles*')?'mm-active':''}}">
                        <i class="metismenu-icon pe-7s-check"></i>
                        Role
                    </a>
                </li>
                <li>
                    <a href="{{route('app.users.index')}}" class="{{Request::is('app/users*')?'mm-active':''}}">
                        <i class="metismenu-icon pe-7s-users"></i>
                        User
                    </a>
                </li>
                 <li>
                    <a href="{{route('app.pages.index')}}" class="{{Request::is('app/pages*')?'mm-active':''}}">
                        <i class="metismenu-icon pe-7s-file"></i>
                        Page
                    </a>
                </li>
                 <li>
                    <a href="{{route('app.menus.index')}}" class="{{Request::is('app/menus*')?'mm-active':''}}">
                        <i class="metismenu-icon pe-7s-menu"></i>
                        Menu
                    </a>
                </li> --}}

            </ul>
        </div>
    </div>
</div>