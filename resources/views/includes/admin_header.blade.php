<div class="app-header header-shadow">
    <!--<div class="app-header header-shadow bg-malibu-beach header-text-light">-->
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
    <div class="app-header__content">
        <div class="app-header-left">
            <ul class="header-megamenu nav">

                <li class="btn-group nav-item">
                
                </li>
                <li class="dropdown nav-item">
                   
                </li>
            </ul>     
        </div>
        <div class="app-header-right">

            <div class="header-dots">
                <div class="dropdown">
                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right"></div>
                </div>
                <div class="dropdown"></div>
            </div>
            <div class="header-btn-lg pr-0">
                <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="btn-group">
                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                     @if(auth()->user()->profile_image)
                                    <img width="42" class="rounded-circle" src="{{ asset('public/storage/'.auth()->user()->profile_image) }}">
                                    @else
                                     <img width="42" class="rounded-circle" src="{{URL::asset('/public/assets/images/no-user-image.png')}}" alt="">
                                     @endif
                                    <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                </a>
                                <div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                    <div class="dropdown-menu-header">
                                        <div class="dropdown-menu-header-inner bg-info">
                                            <div class="menu-header-image opacity-2" style="background-image: url('../public/admin/images/city3.jpg');"></div>
                                            <div class="menu-header-content text-left">
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left mr-3">
                                                             @if(auth()->user()->profile_image)
                                    <img width="42" class="rounded-circle" src="{{ asset('public/storage/'.auth()->user()->profile_image) }}">
                                    @else
                                     <img width="42" class="rounded-circle" src="{{URL::asset('/public/assets/images/no-user-image.png')}}" alt="">
                                     @endif
                                                        </div>
                                                        <div class="widget-content-left">
                                                            <div class="widget-heading">{{ucwords(Auth::user()->name)}}</div>
                                                            <div class="widget-subheading opacity-8">A short profile description</div>
                                                        </div>
                                                        <div class="widget-content-right mr-2">
                                                            <a href="{{url('/admin/logout')}}"><button class="btn-pill btn-shadow btn-shine btn btn-focus">Logout</button></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="scroll-area-xs" style="height: 150px;">
                                        <div class="scrollbar-container ps">
                                            <ul class="nav flex-column">
                                                <li class="nav-item-header nav-item">My Account</li>
                                                <li class="nav-item">
                                                    <a href="{{url('/admin/profile')}}" class="nav-link">Profile
                                                        <div class="ml-auto badge badge-pill badge-info"></div>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{url('/admin/changepassword')}}" class="nav-link">Change Password</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{url('/admin/super-user')}}" class="nav-link">Super Admin</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="widget-content-left  ml-3 header-user-info">
                            <div class="widget-heading"> {{ucwords(Auth::user()->name)}}</div>
                            <div class="widget-subheading"></div>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  