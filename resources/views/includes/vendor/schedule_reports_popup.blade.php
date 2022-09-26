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

<div id="add-schedule-report" class="uk-flex-top" uk-modal>
	<div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical px-0">
		<div class="custom-scroll">
			<button class="uk-modal-close-default" type="button" uk-close></button>

			<h3><figure><i class="fa fa-file"></i></figure> Create New Scheduled Report </h3>	

			<form id="add-schedule-report-form" name="add-schedule-report-form">	
				<div class="scheduleReport-progress-loader popup-progress-loader"></div>
				@csrf
				<div class="form-group AddRecipientEmails">
					<label class="form-label">Recipient Email <small>Where Should We Send Report To?</small></label>			
					<input type="text" class="form-control add-recipient-email" placeholder="example@domain.com" autocomplete="off" name="add_recipient_email[]"> 
					<div class="append-add-recipient"></div>
					<button class="uk-button uk-button-link  uk-text-capitalize mt-5" type="button" id="schedule-add-recipient"><span uk-icon="icon: plus" class="uk-icon"></span> Add Recipient</button>			
				</div>

				<div class="form-group">
					<label class="form-label">Subject</label>			
					<input type="text" class="form-control add_subject" name="add_subject" value="AgencyDashboard.io Report:">	
				</div>

				<div class="form-group">
					<label class="form-label">Text</label>			
					<textarea id="add_text" name="add_text" cols="5" class="form-control add_text">
						<p>Your report is attached to this email.</p>
						<p>Thank you,</p>
						<p>Agency Dashboard</p>
					</textarea>
				</div>

				<div class="form-group">
					<label class="form-label">Project(s) <small>Multiple Projects can be selected</small></label>			
					<select class="form-control selectpicker" data-live-search="true" multiple id="add-project-list" title="Select Projects">
						<option>Select Projects</option>
					</select>	
					<input type="hidden" class="add_selected_projects_id" name="add_project_list">	
				</div>

				<div class="form-group">
					<div class="uk-flex custom-flex">
	                	<label for="full_report">Full Report</label>
	                	<label class="sw">
	                  		<input id="full_report" name="full_report" type="checkbox">
	                  		<div class="sw-pan"></div>
	                  		<div class="sw-btn"></div>
	            		</label>
	            	</div>
	          	</div>	 

	          	<div class="form-box" id="full_report_frequency" style="display: none;">
					<div class="form-group">
						<label class="form-label">Frequency <small>One or both of these options can be set</small></label>
						<div class="uk-grid">
							<div class="uk-width-1-2@s same-width">		
								<select class="form-control selectpicker full-add-rotation" data-live-search="true" title="Send Report Every" multiple>
									<option value="Sun">Sunday</option>
									<option value="Mon">Monday</option>
									<option value="Tue">Tuesday</option>
									<option value="Wed">Wednesday</option>
									<option value="Thu">Thursday</option>
									<option value="Fri">Friday</option>
									<option value="Sat">Saturday</option>
								</select>
								<input type="hidden" class="full-report-add-rotation-input" name="full_report_add_rotation">	
							</div>

							<div class="uk-width-1-2@s same-width  mt-xs-10">		
								<select class="form-control selectpicker full-add-day" data-live-search="true" title="Send Reports on these dates" multiple>
									<?php 
									for ($i = 1; $i <= 30; $i++){
										if($i < 10){
											echo "<option value=0".$i.">".addOrdinalNumberSuffix($i)." of each month</option>";
										}else{
											echo "<option value=".$i.">".addOrdinalNumberSuffix($i)." of each month</option>";
										}
									}
									?>
								</select>
								<input type="hidden" class="full-report-add-day-input" name="full_report_add_day">	
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

	          	<div class="form-group">
					<div class="uk-flex custom-flex">
	                	<label for="Keywords_report">Keywords Report</label>
	                	<label class="sw">
	                  		<input id="Keywords_report" name="Keywords_report" type="checkbox">
	                  		<div class="sw-pan"></div>
	                  		<div class="sw-btn"></div>
	            		</label>
	            	</div>
	          	</div>			

				<div class="form-box" id="keyword_report_frequency" style="display: none;">
					<div class="form-group">
						<label class="form-label">Frequency <small>One or both of these options can be set</small></label>
						<div class="uk-grid">
							<div class="uk-width-1-2@s same-width">		
								<select class="form-control selectpicker keyword-add-rotation" data-live-search="true" title="Send Report Every" multiple>
									<option value="Sun">Sunday</option>
									<option value="Mon">Monday</option>
									<option value="Tue">Tuesday</option>
									<option value="Wed">Wednesday</option>
									<option value="Thu">Thursday</option>
									<option value="Fri">Friday</option>
									<option value="Sat">Saturday</option>
								</select>
								<input type="hidden" class="keyword-report-add-rotation-input" name="keyword_report_add_rotation">	
							</div>

							<div class="uk-width-1-2@s same-width  mt-xs-10">		
								<select class="form-control selectpicker keyword-add-day" data-live-search="true" title="Send Reports on these dates" multiple>
									<?php 
									for ($i = 1; $i <= 30; $i++){
										if($i < 10){
											echo "<option value=0".$i.">".addOrdinalNumberSuffix($i)." of each month</option>";
										}else{
											echo "<option value=".$i.">".addOrdinalNumberSuffix($i)." of each month</option>";
										}
									}
									?>
								</select>
								<input type="hidden" class="keyword-report-add-day-input" name="keyword_report_add_day">	
							</div>
						</div>
					</div>					

					<div class="form-group">
						<label class="form-label">Format <small>Receive PDF or CSV Reports</small></label>
						<div class="uk-grid-small uk-child-width-auto uk-grid radio-font">
							<label><input class="uk-radio" type="radio" name="keyword_add_report_format" value="1" checked id="keyword_add_report_format_pdf"> PDF</label>
							<label class="add-csv-radio"><input class="uk-radio" type="radio" name="keyword_add_report_format" value="2"  id="keyword_add_report_format_csv"> CSV</label>
						</div>
					</div>
				</div>

				<div class="text-left btn-group start">           
					<button class="btn blue-btn" type="button" id="create-schedule-report">Create</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="delete-schedule-report" class="uk-flex-top " uk-modal>
	<div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical px-0 small-popup">
		<div class="custom-scroll">
			<button class="uk-modal-close-default" type="button" uk-close></button>
			<p class="uk-text-center"> Do you want to delete this Report</p>
			<div class="text-left btn-group center">           
				<button class="btn gray-btn uk-modal-close" type="button">Cancel</button>
				<button class="btn blue-btn delete_scheduled_report" type="button" >Delete</button>
			</div>
		</div>
	</div>
