@extends('layouts.vendor_internal_pages')
@section('content')
<div class="project-stats">

	<div uk-grid class="smallChartBox mt-0" id="dashboard-project-stats">
      <div class="uk-width-1-6@m uk-width-1-3@s uk-width-1-2">
        <span class="dashboard-project-stats-span"></span>
        <div class="white-box ex-small-chart-box">
         <div class="ex-small-chart-box-head">
            <h6 class=" dashboard-keywords-up ajax-loader">0</h6>
            <div class="loader h-33 "></div>
            <p><img src="{{URL::asset('public/vendor/internal-pages/images/keywords-up-img.png')}}"> Keywords Up</p>
            <button type="button" class="showMainChartBox"><span uk-icon="icon: arrow-up" uk-tooltip="title: Back; pos: top-left"></span></button>
         </div>
         <div class="ex-small-chart-box-foot">
            <p class="dashboard-top-all-since ajax-loader">since start</p>
         </div>
      </div>
   </div>
   <div class="uk-width-1-6@m uk-width-1-3@s uk-width-1-2">
      <div class="white-box ex-small-chart-box">
         <div class="ex-small-chart-box-head">
            <h6 class=" dashboard-top-three ajax-loader">0/0</h6>
            <div class="loader h-33 "></div>
            <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 3</p>
         </div>
         <div class="ex-small-chart-box-foot">
            <p class="dashboard-top-three-since ajax-loader"><strong>0</strong> since start</p>
         </div>
      </div>
   </div>
   <div class="uk-width-1-6@m uk-width-1-3@s uk-width-1-2">
      <div class="white-box ex-small-chart-box">
         <div class="ex-small-chart-box-head">
            <h6 class=" dashboard-top-ten ajax-loader">0/0</h6>
            <div class="loader h-33 "></div>
            <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 10</p>
         </div>
         <div class="ex-small-chart-box-foot">
            <p class="dashboard-top-ten-since ajax-loader"><strong>0</strong> since start</p>
         </div>
      </div>
   </div>
   <div class="uk-width-1-6@m uk-width-1-3@s uk-width-1-2">
      <div class="white-box ex-small-chart-box">
         <div class="ex-small-chart-box-head">
            <h6 class=" dashboard-top-twenty ajax-loader">0/0</h6>
            <div class="loader h-33 ajax-loader"></div>
            <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 20</p>
         </div>
         <div class="ex-small-chart-box-foot">
            <p class="dashboard-top-twenty-since ajax-loader"><i class="icon ion-arrow-up-a"></i><strong>0</strong> since start</p>
         </div>
      </div>
   </div>
   <div class="uk-width-1-6@m uk-width-1-3@s uk-width-1-2">
      <div class="white-box ex-small-chart-box">
         <div class="ex-small-chart-box-head">
            <h6 class=" dashboard-top-thirty ajax-loader">0/0</h6>
            <div class="loader h-33 "></div>
            <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 30</p>
         </div>
         <div class="ex-small-chart-box-foot">
            <p class="dashboard-top-thirty-since ajax-loader"><strong>0</strong> since start</p>
         </div>
      </div>
   </div>
   <div class="uk-width-1-6@m uk-width-1-3@s uk-width-1-2">
      <div class="white-box ex-small-chart-box">
         <div class="ex-small-chart-box-head">
            <h6 class=" dashboard-top-hundred ajax-loader">0/0</h6>
            <div class="loader h-33 "></div>
            <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 100</p>
         </div>
         <div class="ex-small-chart-box-foot">
            <p class="dashboard-top-hundred-since ajax-loader"><strong>0</strong> since start</p>
         </div>
      </div>
   </div>
</div>

<div uk-grid class="mb-40 mainChartBox mt-0 showMe">
 <div class="uk-width-1-3@l uk-width-1-3@s ">
   <div class="white-box small-chart-box style2">
     <div class="small-chart-box-head">
       <figure class="ajax-loader">
         <img src="{{URL::asset('public/vendor/internal-pages/images/total-keywords-icon.png')}}">
      </figure>
      <h6 class="ajax-loader"><big class="dashboard-keyword-detail">0<span>/0</span></big> Total Keywords<span
         uk-tooltip="title: Total number of keywords available in your package; pos: top-left"
         class="fa fa-info-circle"></span></h6>

         <button type="button" class="showOtherChart"><span uk-icon="icon: arrow-right" uk-tooltip="title: Live Tracking Summary; pos: top-left"></span></button>
      </div>
   </div>
</div>

<div class="uk-width-1-3@l uk-width-1-3@s ">
   <div class="white-box small-chart-box style2">
     <div class="small-chart-box-head">
       <figure class="ajax-loader">
         <img src="{{URL::asset('public/vendor/internal-pages/images/total-projects-icon.png')}}">
      </figure>
      <h6 class="ajax-loader">
         <big class="dashboard-project-detail">0<span>/0</span></big> Total Projects<span
         uk-tooltip="title: Total number of projects available in your package; pos: top-left"
         class="fa fa-info-circle"></span>
      </h6>
   </div>
</div>
</div>

<div class="uk-width-1-3@l uk-width-1-3@s ">
   <div class="white-box small-chart-box style2">
     <div class="small-chart-box-head">
       <figure class="ajax-loader">
         <img src="{{URL::asset('public/vendor/internal-pages/images/freelancer-icon.png')}}">
      </figure>
      <h6 class="ajax-loader"><big>Expired<span class="dashboard-project-name">Subscription</span></big> Subscription<span
         uk-tooltip="title: You can upgrade/downgrade your subscription from billing section in settings.; pos: top-left"
         class="fa fa-info-circle"></span></h6>
      </div>
   </div>
</div>

</div>
</div>
<div class="white-box pa-0 mb-40">
	<div class="white-box-body emailAlert">
		<h3><img src="{{URL::asset('public/vendor/internal-pages/images/alert-icon.png')}}"> ALERT</h3>
     
      <?php
      if(Auth::user()->parent_id <> null){
      $email = App\User::get_agency_owner_email(Auth::user()->parent_id);
      ?>
      <p>The subscription has expired, contact your agency owner </p>
      <a href="mailto: {{$email}}"><button class="btn blue-btn"> Contact</button></a>
      <?php }else {?>
      <p>Your subscription has expired, renew to continue </p>
      <button class="btn blue-btn"  id="renew-package"> Renew</button>
      <?php } ?>
   </div>
</div>
@endsection