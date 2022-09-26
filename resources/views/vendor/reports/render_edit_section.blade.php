<?php 
function addOrdinalNumberSuffix($num) {
	if (!in_array(($num % 100),array(11,12,13))){
		switch ($num % 10) {
			case 1:  return $num.'st';
			case 2:  return $num.'nd';
			case 3:  return $num.'rd';
		}
	}
	return $num.'th';
}
?>
<input type="hidden" name="report_id" value="{{$result->id}}" >
<div class="form-group EditRecipientEmails">
	<label class="form-label">Recipient Email <small>Where Should We Send Report To?</small></label>	
	<?php if(isset($result) && !empty($result))	{
		$emails = explode(',',$result->email);
		foreach($emails as $key=>$value){?>
			<div class="editRecipientEmail uk-flex uk-flex-center">
				<input type="text" class="form-control edit-recipient-email" placeholder="example@domain.com" autocomplete="off" name="add_recipient_email[]" value="{{$value}}"> 
				@if(count($emails) >1)
				<figure class="edit-remove-append-email"><i class="fa fa-trash"></i></figure>
				@endif
			</div>
		<?php }} ?>
		<div class="append-edit-recipient"></div>
		<button class="uk-button uk-button-link  uk-text-capitalize mt-5" type="button" id="schedule-edit-recipient"><span uk-icon="icon: plus" class="uk-icon"></span> Add Recipient</button>			
	</div>

	<div class="form-group">
		<label class="form-label">Subject</label>			
		<input type="text" class="form-control edit_subject" name="add_subject" value="{{$result->subject}}">	
	</div>

	<div class="form-group">
		<label class="form-label">Text</label>			
		<textarea class="form-control edit_text" name="add_text" id="edit_text" cols="5">{{$result->mail_text}}</textarea>
	</div>

	<div class="form-group">
		<label class="form-label">Project(s) <small>Multiple Projects can be selected</small></label>			
		<select class="form-control selectpicker" data-live-search="true" multiple id="edit-project-list" title="Select Projects">
			@if(!empty($projects) && isset($projects))
			@foreach($projects as $project)
			<option value="{{$project->id}}" <?php if (in_array($project->id, explode(',',$result->request_id))) { echo "selected";}?>>{{$project->host_url}}</option>
			@endforeach
			@endif
		</select>	
		<input type="hidden" class="edit_selected_projects_id" name="add_project_list" value="{{$result->request_id}}">	
	</div>
	<div class="form-group" <?php if($result->report_type == 1){ echo 'style="display: block;"'; }else{ echo 'style="display: none;"'; }?>>
		<div class="uk-flex custom-flex uk-flex-middle">
			<label for="full_report" class="mb-0">Full Report</label>
			<label class="sw ml-2">
				<input id="full_report_edit" name="full_report" type="checkbox" <?php if($result->report_type == 1){ echo "checked"; }?>>
				<div class="sw-pan"></div>
				<div class="sw-btn"></div>
			</label>
		</div>
	</div>	 

	<div class="form-box" id="full_report_frequency_edit" <?php if($result->report_type == 1){ echo 'style="display: block;"'; }else{ echo 'style="display: none;"'; }?> >
		<div class="form-group">
			<label class="form-label">Frequency <small>One or both of these options can be set</small></label>
			<div class="uk-grid">
				<div class="uk-width-1-2@s same-width">		
					<select class="form-control selectpicker edit-full-add-rotation" data-live-search="true" title="Send Report Every" multiple>
						<option value="Sun" <?php echo (in_array('Sun', explode(',',$result->rotation)) && $result->report_type == 1)?'selected':'';?>>Sunday</option>
						<option value="Mon" <?php echo (in_array('Mon', explode(',',$result->rotation)) && $result->report_type == 1)?'selected':'';?>>Monday</option>
						<option value="Tue" <?php echo (in_array('Tue', explode(',',$result->rotation)) && $result->report_type == 1)?'selected':'';?>>Tuesday</option>
						<option value="Wed" <?php echo (in_array('Wed', explode(',',$result->rotation)) && $result->report_type == 1)?'selected':'';?>>Wednesday</option>
						<option value="Thu" <?php echo (in_array('Thu', explode(',',$result->rotation)) && $result->report_type == 1)?'selected':'';?>>Thursday</option>
						<option value="Fri" <?php echo (in_array('Fri', explode(',',$result->rotation)) && $result->report_type == 1)?'selected':'';?>>Friday</option>
						<option value="Sat" <?php echo (in_array('Sat', explode(',',$result->rotation)) && $result->report_type == 1)?'selected':'';?>>Saturday</option>
					</select>
					<input type="hidden" class="edit-full-report-add-rotation-input" name="full_report_add_rotation" <?php if($result->report_type == 1){ echo 'value="'.$result->rotation.'"';}?>>	
				</div>

				<div class="uk-width-1-2@s same-width  mt-xs-10">		
					<select class="form-control selectpicker edit-full-add-day" data-live-search="true" title="Send Reports on these dates" multiple>
						@for ($i = 1; $i <= 30; $i++)
						@if($i < 10)
						<option value="{{'0'.$i}}" <?php echo (in_array($i, explode(',',$result->day)) && $result->report_type == 1)?'selected':'';?>>{{addOrdinalNumberSuffix($i)}} of each month</option>
						@else
						<option value="{{$i}}" <?php echo (in_array($i, explode(',',$result->day)) && $result->report_type == 1)?'selected':'';?>>{{addOrdinalNumberSuffix($i)}} of each month</option>
						@endif
						@endfor
					</select>
					<input type="hidden" class="edit-full-report-add-day-input" name="full_report_add_day" <?php if($result->report_type == 1){ echo 'value="'.$result->day.'"';}?>>	
				</div>
			</div>
		</div>					

		<div class="form-group">
			<label class="form-label">Format <small>Default PDF</small></label>
			<div class="uk-grid-small uk-child-width-auto uk-grid radio-font">
				<label><input class="uk-radio" type="radio" name="full_add_report_format" value="1" checked id="full_add_report_format_pdf"> PDF</label>
			</div>
		</div>
	</div>



	<div class="form-group" <?php if($result->report_type == 2){ echo 'style="display: block;"'; }else{ echo 'style="display: none;"'; }?>>
		<div class="uk-flex custom-flex uk-flex-middle">
			<label for="Keywords_report" class="mb-0">Keywords Report</label>
			<label class="sw ml-2">
				<input id="Keywords_report_edit" name="Keywords_report" type="checkbox" <?php if($result->report_type == 2){ echo "checked"; }?>>
				<div class="sw-pan"></div>
				<div class="sw-btn"></div>
			</label>
		</div>
	</div>			

	<div class="form-box" id="keyword_report_frequency_edit" <?php if($result->report_type == 2){ echo 'style="display: block;"'; }else{ echo 'style="display: none;"'; }?>>
		<div class="form-group">
			<label class="form-label">Frequency <small>One or both of these options can be set</small></label>
			<div class="uk-grid">
				<div class="uk-width-1-2@s same-width">		
					<select class="form-control selectpicker edit-keyword-add-rotation" data-live-search="true" title="Send Report Every" multiple>
						<option value="Sun" <?php echo (in_array('Sun', explode(',',$result->rotation)) && $result->report_type == 2)?'selected':'';?>>Sunday</option>
						<option value="Mon" <?php echo (in_array('Mon', explode(',',$result->rotation)) && $result->report_type == 2)?'selected':'';?>>Monday</option>
						<option value="Tue" <?php echo (in_array('Tue', explode(',',$result->rotation)) && $result->report_type == 2)?'selected':'';?>>Tuesday</option>
						<option value="Wed" <?php echo (in_array('Wed', explode(',',$result->rotation)) && $result->report_type == 2)?'selected':'';?>>Wednesday</option>
						<option value="Thu" <?php echo (in_array('Thu', explode(',',$result->rotation)) && $result->report_type == 2)?'selected':'';?>>Thursday</option>
						<option value="Fri" <?php echo (in_array('Fri', explode(',',$result->rotation)) && $result->report_type == 2)?'selected':'';?>>Friday</option>
						<option value="Sat" <?php echo (in_array('Sat', explode(',',$result->rotation)) && $result->report_type == 2)?'selected':'';?>>Saturday</option>
					</select>
					<input type="hidden" class="edit-keyword-report-add-rotation-input" name="keyword_report_add_rotation" <?php if($result->report_type == 2){ echo 'value="'.$result->rotation.'"';}?>>	
				</div>

				<div class="uk-width-1-2@s same-width  mt-xs-10">		
					<select class="form-control selectpicker edit-keyword-add-day" data-live-search="true" title="Send Reports on these dates" multiple>
						@for ($i = 1; $i <= 30; $i++)
						@if($i < 10)
						<option value="{{'0'.$i}}" <?php echo (in_array($i, explode(',',$result->day)) && $result->report_type == 2)?'selected':'';?>>{{addOrdinalNumberSuffix($i)}} of each month</option>
						@else
						<option value="{{$i}}" <?php echo (in_array($i, explode(',',$result->day)) && $result->report_type == 2)?'selected':'';?>>{{addOrdinalNumberSuffix($i)}} of each month</option>
						@endif
						@endfor
					</select>
					<input type="hidden" class="edit-keyword-report-add-day-input" name="keyword_report_add_day" <?php if($result->report_type == 2){ echo 'value="'.$result->day.'"';}?>>	
				</div>
			</div>
		</div>					

		<div class="form-group">
			<label class="form-label">Format <small>Receive PDF or CSV Reports</small></label>
			<div class="uk-grid-small uk-child-width-auto uk-grid radio-font">
				<label><input class="uk-radio" type="radio" name="keyword_add_report_format" value="1" <?php if($result->format == 1){ echo "checked"; }?> id="keyword_add_report_format_pdf"> PDF</label>
				<label class="add-csv-radio"><input class="uk-radio" type="radio" name="keyword_add_report_format" value="2" <?php if($result->format == 2){ echo "checked"; }?> id="keyword_add_report_format_csv"> CSV</label>
			</div>
		</div>
	</div>

	<div class="text-left btn-group start">           
		<button class="btn blue-btn" type="button" id="update-schedule-report">Save</button>
	</div>