</div>

<div id="schedule-report-history" class="uk-flex-top" uk-modal>
	<div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical px-0">
		<div class="custom-scroll">
			<button class="uk-modal-close-default" type="button" uk-close></button>

			<h3 class="scheduled-report-project-name"> Send History </h3>	
			<table>
				<thead>
					<tr>
						<th>Delivery Date</th>
						<th>Sent To</th>
						<th>Email Status</th>
					</tr>
				</thead>
				<tbody id="schedule-report-history-data"></tbody>
			</table>		
		</div>
	</div>
</div>


<div id="schedule-report-edit" class="uk-flex-top" uk-modal>
	<div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical px-0">
		<div class="custom-scroll">
			<button class="uk-modal-close-default" type="button" uk-close></button>

			<h3><figure><i class="fa fa-file"></i></figure> Edit Scheduled Report </h3>	

			<form id="edit-schedule-report-form" name="edit-schedule-report-form">
				<div class="edit-scheduleReport-progress-loader popup-progress-loader"></div>
					@csrf
				<div class="display-edit-scheduledReport">

					<div class="form-group EditRecipientEmails">
						<label class="form-label">Recipient Email <small>Where Should We Send Report To?</small></label>	
						<div class="editRecipientEmail uk-flex uk-flex-center">
							<input type="text" class="form-control edit-recipient-email" placeholder="example@domain.com" autocomplete="off" name="add_recipient_email[]"> 
							<figure class="edit-remove-append-email"><i class="fa fa-trash"></i></figure>
						</div>
						<div class="append-edit-recipient"></div>
						<button class="uk-button uk-button-link  uk-text-capitalize mt-5" type="button" id="schedule-edit-recipient"><span uk-icon="icon: plus" class="uk-icon"></span> Add Recipient</button>			
					</div>

					<div class="form-group">
						<label class="form-label">Subject</label>			
						<input type="text" class="form-control edit_subject" name="add_subject">	
					</div>

					<div class="form-group">
						<label class="form-label">Text</label>			
						<textarea class="form-control edit_text" name="add_text"></textarea>
					</div>

					<div class="form-group">
						<label class="form-label">Project(s) <small>Multiple Projects can be selected</small></label>			
						<select class="form-control selectpicker" data-live-search="true" multiple id="edit-project-list" title="Select Projects">
							<option>Select Projects</option>
						</select>	
					</div>

					<div class="form-group">
						<div class="uk-flex custom-flex">
		                	<label for="full_report">Full Report</label>
		                	<label class="sw">
		                  		<input id="full_report_edit" name="full_report" type="checkbox">
		                  		<div class="sw-pan"></div>
		                  		<div class="sw-btn"></div>
		            		</label>
		            	</div>
		          	</div>	 

		          	<div class="form-box" id="full_report_frequency_edit" style="display: none;">
					<div class="form-group">
						<label class="form-label">Frequency <small>One or both of these options can be set</small></label>
						<div class="uk-grid">
							<div class="uk-width-1-2@s same-width">		
								<select class="form-control selectpicker full-add-rotation" data-live-search="true" title="Send Report Every" multiple>
									<option value="Sun">Sunday</option>
									<option value="Mon">Monday</option>
									<option value="Tue">Tuesday</option>
									<option value="Wed">Wednesday</option>
									<option value="Thu">Thursday</option>
									<option value="Fri">Friday</option>
									<option value="Sat">Saturday</option>
								</select>
								<input type="hidden" class="full-report-add-rotation-input" name="full_report_add_rotation">	
							</div>

							<div class="uk-width-1-2@s same-width  mt-xs-10">		
								<select class="form-control selectpicker full-add-day" data-live-search="true" title="Send Reports on these dates" multiple>
									<?php 
									for ($i = 1; $i <= 30; $i++){
										if($i < 10){
											echo "<option value=0".$i.">".addOrdinalNumberSuffix($i)." of each month</option>";
										}else{
											echo "<option value=".$i.">".addOrdinalNumberSuffix($i)." of each month</option>";
										}
									}
									?>
								</select>
								<input type="hidden" class="full-report-add-day-input" name="full_report_add_day">	
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



				<div class="form-group">
					<div class="uk-flex custom-flex">
	                	<label for="Keywords_report">Keywords Report</label>
	                	<label class="sw">
	                  		<input id="Keywords_report_edit" name="Keywords_report" type="checkbox">
	                  		<div class="sw-pan"></div>
	                  		<div class="sw-btn"></div>
	            		</label>
	            	</div>
	          	</div>			

				<div class="form-box" id="keyword_report_frequency_edit" style="display: none;">
					<div class="form-group">
						<label class="form-label">Frequency <small>One or both of these options can be set</small></label>
						<div class="uk-grid">
							<div class="uk-width-1-2@s same-width">		
								<select class="form-control selectpicker keyword-add-rotation" data-live-search="true" title="Send Report Every" multiple>
									<option value="Sun">Sunday</option>
									<option value="Mon">Monday</option>
									<option value="Tue">Tuesday</option>
									<option value="Wed">Wednesday</option>
									<option value="Thu">Thursday</option>
									<option value="Fri">Friday</option>
									<option value="Sat">Saturday</option>
								</select>
								<input type="hidden" class="keyword-report-add-rotation-input" name="keyword_report_add_rotation">	
							</div>

							<div class="uk-width-1-2@s same-width  mt-xs-10">		
								<select class="form-control selectpicker keyword-add-day" data-live-search="true" title="Send Reports on these dates" multiple>
									<?php 
									for ($i = 1; $i <= 30; $i++){
										if($i < 10){
											echo "<option value=0".$i.">".addOrdinalNumberSuffix($i)." of each month</option>";
										}else{
											echo "<option value=".$i.">".addOrdinalNumberSuffix($i)." of each month</option>";
										}
									}
									?>
								</select>
								<input type="hidden" class="keyword-report-add-day-input" name="keyword_report_add_day">	
							</div>
						</div>
					</div>					

					<div class="form-group">
						<label class="form-label">Format <small>Receive PDF or CSV Reports</small></label>
						<div class="uk-grid-small uk-child-width-auto uk-grid radio-font">
							<label><input class="uk-radio" type="radio" name="keyword_add_report_format" value="1" checked id="keyword_add_report_format_pdf"> PDF</label>
							<label class="add-csv-radio"><input class="uk-radio" type="radio" name="keyword_add_report_format" value="2"  id="keyword_add_report_format_csv"> CSV</label>
						</div>
					</div>
				</div>
					<div class="text-left btn-group start">           
						<button class="btn blue-btn" type="button" id="update-schedule-report">Save</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>




<div id="confirmation-schedule-report" class="uk-flex-top " uk-modal>
	<div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical px-0 small-popup">
		<div class="custom-scroll">
			<button class="uk-modal-close-default" type="button" uk-close></button>
			<p class="uk-text-center confirmation-text mt-3"></p>
			<form>
				@csrf	
				<div class="text-left btn-group center">           
					<button class="btn gray-btn uk-modal-close" type="button">Cancel</button>
					<button class="btn blue-btn delete_existing_report" type="button">Ok</button>
				</div>
			</form>
		</div>
	</div>
</div>