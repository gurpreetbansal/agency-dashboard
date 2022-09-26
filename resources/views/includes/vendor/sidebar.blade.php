 <aside class="sidebar">
    <div class="logo">
        <a href="{{url('/dashboard')}}">
            <img src="{{URL::asset('public/front/img/logo.svg')}}" alt="Logo">
        </a>
    </div>

    <nav>

        <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
            <li class="{{ (request()->is('/dashboard')) ? 'active' : '' }}"><a href="{{url('/dashboard')}}">

                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/dashboard-icon.png')}}" alt="Dashboard"></figure> Dashboard
            </a></li>
        </ul>

        <div class="uk-navbar-item">
            <form action="javascript:void(0)">
                <input type="text" placeholder="Search..." class="projects_autocomplete">
                <div class="refresh-search-icon" id="refresh-sidebar-search">
                    <span uk-icon="refresh"></span>
                </div>
                <a href="javascript:;" class="sidebar-search-clear"><span class="clear-input sidebarClear" uk-icon="icon: close;"></span></a>
                <button type="submit"><span uk-icon="icon:search"></span></button>
            </form>
        </div>

        <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
            <li class="uk-parent uk-open">
                <a href="#">
                    <figure><img src="{{URL::asset('public/vendor/internal-pages/images/project-icon.png')}}" alt="Projects"></figure> Projects
                </a>

                <ul class="uk-nav-sub" id="defaultCampaignList">
                    @for($i=1;$i<20;$i++)
                    <li class=""><a href="javascript:;" ><div class="ajax-loader h-33 loaderNavSub"></div></a></li>
                    @endfor
                </ul>
            </li>
            <li>
            <a href="{{url('/logout')}}">
                    <figure><img src="{{URL::asset('public/vendor/internal-pages/images/logout-icon.png')}}" alt="Logout"></figure> Logout
                </a>
            </li>
        </ul>
    </nav>
</aside>