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
        </div>    <div class="app-header__content">
            <div class="app-header-left">
                <?php
                if(Auth::user()->role_id == 2){
                    $email_verified = Auth::user()->email_verified; 
                    if ($email_verified == 1) {
                        ?>
                        <div id="addNewProject"><button type="button" class="mb-2 mr-2 btn btn-gradient-info" data-toggle="modal" data-target="#addNewProject-modal"><i class="fa fa-plus-circle"></i> Add New Project</button></div>
                    <?php } } ?>
                </div>
                <div class="app-header-right">

                    <div class="header-dots"> 
                        @if(Auth::user()->login_as == 1)
                        <a href="{{url('/back_to_admin')}}"> 
                            <button type="button" class="mb-2 mr-2 btn btn-gradient-info" id="BackToAdmin"><i class="fa fa-angle-left mr-2"></i> Back to Admin</button>
                        </a>
                        @endif

                        <div class="dropdown">
                            <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="p-0 mr-2 btn btn-link">
                                <span class="icon-wrapper icon-wrapper-alt rounded-circle">
                                    <span class="icon-wrapper-bg bg-danger"></span>
                                    <i class="icon text-danger icon-anim-pulse ion-android-notifications"></i>
                                    <span class="badge badge-dot badge-dot-sm badge-danger">Notifications</span>
                                </span>
                            </button>
                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right">
                                <div class="dropdown-menu-header mb-0">
                                    <div class="dropdown-menu-header-inner bg-deep-blue">
                                        <div class="menu-header-image opacity-1" style="background-image: url('public/vendor/images/city3.jpg');"></div>
                                        <div class="menu-header-content text-dark">
                                            <h5 class="menu-header-title">Announcements</h5>
                                            <!-- <h6 class="menu-header-subtitle">You have <b>21</b> unread messages</h6> -->
                                        </div>
                                    </div>
                                </div>
                               <!--  <ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
                                    <li class="nav-item">
                                        <a role="tab" class="nav-link active" data-toggle="tab" href="#tab-messages-header">
                                            <span>Messages</span>
                                        </a>
                                    </li>
                                   
                                </ul> -->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab-messages-header" role="tabpanel">
                                        <div class="scroll-area-sm">
                                            <div class="scrollbar-container ps">
                                                <div class="p-3">
                                                    <div class="notifications-box">
                                                        <div class="vertical-time-simple vertical-without-time vertical-timeline vertical-timeline--one-column">

                                                          <?php  
                                                            if(isset($announcements) && !empty($announcements) && count($announcements) > 0){
                                                            foreach($announcements as $announcement){ ?>
                                                            <div class="vertical-timeline-item dot-{{$announcement->announcement_type}} vertical-timeline-element">
                                                                <div>
                                                                    <span class="vertical-timeline-element-icon bounce-in"></span>
                                                                    <div class="vertical-timeline-element-content bounce-in">
                                                                        <h4 class="timeline-title">{{$announcement->announcement}}</h4>
                                                                        <span class="vertical-timeline-element-date"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                           <?php } } else{ ?>
                                                            <div class="vertical-timeline-item dot-dark vertical-timeline-element">
                                                                <div>
                                                                    <span class="vertical-timeline-element-icon bounce-in"></span>
                                                                    <div class="vertical-timeline-element-content bounce-in">
                                                                        <h4 class="timeline-title">No Announcements yet!</h4>
                                                                        <span class="vertical-timeline-element-date"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
                                            </div>
                                        </div>


                                    </div>
                                    <ul class="nav flex-column">
                                        <!-- <li class="nav-item-divider nav-item"></li> -->

                                    </ul>
                                </div>
                            </div>


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
                                                <i class="fa fa-angle-down ml-2 opacity-5"></i>
                                            </a>
                                            <div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                                <div class="dropdown-menu-header">
                                                    <div class="dropdown-menu-header-inner bg-info">
                                                        <div class="menu-header-image opacity-2" style="background-image: url('/public/vendor/images/city3.jpg');"></div>
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
                                                                        <div class="widget-heading">{{Auth::user()->name}}</div>
                                                                        <div class="widget-subheading opacity-8">A short profile description</div>
                                                                    </div>
                                                                    <div class="widget-content-right mr-2">
                                                                        <a href="{{url('/logout')}}"><button class="btn-pill btn-shadow btn-shine btn btn-focus">Logout</button></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="scroll-area-xs" style="height: auto;">
                                                    <div class="scrollbar-container ps">
                                                        <ul class="nav flex-column">
                                                            <li class="nav-item-header nav-item">My Account</li>
                                                            <li class="nav-item">
                                                                <a href="{{url('/profile')}}" class="nav-link">Profile
                                                                    <div class="ml-auto badge badge-pill badge-info"></div>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a href="{{url('changepassword')}}" class="nav-link">Change Password</a>
                                                            </li>
                                                            <li class="nav-item-header nav-item">Settings</li>
                                                             <li class="nav-item">
                                                                <a href="{{url('account-settings')}}" class="nav-link">Account Settings</a>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget-content-left  ml-3 header-user-info">
                                        <div class="widget-heading"> {{Auth::user()->name}}</div>
                                        <div class="widget-subheading"> </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>  