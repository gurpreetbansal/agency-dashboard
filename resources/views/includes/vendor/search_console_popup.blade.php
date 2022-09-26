<div class="dateRange-popup" id="sc-dateRange-popup">
	<form>
		<input type="hidden" class="datepicker_selection" value="1">
		<input type="hidden" class="vk-sidebar-selected" value="dashboard">
		<div class="dateRange-fields">
			<div class="form-group uk-flex">
				<label>Date Range</label>
			</div>
			<div class="form-group uk-flex">
				<div id="sc_current_range" class="form-control sc_daterangepicker">
					<input type="hidden" class="sc_start_date" value="{{@$start_date}}">
					<input type="hidden" class="sc_end_date" value="{{@$end_date}}">
					<input type="hidden" class="current_label" value="{{@$selected}}">
					<input type="hidden" class="comparison_days">
					<i class="fa fa-calendar"></i><p></p>
				</div>
			</div>
			<div class="form-group uk-flex">
				<input type="hidden" class="is_compare">
				<label class='sw'>
					<input type='checkbox'  class="sc_compare" <?php if(@$comparison == 1){echo "checked";}?>>
					<div class='sw-pan'></div>
					<div class='sw-btn'></div>
				</label>
				<label>Compare to:</label>
				<select class="form-control" id="sc_comparison" <?php if(@$comparison == 0){echo "readonly disabled";}?>  >
					<option selected="selected" value="previous_period" {{@$compare_to === 'previous_period'?'selected':''}}>Previous period</option>
					<option value="previous_year" {{@$compare_to === 'previous_year'?'selected':''}}>Previous year</option>
				</select>
			</div>
			<div class="form-group uk-flex <?php if(@$comparison == 0){echo 'hidden-previous-datepicker';}?>" id="previous-section">
				<div id="sc_previous_range" class="form-control sc_daterangepicker">
					<input type="hidden" class="sc_prev_start_date" value="{{@$compare_start_date}}">
					<input type="hidden" class="sc_prev_end_date" value="{{@$compare_end_date}}">
					<input type="hidden" class="prev_comparison_days">
					<i class="fa fa-calendar"></i><p></p>
				</div>
			</div>
			<div class="uk-flex">
				<input type="button" class="btn blue-btn sc_apply_btn" value="Apply" >
				<a href="javascript:;" class="sc_cancel_btn"><input type="button" class="btn btn-border red-btn-border" value="Cancel"></a>
			</div>
		</div>
	</form>
</div>