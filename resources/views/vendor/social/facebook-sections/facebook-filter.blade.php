<div class="filter-list">
	@isset($gtUser->fbid)
		<p class="overview_date_range"><span uk-icon="clock"></span> Last 30 Days</p>
	@endisset
	<ul>
		<li>
			<p><span uk-icon="clock"></span> </p>
		</li>
		<li>
			<a href="javascript:;" id="facebook_dateRange" class="btn icon-btn color-blue" uk-tooltip="title: Date Range; pos: top-center">
				<img src="{{URL::asset('/public/vendor/internal-pages/images/date-rance-calender-icon.png')}}">
			</a>
		</li>
		<li>
            <a href="javascript:;" data-request-id="{{$campaign_id}}" id="refreshFacebookData" class="btn icon-btn color-purple" uk-tooltip="title: Refresh Facebook Page Data; pos: top-center" title="" aria-expanded="false"><img src="{{URL::asset('/public/vendor/internal-pages/images/refresh-icon.png')}}"></a>
         </li>
	</ul>
</div>


<div class="dateRange-popup">
	<form>
		<div class="dateRange-fields">
			<div class="form-group uk-flex">
				<label>Date Range</label>
			</div>
			<div class="form-group uk-flex">
				<div id="facebook_current_range" class="form-control rangepicker facebook_daterangepicker">
					<input type="hidden" class="facebook_start_date" >
					<input type="hidden" class="facebook_end_date" >
					<input type="hidden" class="facebook_current_label" >
					<input type="hidden" class="facebook_comparison_days">
					<i class="fa fa-calendar"></i><p></p>
				</div>
			</div>
			<div class="form-group uk-flex">
				<input type="hidden" class="facebook_is_compare">
				<label class='sw'>
					<input type='checkbox'  class="facebook_compare" >
					<div class='sw-pan'></div>
					<div class='sw-btn'></div>
				</label>
				<label>Compare to:</label>
				<select class="form-control" id="facebook_comparison">
					<option class="facebook_previous_period" selected="selected" value="previous_period" >Previous period</option>
					<option class="facebook_previous_year" value="previous_year">Previous year</option>
				</select>
			</div>
			<div class="form-group uk-flex" id="facebook-previous-section">
				<div id="facebook_previous_range" class="form-control rangepicker facebook_daterangepicker">
					<input type="hidden" class="facebook_prev_start_date" >
					<input type="hidden" class="facebook_prev_end_date" >
					<input type="hidden" class="facebook_prev_comparison_days">
					<i class="fa fa-calendar"></i><p></p>
				</div>
			</div>
			<div class="uk-flex">
				<input type="button" class="btn blue-btn facebook_apply_btn" value="Apply" >
				<a href="javascript:;" class="facebook_cancel_btn"><input type="button" class="btn btn-border red-btn-border" value="Cancel"></a>
			</div>
		</div>
	</form>
</div>