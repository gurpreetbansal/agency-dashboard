<header class="header">
  <div class="elem-start">
    <div class="logo">
     <a href="{{url('/dashboard')}}">
      <div class="loader h-33"></div>
      <img src="{{URL::asset('public/front/img/logo.svg')}}" alt="Logo">
    </a>
  </div>
  <button class="toggleMenuBtn"><span uk-icon="icon:  menu"></span></button>
  <?php if(isset($dfs_user_data) && !empty($dfs_user_data)){
    if($dfs_user_data->balance > 50){ ?>
      <div class="alert alert-warning">
        <span>Balance left for Data For seo: <strong>{{'$'.$dfs_user_data->balance}}</strong></span>
      </div>
    <?php }elseif($dfs_user_data->balance <=50){ ?>
      <div class="alert alert-danger">
        <span>Data For Seo balance less than $50, <strong>Please Recharge</strong> </span>
        <span>Current Balance :{{'$'.$dfs_user_data->balance}}</span>
      </div>
    <?php } } ?>

    <?php if(isset($pdfcrowd_data) && !empty($pdfcrowd_data)){
      if($pdfcrowd_data->balance > 500){ ?>
        <div class="alert alert-warning">
          <span>Credits left for PdfCrowd Api: <strong>{{$pdfcrowd_data->balance}}</strong></span>
        </div>
      <?php }elseif($pdfcrowd_data->balance <=500){ ?>
        <div class="alert alert-danger">
          <span>PdfCrowd balance less than 50 credits, <strong>Please Recharge</strong> </span>
          <span>Current Balance :{{$pdfcrowd_data->balance}}</span>
        </div>
      <?php } } ?>
    </div>

    <?php 
    $user_id = App\User::get_parent_user_id(Auth::user()->id);
    $check = App\User::check_subscription($user_id); 
    ?>

    @if($check == 'expired')
    <div class="header-nav ajax-loader">
      <ul>
        <li class="nav-li">
          <button type="button">
            <span class="caret" uk-icon="question" uk-tooltip="title: Support; pos: top"></span>
          </button>
          <div class="uk-dropdown uk-dropdown-close" uk-dropdown="mode: click">
            <ul class="uk-nav uk-dropdown-nav">
              <li><a onclick="openWidget();">Help</a></li>
              <li><a onclick="onsetWidget.show();">Updates</a></li>
            </ul>
          </div>
        </li>

        <li id="header-detail-li">
          <button type="button" class="" id="header-detail-button">
            <figure>
              @if(auth()->user()->profile_image != null)
              <img src="{{ auth()->user()->profile_image }}">
              @else
              <?php 
              $words = explode(' ', Auth::user()->name);
              $initial =  strtoupper(substr($words[0], 0, 1));
              ?>
              <figcaption>{{$initial}}</figcaption>
              @endif
            </figure>
            @if(Auth::user() != null)
            <span>{{Auth::user()->name}}</span>
            @endif
            <span class="caret" uk-icon="icon: triangle-down"></span>
          </button>
          <div class="uk-dropdown" uk-dropdown="mode: click">
            <ul class="uk-nav uk-dropdown-nav">
              <li @if(Request::is('profile-settings')) class="active" @endif><a href="{{url('/profile-settings')}}"><span uk-icon="icon: user"></span> Profile</a></li>
              <li @if(Request::is('logout')) class="active" @endif><a href="{{url('/logout')}}"><span uk-icon="icon: sign-out"></span> Logout</a></li>
            </ul>
          </div>
          
        </li>
      </ul>
    </div>
    @else

    <div class="header-nav ajax-loader">
      <ul>
        
        @if(Auth::user()->login_as == 1 && Auth::user()->super_user == 'admin')
        <a href="{{url('/back_to_admin')}}"> 
          <button type="button" class="btn blue-btn" id="BackToAdmin"><i class="fa fa-angle-left mr-2"></i> Back to Admin</button>
        </a>
        @endif

        @if(Auth::user()->role_id != 4)
        <li id="add-project-li">
          @if(Auth::user()->email_verified == 1)
          @if(!empty($user_package))
          @if($user_package->projects <= $project_count)
          <a href="javascript:;" class="btn blue-btn" id="reached_project_limit"><span uk-icon="icon: plus"></span> Add New Project</a>
          @else
          <a href="{{url('/add-new-project')}}" class="btn blue-btn"><span uk-icon="icon: plus"></span> Add New Project</a>
          @endif
          @else
          <a href="javascript:;" class="btn blue-btn"><span uk-icon="icon: plus"></span> Add New Project</a>
          @endif
          @endif
        </li>
        @endif

        <li class="nav-li">
          <button type="button">
            <span class="caret" uk-icon="question" uk-tooltip="title: Support; pos: top"></span>
          </button>
          <div class="uk-dropdown uk-dropdown-close" uk-dropdown="mode: click">
            <ul class="uk-nav uk-dropdown-nav">
              <li><a onclick="openWidget();">Help</a></li>
              <li><a onclick="onsetWidget.show();">Updates</a></li>
            </ul>
          </div>
        </li>
        
          <!-- <li class="alerts-top notificationAlert" data-campaign-id="{{@$campaign_id}}">
            <button type="button"  id="notification-badge-count">
              <span uk-icon="icon:bell" uk-tooltip="title: Alerts; pos: top"></span>   
              @if((isset($notifications) && $notifications <> null) && ($notifications['result_count'] > 0))
              <span class="uk-badge">{{$notifications['result_count']}}</span>
              @endif        
            </button>
            <div class="uk-dropdown" uk-dropdown="mode: click">
              <div class="noti-title"><h5>Notification <span class="uk-float-right"></span></h5></div>
              <div class="noti-con">
                <ul>
                  @if(!empty($notifications) && isset($notifications['result']) && count($notifications['result']) > 0)
                  @foreach($notifications['result']  as $key=>$value)
                  <li>
                    <a href="javascript:;">
                      <div class="circle <?php if($value->oneday_position > 0){ echo 'light-green-bg';}else{ echo 'light-red-bg';}?>">{{($value->oneday_position < 0)?str_replace('-','',$value->oneday_position):$value->oneday_position}} <i class="icon <?php if($value->oneday_position > 0){ echo 'ion-arrow-up-a';}else{ echo 'ion-arrow-down-a';}?>"></i></div> 
                      <div class="txt">{{$value->keyword}}
                        @if(!Request::is('campaign-detail/*') && !Request::is('project-settings/*') && !Request::is('extra-organic-keywords/*'))
                        <small class="uk-text-primary uk-text-italic">{{$value->host_url}}</small>
                        @endif
                        <small class="uk-text-muted">
                          <span>Pos. on {{date('M d',strtotime($value->updated_at))}} - <b> {{$value->position}} </b></span>
                          <span>Pos. on {{date('M d',strtotime('-1 day',strtotime($value->updated_at)))}}  - <b>{{($value->position === 0 || $value->position === null)?(100 + $value->oneday_position):($value->position + $value->oneday_position)}}</b></span>
                        </small>
                      </div>
                    </a>
                  </li>
                  @endforeach
                  @else
                  <li><a href="javascript:;"><div class="txt"><center>no alerts</center></div></a></li>
                  @endif
                </ul>
              </div> 
              <div class="noti-bottom" id="campaign-notification" data-campaign-id="{{@$campaign_id}}" data-host-url="<?php if(isset($campaign_id) && isset($notifications) && isset($notifications['result'][0])){ echo $notifications['result'][0]->host_url;}?>"><a href="javascript:;">View All</a></div>
            </div>
          </li> -->
          
          <li id="header-detail-li">
            <button type="button" class="" id="header-detail-button">
              <figure>
               @if(auth()->user()->profile_image != null)
               <img src="{{ auth()->user()->profile_image }}">
               @else
               <?php 
               $words = explode(' ', Auth::user()->name);
               $initial =  strtoupper(substr($words[0], 0, 1));
               ?>
               <figcaption>{{$initial}}</figcaption>
               @endif
             </figure>
             @if(Auth::user() != null)
             <span>{{Auth::user()->name}}</span>
             @endif
             <span class="caret" uk-icon="icon: triangle-down"></span>
           </button>
           <div class="uk-dropdown" uk-dropdown="mode: click">
            <ul class="uk-nav uk-dropdown-nav">
              <li @if(Request::is('profile-settings')) class="active" @endif><a href="{{url('/profile-settings')}}"><span uk-icon="icon: user"></span> Profile</a></li>
              @if(auth()->user()->role_id == 2)
              <li @if(Request::is('shared-access')) class="active" @endif><a href="{{url('/shared-access')}}"><span uk-icon="icon:  users"></span> Shared Access</a></li>
              @endif
              @if(auth()->user()->role_id != 4)
              <li @if(Request::is('archived-campaigns')) class="active" @endif><a href="{{url('/archived-campaigns')}}"><span uk-icon="icon:  album"></span> Archived Campaigns</a></li>
              <li @if(Request::is('schedule-report')) class="active" @endif><a href="{{url('/schedule-report')}}"><span uk-icon="icon:  calendar"></span> Schedule Reports</a></li>
              @endif
              <li @if(Request::is('logout')) class="active" @endif><a href="{{url('/logout')}}"><span uk-icon="icon: sign-out"></span> Logout</a></li>
            </ul>
          </div>
        </li>
        @endif
      </ul>
    </div>
  </header>