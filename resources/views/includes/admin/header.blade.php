<header class="header">
    <div class="elem-start">
        <div class="logo">
            <a href="{{url('/')}}">
                <div class="loader h-33"></div>
                <img src="{{URL::asset('public/front/img/logo.png')}}" alt="Logo">
            </a>
        </div>
        <button class="toggleMenuBtn"><span uk-icon="icon:  menu"></span></button>
        <?php if(isset($dfs_balance) && !empty($dfs_balance)){
            if($dfs_balance->balance > 50){ ?>
                <div class="alert alert-success">
                    <span>Balance left for Data For seo: <strong>{{'$'.$dfs_balance->balance}}</strong></span>
                </div>
            <?php }elseif($dfs_balance->balance <=50){ ?>
                <div class="alert alert-danger">
                    <span>Data For Seo balance less than $50, <strong>Please Recharge</strong> </span>
                    <span>Current Balance :{{'$'.$dfs_balance->balance}}</span>
                </div>
            <?php } } ?>
        </div>

        <div class="header-nav ajax-loader">
            <ul>
                <li id="admin-header-detail-li">
                    <button type="button">
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
                 <div uk-dropdown="mode: click">
                    <ul class="uk-nav uk-dropdown-nav">
                        <li @if(Request::is('/admin/profile-settings')) class="active" @endif><a href="{{url('/admin/profile-settings')}}"><span uk-icon="icon: user"></span> Profile</a></li>
                        <li @if(Request::is('/admin/logout')) class="active" @endif><a href="{{url('/admin/logout')}}"><span uk-icon="icon: sign-out"></span> Logout</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</header>