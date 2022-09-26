@extends('layouts.vendor_layout',['page' => 'new-dashboard'])
@section('content')
<input type="hidden" class="campaignID" value="{{$campaign_id}}">

<div class="app-inner-layout">
	@php

		@$dashUsed = array_intersect($types,array_keys($all_dashboards));

		@$dashDiff = array_diff(array_keys($all_dashboards),$types);

		@$arrCombine = array_merge($dashUsed,$dashDiff);


	@endphp



		<div class="container">
	        <ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-rounded-lg newDashboard">
	        	@foreach($arrCombine as $key=> $dashboards)
					@if(array_key_exists($dashboards,$all_dashboards))
				 <li class="nav-item ">
	                <a role="tab" class="nav-link text-center  <?php if($key == 0){ echo "active "; } ?>" href="#{{$all_dashboards[$dashboards]}}" 	data-toggle="tab">
	                    <span data-toggle="tooltip" data-placement="bottom" title="<?php if(in_array($dashboards, $dashUsed)){ echo 'Connected ';} if(in_array($dashboards, $dashDiff)){ echo "Inactive"; } ?>">{{$all_dashboards[$dashboards]}} <small class="badge badge-dot badge-dot-sm badge-<?php if(in_array($dashboards, $dashUsed)){ echo 'success ';} if(in_array($dashboards, $dashDiff)){ echo "danger"; } ?>" style="vertical-align: 10px;margin: 2px;">&nbsp;</small></span>
	                </a>
	            </li>
	            @endif
	           @endforeach
	        </ul>
    	</div>


	<div class="tab-content" id="DashboardSection">

		@foreach($arrCombine as $key=> $dashboards)

			@if($key==0)
				<div id="{{$all_dashboards[$dashboards]}}" class=" mainDashboardSection tab-pane  fade  <?php if($key==0){ echo 'in active show'; }?>">


						@if($all_dashboards[$dashboards] =='SEO')
							@include('vendor.dashboards.seo')

						@elseif($all_dashboards[$dashboards]=='PPC')
							@include('vendor.dashboards.ppc')

						@elseif($all_dashboards[$dashboards] == 'GMB')
						@include('vendor.dashboards.gmb')

						@elseif($all_dashboards[$dashboards]=='Social')
							@include('vendor.dashboards.social')

						@endif


				</div>
			@else
				<div id="{{$all_dashboards[$dashboards]}}" class="mainDashboardSection tab-pane fade "></div>
			@endif
		@endforeach
	</div>
</div>
@endsection