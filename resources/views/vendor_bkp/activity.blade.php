@extends('layouts.vendor_internal_pages')
@section('content')


	
	<!-- Activity Section -->
	<input type="hidden" class="campaignID" value="{{ $campaign_id }}">
	<input type="hidden" class="campaign_id" value="{{ $campaign_id }}">
	<input type="hidden" class="user_id" value="{{ @$user_id }}">
	<div class="white-box pa-0 mb-40 activity-box" id="activity-section">
		
		

		<div class="white-box-head">
	        <span class="white-box-handle" uk-icon="icon: table"></span>
	        <div class="left">
	            <div class="loader h-33 half-px"></div>
	            <div class="heading">
	            <img src="{{URL::asset('public/vendor/internal-pages/images/activity-img.png')}}">
	            <h2>Project Activity
	                <span uk-tooltip="title: The section lists various activities currently running on your site. Check the progress, time spent and leave a note, if needed.; pos: top-left"
	                class="fa fa-info-circle"></span></h2>
	            </div>
	        </div>
	        <div class="right">
	            <div class="filter-list activity-filter-list">
	                <ul>
	                    <li>
	                        <button type="button" id="calendar-label" class="btn btn-sm btn-border">
	                            Select Month <span uk-icon="icon:triangle-down"></span>
	                        </button>
	                        <div uk-dropdown="mode: click; pos: bottom-right">
	                            <div class="calendar-dropdown" style="display: none;">
	                                <div class="calendar-head">
	                                    <button type="button" class="custom-calendar" data-type="down" data-value="{{ date('Y') }}" data-id="-1"><span uk-icon="icon:arrow-left"></span></button>
	                                    <h5 id="calendar-year">{{ date('Y') }}</h5>
	                                    <button type="button" class="custom-calendar disable" data-type="up" data-value="{{ date('Y') }}" data-id="+1" disabled="disabled"><span uk-icon="icon:arrow-right"></span></button>
	                                </div>
	                                <div class="calendar-body">
	                                    <div class="month-list">
	                                        @for ($i=0; $i < 12; $i++)
	                                            @if($i == 0)
	                                            <?php $startMonth = date('Y-01-01'); ?>
	                                                <button type="button" class="calendar-month {{ date('m', strtotime('+'.$i.' month', strtotime($startMonth))) > date('m') ? 'oldmonth':'' }} {{ date('m',strtotime($startMonth)) == date('m') ? 'active':'' }}" data-month="{{ date('M',strtotime($startMonth)) }}" data-value="01" >{{ date('M',strtotime($startMonth)) }}</button>
	                                            @else
	                                                <button type="button" class="calendar-month {{ date('m', strtotime('+'.$i.' month', strtotime($startMonth))) > date('m') ? 'newmonth':'' }} {{ date('m', strtotime('+'.$i.' month', strtotime($startMonth))) == date('m') ? 'active':'' }} {{ date('m', strtotime('+'.$i.' month', strtotime($startMonth))) > date('m') ? 'disable':'' }}" data-month="{{ date('M', strtotime('+'.$i.' month', strtotime($startMonth))) }}" data-value="{{ date('m', strtotime('+'.$i.' month', strtotime($startMonth))) }}" {{ date('m', strtotime('+'.$i.' month', strtotime($startMonth))) > date('m') ? 'disabled':'' }} >{{ date('M', strtotime('+'.$i.' month', strtotime($startMonth))) }}</button>
	                                            @endif
	                                        @endfor
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </li>
	                    @for ($i=0; $i < 6; $i++)
	                        <li>
	                            <button type="button" data-module="activities_range" class="activitiesRange {{ $i == 0 ? 'active':'' }} " data-value="{{ date('Y-m',strtotime(' -'.$i.' months' )) }}" >{{ date('M, Y',strtotime(" -".$i." months")) }}</button>
	                        </li>
	                    @endfor

	                </ul>
	            </div>
	        </div>
	    </div>
	    <div class="white-box-body">
	        <div class="activity-tab-section">

	            <div class="white-box-body pa-0">
	                 <div class="project-table-cover checkActivity">
	                    <div class="project-table-body1 table-responsive">
	                        <table class="activitiesList style1" id="activityList">
	                            <thead>
	                                <tr>
	                                    <th>Date</th>
	                                    <th>Activity</th>
	                                    <th>Progress </th>
	                                    <th>No. of Hours </th>
	                                    <th>Note</th>
	                                    <th>Status </th>
	                                    <th>Actions</th>
	                                </tr>
	                            </thead>
	                            <tbody> </tbody>
	                        </table>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	   
	</div>
	<input type="hidden" id="activitylimit" value="10">
	<input type="hidden" id="activitypage" value="1">
	<input type="hidden" id="activitydate" value="{{ date('Y-m') }}">

	<div class="popup" id="activityprogress" data-pd-popup="checkProgress">
	    
	</div>





@endsection