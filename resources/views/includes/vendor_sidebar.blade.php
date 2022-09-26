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
                                <a href="{{url('/dashboard')}}" class="{{ (request()->is('dashboard')) ? 'mm-active' : '' }}">
                                    <i class="metismenu-icon pe-7s-rocket"></i>
                                    Dashboard
                                </a>
                            </li>



                        @if(Auth::user()->role_id == 2)
                            <li>
                                <a href="{{url('/settings')}}" class="{{ (request()->is('settings')) ? 'mm-active' : '' }}">
                                    <i class="metismenu-icon fa fa-cogs"></i>
                                    Settings
                                </a>
                            </li>
                            <li>
                                <a href="{{url('/authorization')}}" class="{{ (request()->is('authorization')) ? 'mm-active' : '' }}">
                                    <i class="metismenu-icon fa fa-lock"></i>
                                    Authorization
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>

                    <div class="projects">
                        <div class="projects-search-result">
                            <div class="input-group searchInput">
                                <input placeholder="Type here for search..." type="text" class="form-control projects_autocomplete">
                                <span class="icon"><i class="fa fa-search"></i></span>
                            </div>
                            <div class="result_conatainer">
                                <ul class="vertical-nav-menu metismenu"></ul>
                            </div>
                        </div>

                        <div class="app-sidebar__inner">
                            <ul class="vertical-nav-menu metismenu">
                                <li class="mm-active">
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-diamond"></i> Projects
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>

                                    <ul class="mm-collapse mm-show">
                                        <li id="defaultCampaignList">
                                            @if(isset($allCampaigns) && !empty($allCampaigns))
                                            @foreach($allCampaigns as $key=>$value)
                                            <a href="{{url('new-dashboard/'.$value->id)}}">{{$value->domain_name}}</a>
                                            @endforeach
                                            @endif
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                      
                    </div>
                </div>
            </div>