@extends('layouts.vendor_internal_pages')
@section('content')
<input type="hidden" class="campaignID" value="{{$campaign_id}}">
<input type="hidden" class="user_id" value="{{$user_id}}">
<div class="project-detail-body">
	<!-- Project Tabs Nav -->
	<div class="tabs">
		<!-- <div class="loader h-48 half"></div> -->

		@php
		@$dashUsed = array_intersect($types,array_keys($all_dashboards));
		@$dashDiff = array_diff(array_keys($all_dashboards),$types);
		@$arrCombine = array_merge($dashUsed,$dashDiff);
		@endphp

		<ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: #projectNavContainer; swiping: false">
			@foreach($arrCombine as $key=> $dashboards)
			@if(array_key_exists($dashboards,$all_dashboards))
			<li class="selectedDashboard <?php if(in_array($dashboards, $dashDiff)){ echo 'inactive'; }?>"><a href="#{{$all_dashboards[$dashboards]}}">{{$all_dashboards[$dashboards]}}</a></li>
			@endif
			@endforeach
		</ul>

		<!-- <div class="loader h-48 half-s"></div> -->
		<div class="download-options">
			<div class="btn-group">
				@if(Auth::user()->role_id !=4)
				<span class="addGmbRefresh"></span>
				<span class="addActivityImg"></span>
				<a href="javascript:;" class="btn icon-btn color-orange" id="notesPopup" uk-tooltip="title: Project Notes; pos: top-center"><img src="{{URL::asset('public/vendor/internal-pages/images/add-notes.png')}}"></a>
				@endif

				@if(isset($audit) && $audit == 'run')
				<a href="{{url('/audit/detail/'.$campaign_id)}}" target="_blank" class="btn icon-btn color-blue site-audit-icon" uk-tooltip="title: Run Site Audit; pos: top-center">
					<img src="{{URL::asset('public/vendor/internal-pages/images/run-site-audit.png')}}">
				</a>
				@elseif(isset($audit) && $audit == 'check')
				<a href="{{url('/audit/detail/'.$campaign_id)}}" target="_blank" class="btn icon-btn color-green site-audit-icon" uk-tooltip="title: Check Site Audit; pos: top-center">
					<img src="{{URL::asset('public/vendor/internal-pages/images/check-site-audit.png')}}">
				</a>
				@endif
				
				<a href="{{ url('/download/pdf/'.@$keyenc.'/seo') }}" target="_blank" data-type="seo" class="btn icon-btn color-red generate-pdf" uk-tooltip="title: Generate PDF File; pos: top-center"><img src="{{URL::asset('public/vendor/internal-pages/images/pdf-icon.png')}}"></a>
				@if(Auth::user()->role_id !=4)
					<a href="{{url('/project-settings/'.$campaign_id)}}" class="btn icon-btn color-blue" uk-tooltip="title:Project Setting; pos: top-center"><img src="{{URL::asset('public/vendor/internal-pages/images/setting-icon.png')}}"></a>
				@endif
				<a href="javascript:;" id="ShareKey" data-id="{{$campaign_id}}" data-share-key="{{$data->share_key}}" class="btn icon-btn color-purple" uk-tooltip="title: Generate Shared Key; pos: top-center"><img src="{{URL::asset('public/vendor/internal-pages/images/share-key-icon.png')}}"></a>
					
			</div>
		</div>
	</div>
	<!-- Project Tabs Nav End -->
	<!-- Project Tabs Content -->
	<div class="tab-content ">
		<div class="uk-switcher projectNavContainer" id="projectNavContainer">
			@foreach($arrCombine as $key=> $dashboards)
			@if($key==0)
			<div  id="{{$all_dashboards[$dashboards]}}">
				@if($all_dashboards[$dashboards] =='SEO')
					@include('vendor.campaign_detail.seo')

				@elseif($all_dashboards[$dashboards]=='PPC')
					@include('vendor.campaign_detail.ppc')

				@elseif($all_dashboards[$dashboards] == 'GMB')
					@include('vendor.dashboards.gmb')

				@elseif($all_dashboards[$dashboards]=='Social')
					@include('vendor.campaign_detail.social')

				@endif
			</div>
			@else
			<div  id="{{$all_dashboards[$dashboards]}}"></div>
			@endif
			@endforeach
		</div>
	</div>
	<!-- Project Tabs Content End -->
</div>
@endsection