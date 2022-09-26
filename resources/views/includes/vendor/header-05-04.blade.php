<header class="header">
    <div class="elem-start">
        <div class="logo">
            <a href="#">
                <div class="loader h-33"></div>
                <img src="{{URL::asset('public/front/img/logo.png')}}" alt="Logo">
            </a>
        </div>
        <button class="toggleMenuBtn"><span uk-icon="icon:  menu"></span></button>
        <?php if(isset($dfs_user_data) && !empty($dfs_user_data)){
            if($dfs_user_data->balance > 50){ ?>
                <div class="alert alert-success">
                    <span>Balance left for Data For seo: <strong>{{'$'.$dfs_user_data->balance}}</strong></span>
                </div>
            <?php }elseif($dfs_user_data->balance <=50){ ?>
                <div class="alert alert-danger">
                    <span>Data For Seo balance less than $50, <strong>Please Recharge</strong> </span>
                    <span>Current Balance :{{'$'.$dfs_user_data->balance}}</span>
                </div>
            <?php } } ?>

            
            @if(\Request::Segment(1) =='campaign-detail')
                @if(isset($campaign_errors) && !empty($campaign_errors))
                @foreach($campaign_errors as $error)
                @if($error->reason !='backendError')
                 <div class="alert alert-success">
                    <span><strong><?php if($error->module == 1){echo 'Google Analytics: ';}elseif($error->module == 2){echo 'Google Search Console: ';}?></strong></span>
                    <span>{{$error->message}}</span>
                </div>
                 @endif
                @endforeach
                @endif
            @endif
        </div>

        <div class="header-nav ajax-loader">
            <ul>
                <li>
                    @if(Auth::user()->email_verified == 1)
                        @if($user_package->projects <= $project_count)
                        <a href="javascript:;" class="btn blue-btn" id="reached_project_limit"><span uk-icon="icon: plus"></span> Add New Project</a>
                        @else
                    <a href="{{url('/add-new-project')}}" class="btn blue-btn"><span uk-icon="icon: plus"></span> Add New Project</a>
                    @endif
                    @else
                    <a href="javascript:;" class="btn blue-btn"><span uk-icon="icon: plus"></span> Add New Project</a>
                    @endif
                </li>

                <li>
                    <button type="button" class="">
                        <span uk-icon="icon:bell"></span>
                        <span class="uk-badge">1</span>
                    </button>
                   <!--  <div uk-dropdown="mode: click" class="notification-dropdown">
                        <div class="date">Wednesday, 06 January 2021</div>
                        <div class="notification-timeline">
                            <article>
                                <div class="account-timeline-time">
                                    05:27 AM
                                </div>
                                <div class="account-timeline-badge yellow">
                                    <span></span>
                                </div>
                                <div class="account-timeline-info">
                                    New keywords have not started ranking today
                                </div>
                            </article>
                            <article>
                                <div class="account-timeline-time">
                                    04:09 AM
                                </div>
                                <div class="account-timeline-badge green">
                                    <span></span>
                                </div>
                                <div class="account-timeline-info">
                                    <b class="green">0.00%</b> increase in traffic since yesterday
                                </div>
                            </article>
                            <article>
                                <div class="account-timeline-time">
                                    12:01 AM
                                </div>
                                <div class="account-timeline-badge red">
                                    <span></span>
                                </div>
                                <div class="account-timeline-info">
                                    hire seo expert india moved <b class="green">1</b> position(s) up
                                </div>
                            </article>
                            <article>
                                <div class="account-timeline-time">
                                    02:37 pM
                                </div>
                                <div class="account-timeline-badge purple">
                                    <span></span>
                                </div>
                                <div class="account-timeline-info">
                                    seo consultant in india moved <b class="red">6</b> position(s) down
                                </div>
                            </article>
                        </div>
                        <div class="text-center">
                            <a href="#" class="btn blue-btn btn-sm">Load More</a>
                        </div>
                    </div> -->

        </li>
        <li>
            <button type="button" class="">
                <figure>
                   @if(auth()->user()->profile_image != null)
                   <img src="{{ auth()->user()->profile_image }}">
                   @else
                   <!-- <img src="{{URL::asset('/public/assets/images/no-user-image.png')}}"> -->
                   <?php 
                       $words = explode(' ', Auth::user()->name);
                       $initial =  strtoupper(substr($words[0], 0, 1));
                   ?>
                   <figcaption>{{$initial}}</figcaption>
                   @endif
               </figure>
               @if(Auth::user() != null)
               {{Auth::user()->name}}
               @endif
               <span class="caret" uk-icon="icon: triangle-down"></span>
           </button>
           <div uk-dropdown="mode: click">
            <ul class="uk-nav uk-dropdown-nav">
                <li class="active"><a href="{{url('/profile-settings')}}"><span uk-icon="icon: user"></span> Profile</a></li>
                    <!-- <li><a href="{{url('changepassword')}}"><span uk-icon="icon: lock"></span> Change Password</a></li>
                        <li><a href="{{url('account-settings')}}"><span uk-icon="icon: cog"></span> Account Settings</a></li> -->
                        <!-- <li><a href="#"><span uk-icon="icon: cog"></span> Account Settings</a></li> -->
                        <li><a href="{{url('/archived-campaigns')}}"><span uk-icon="icon:  album"></span> Archived Campaigns</a></li>
                        <li><a href="{{url('/logout')}}"><span uk-icon="icon: sign-out"></span> Logout</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</header>