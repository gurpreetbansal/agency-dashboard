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
    </div>

     <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">Menu</li>
                <li>
                    <a href="javascript:;" class="{{ (request()->is('/view/dashboard/*')) ? 'mm-active' : '' }}">
                        <i class="metismenu-icon pe-7s-rocket"></i>
                        Dashboard
                    </a>
                </li>
                @if(in_array("SEO", $dashboard))
                <li class="seoSidebar">
                    <a href="#SEO#RANKING" class=" sidebarLinks">
                        <i class="metismenu-icon pe-7s-graph3"></i>
                        Rankings
                    </a>
                </li>

                <li class="seoSidebar">
                    <a href="#SEO#TRAFFIC" class=" sidebarLinks">
                        <i class="metismenu-icon pe-7s-graph1"></i>
                        Traffic
                    </a>
                </li>

                 <li class="seoSidebar">
                    <a href="#SEO#BACKLINKS" class=" sidebarLinks">
                        <i class="metismenu-icon pe-7s-link"></i>
                        Backlinks
                    </a>
                </li>

                <li class="seoSidebar">
                    <a href="#SEO#LEADS" class=" sidebarLinks">
                        <i class="metismenu-icon pe-7s-users"></i>
                        Leads
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>