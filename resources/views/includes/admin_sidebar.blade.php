<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
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
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading"></li>
                <li>
                    <a href="{{url('/admin/dashboard')}}" class="{{ (request()->is('admin/dashboard')) ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-rocket"></i>
                        Dashboard
                    </a>
                </li>

                <li>
                    <a href="#"  class="{{ (request()->is('admin/regional-database/create') || request()->is('admin/regional-database')) ? 'mm-active' : '' }}">
                        <i class="metismenu-icon fa fa-database"></i>
                        Regional Database
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="{{ (request()->is('admin/regional-database/create') || request()->is('admin/regional-database')) ? 'mm-collapse mm-show' : '' }}">
                        <li>
                            <a href="{{url('/admin/regional-database/create')}}" class="{{ (request()->is('admin/regional-database/create')) ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Add
                            </a>
                        </li>
                        <li>
                            <a href="{{url('/admin/regional-database')}}" class="{{ (request()->is('admin/regional-database')) ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Manage
                            </a>
                        </li>
                    </ul>
                </li>
                
             <!--    <li>
                    <a href="{{url('/admin/clients')}}" class="{{ (request()->is('admin/clients')) ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-users"></i>
                        Clients
                    </a>
                </li>-->
                <li>
                    <a href="{{url('/admin/transactions')}}" class="{{ (request()->is('admin/transactions')) ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-wallet"></i>
                        Transactions
                    </a>
                </li> 

                <li>
                    <a href="#"  class="{{ (request()->is('admin/packages/create') || request()->is('admin/packages')) ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-diamond"></i>
                        Packages
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="{{ (request()->is('admin/packages/create') || request()->is('admin/packages')) ? 'mm-collapse mm-show' : '' }}">
                        <li>
                            <a href="{{url('/admin/packages/create')}}" class="{{ (request()->is('admin/packages/create')) ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Add
                            </a>
                        </li>
                        <li>
                            <a href="{{url('/admin/packages')}}" class="{{ (request()->is('admin/packages')) ? 'mm-active' : '' }}">
                                <i class="metismenu-icon"></i>
                                Manage
                            </a>
                        </li>
                    </ul>
                </li>

                <!--                 <li>
                                    <a href="#"  class="{{ (request()->is('admin/support')) ? 'mm-active' : '' }}">
                                        <i class="metismenu-icon pe-7s-headphones"></i>
                                        Support
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul class="{{ (request()->is('admin/support')) ? 'mm-collapse mm-show' : '' }}">
                                        <li>
                                            <a href="#" class="{{ (request()->is('admin/support')) ? 'mm-active' : '' }}">
                                                <i class="metismenu-icon"></i>
                                                Manage
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                -->
                
               
            </ul>
        </div>
    </div>
</div>  