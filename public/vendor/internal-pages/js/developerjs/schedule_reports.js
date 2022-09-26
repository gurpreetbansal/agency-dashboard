var BASE_URL = $('.base_url').val();

$(document).ready(function(){
	fetch_campaigns();
	ScheduleReportTable();

});

window.onload = function () {
	tinymce.init({
		selector: '#add_text',
		menubar:false	
	});
}

function fetch_campaigns(){
	$.ajax({
		type:'GET',
		dataType:'json',
		url:BASE_URL + '/ajax_fetch_campaigns',
		success:function(response){
			$('#add-project-list').html('');
			$('#add-project-list').html(response['select']);
			$('.selectpicker').selectpicker('refresh');
		}
	});
}

$(document).on('click','.add-schedule-report',function(){
	$('form#add-schedule-report-form')[0].reset();
	$('.full-report-add-rotation-input,.full-report-add-day-input').removeAttr('value');
	$("#add-schedule-report-form select").selectpicker("refresh");
	$('.add-csv-radio').css('display','block');
});

function ValidateEmail(email) {
	var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	if (!expr.test(email)) {
		return 'error';
	}else{
		return 'success';
	}
}

$(document).on('keyup blur focusout','.add-recipient-email', function () {
	if($(this).val() !== ''){
		if(ValidateEmail($(this).val()) === 'error'){
			$(this).addClass('error');
		}else{
			$(this).removeClass('error');
		}
	}else{
		$(this).removeClass('error');
	}
});


$(document).on('click','#schedule-add-recipient',function(e){
	$('.append-add-recipient').append('<div class="append-reciepient-email uk-flex uk-flex-center"><input type="text" class="form-control add-recipient-email" placeholder="example@domain.com" autocomplete="off"  name="add_recipient_email[]"><figure class="remove-append-email"><i class="fa fa-trash"></i></figure></div>');
});


$(document).on('click','.remove-append-email',function(e){
	$(this).parent().remove();
});

$(document).on('change','.add-report-type',function(){
	if($(this).val() === "1"){
		$('#add_report_format_pdf').prop('checked',true);
		$('#add_report_format_csv').prop('checked',false);
		$('.add-csv-radio').css('display','none');
	}else{
		$('.add-csv-radio').css('display','block');
	}
});

$(document).on('change','#add-project-list',function(){
	var options  = $('#add-project-list option:selected');
	var selected = [];
	$(options).each(function(){
		selected.push($(this).val());
	});
	$('.add_selected_projects_id').val(selected);
});


$('.add-rotation').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {	

	var options  = $('.add-rotation option:selected');
	var selected = [];
	$(options).each(function(){
		selected.push($(this).val()); 
	});
	$('.add-rotation-input').val(selected);
	if (selected === '') {
		$('.add-rotation-input').parent().find('.bootstrap-select').addClass('error');
		$('.add-day-input').parent().find('.bootstrap-select').addClass('error');
	}else{
		$('.add-rotation-input').parent().find('.bootstrap-select').removeClass('error');
		$('.add-day-input').parent().find('.bootstrap-select').removeClass('error');
	}
});


$('.add-day').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {	
	var options  = $('.add-day option:selected');
	var selected = [];
	$(options).each(function(){
		selected.push($(this).val()); 
	});
	$('.add-day-input').val(selected);
	if (selected === '') {
		$('.add-day-input').parent().find('.bootstrap-select').addClass('error');
		$('.add-rotation-input').parent().find('.bootstrap-select').addClass('error');
	}else{
		$('.add-day-input').parent().find('.bootstrap-select').removeClass('error');
		$('.add-rotation-input').parent().find('.bootstrap-select').removeClass('error');
	}
});


$(document).on("keyup change", '#add-schedule-report-form input,#add-schedule-report-form select,#add-schedule-report-form radio,#add-schedule-report-form textarea', function(e) {
	
	if ($('.add-recipient-email').val() === '') {
		$('.add-recipient-email').addClass('error');
	}else{
		if(ValidateEmail($('.add-recipient-email').val()) === 'error'){
			$('.add-recipient-email').addClass('error');
		}else{
			$('.add-recipient-email').removeClass('error');
		}
	}

	if ($('.add_subject').val() === '') {
		$('.add_subject').addClass('error');
	}else{
		$('.add_subject').removeClass('error');
	}

	if(tinymce.get("add_text").getContent() === ''){
		$('.add_text').addClass('error');
	}else{
		$('.add_text').removeClass('error');
	}

	if ($('.add_selected_projects_id').val() === '') {
		$('.add_selected_projects_id').parent().find('.bootstrap-select').addClass('error');
	}else{
		$('.add_selected_projects_id').parent().find('.bootstrap-select').removeClass('error');
	}
});





$(document).on('click','#create-schedule-report',function(e){
	e.preventDefault();
	$('#create-schedule-report').attr('disabled','disabled');
	$('.scheduleReport-progress-loader').css('display','block');
	$('#add-schedule-report div').addClass('uk-overflow-hidden');
	var summary = tinymce.get("add_text").getContent();
	var myform = document.getElementById("add-schedule-report-form");
	var fd = new FormData(myform);
	fd.append('add_text',summary);
	$.ajax({
		url: BASE_URL+'/ajax_create_schedule_report',
		data: fd,
		cache: false,
		processData: false,
		contentType: false,
		type: 'POST',
		success: function (dataofconfirm) {
			if(dataofconfirm['status'] === 0){
				if(dataofconfirm['message']['add_recipient_email']){
					if(dataofconfirm['message']['add_recipient_email'].length !== 0){
						Command: toastr["error"]('An invalid email was detected, please double check your email(s)!');
						if(dataofconfirm['message']['add_recipient_email'].length === 1){
							$(".AddRecipientEmails input").addClass('error');
						}else{
							$(dataofconfirm['message']['add_recipient_email']).each(function(index) {
								$(".AddRecipientEmails input:nth-child("+ index +")").addClass('error');
							});
						}
					} 
				}

				if(dataofconfirm['message']['add_subject']){
					Command: toastr["error"]('Subject is required');
				}

				if(dataofconfirm['message']['add_text']){
					Command: toastr["error"]('Text is required');
				}

				if(dataofconfirm['message']['add_project_list']){
					Command: toastr["error"]('Please select a project to report!');
					$('.add_selected_projects_id').parent().find('.bootstrap-select').addClass('error');
				} 

				if(dataofconfirm['message']['full_report_options']){
					Command: toastr["error"](dataofconfirm['message']['full_report_options']);
					$('.full-report-add-day-input').parent().find('.bootstrap-select').addClass('error');
					$('.full-report-add-rotation-input').parent().find('.bootstrap-select').addClass('error');
				}

				if(dataofconfirm['message']['keyword_report_options']){
					Command: toastr["error"](dataofconfirm['message']['keyword_report_options']);
					$('.keyword-report-add-day-input').parent().find('.bootstrap-select').addClass('error');
					$('.keyword-report-add-rotation-input').parent().find('.bootstrap-select').addClass('error');
				}

				if(dataofconfirm['message']['report_options']){
					Command: toastr["error"](dataofconfirm['message']['report_options']);
					$('#Keywords_report').parent().addClass('error');
					$('#full_report').parent().addClass('error');
				}
			}

			if(dataofconfirm['status'] === 1){
				$(".uk-close").trigger("click");
				ScheduleReportTable();
				$('form#add-schedule-report-form')[0].reset();
				$('.full-report-add-rotation-input,.full-report-add-day-input').removeAttr('value');
				$('#full_report_frequency,#keyword_report_frequency').css('display','none');
				Command: toastr["success"](dataofconfirm['message']);
			}
			$('#create-schedule-report').removeAttr('disabled','disabled');

			setTimeout(function(){
				$('.scheduleReport-progress-loader').addClass('complete');
				setTimeout(function(){
					$('.scheduleReport-progress-loader').css('display','none');
					$('.scheduleReport-progress-loader').removeClass('complete');
					$('#add-schedule-report div').removeClass('uk-overflow-hidden');
				}, 500);
			}, 500);
		}	        
	});
});


function ScheduleReportTable(){
	var column_name = $('#column_name_scheduleReport').val();
	var order_type = $('#sort_type_scheduleReport').val();
	var limit = $('#limit_scheduleReport').val();
	var page = $('#page_scheduleReport').val();
	var search = $('.schedule-report-search').val();
	$('#page_scheduleReport').val(page);

	ScheduleReportList(column_name, order_type, limit, page,search);
}


function ajaxData(column_name, order_type, limit, page,search) {
	$.ajax({
		url: BASE_URL + '/ajax_data',
		type: 'GET',
		data: {column_name, order_type, limit, page,search},
		success: function(response) {
			$('#schedule_report_table tbody').html('');
			$('#schedule_report_table tbody').html(response);
			$('#schedule_report_table tr th').removeClass('ajax-loader');
			$('#schedule_report_table tr td').removeClass('ajax-loader');
		}
	});
}

function ScheduleReportList(column_name, order_type, limit, page,search) {
	$.ajax({
		url: BASE_URL + '/ajax_schedule_report_list',
		type: 'GET',
		data: {column_name, order_type, limit, page,search},
		success: function(response) {
			$('#schedule_report_table tbody').html('');
			$('#schedule_report_table tbody').html(response);
			$('#schedule_report_table tr th').removeClass('ajax-loader');
			$('#schedule_report_table tr td').removeClass('ajax-loader');
		}
	});


	$.ajax({
		url: BASE_URL + '/ajax_schedule_report_pagination',
		type: 'GET',
		data: {column_name, order_type, limit, page,search},
		success: function(response) {
			$('#schedule_report_table_foot').html('');
			$('#schedule_report_table_foot').html(response);
			$('.schedulereport').removeClass('ajax-loader');
			$('.project-entries').removeClass('ajax-loader');
			$('.project-search').removeClass('ajax-loader');
		}
	});
}


$(document).on('click','.remove_scheduled_report',function(e){
	e.preventDefault();
	$('.delete_scheduled_report').attr('report-id',$(this).attr('report-id'));
});

$(document).on('click','.delete_scheduled_report',function(e){
	e.preventDefault();
	var report_id = $(this).attr('report-id');
	$.ajax({
		type:'POST',
		data:{report_id,_token:$('meta[name="csrf-token"]').attr('content')},
		dataType:'json',
		url:BASE_URL+'/ajax_remove_scheduled_report',
		success:function(response){
			$("#delete-schedule-report .uk-close").trigger("click");
			if(response['status'] === 1){
				Command: toastr["success"](response['message']);
				ScheduleReportTable();
			}
			if(response['status'] === 0){
				Command: toastr["error"](response['message']);
			}			
		}
	});
});


$(document).on('click','.send_report_now',function(e){
	e.preventDefault();	
	var report_id = $(this).attr('report-id');
	if(report_id !== ''){
		Command: toastr["success"]('Request sent successfully, you will recieve your report shortly.');
		$.ajax({
			type:'POST',
			data:{report_id,_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			url:BASE_URL+'/ajax_send_report_now',
			success:function(response){
				if(response['status'] === 1){
					Command: toastr["success"](response['message']);
					ScheduleReportTable();
				}
				if(response['status'] === 0){
					Command: toastr["error"](response['message']);
				}			
			}
		});
	}else{
		Command: toastr["error"]('Please try again later.');
	}
});

$(document).on('click','.scheduled_report_history',function(e){
	$('.scheduled-report-project-name').text($(this).attr('project-name') +' Send History');
	getScheduleHistory($(this).attr('report-id'));
});

function getScheduleHistory(report_id){
	$.ajax({
		type:'GET',
		data:{report_id},
		url:BASE_URL +'/ajax_get_scheduled_report_history',
		success:function(response){
			$('#schedule-report-history-data').html(response);
		}
	});
}

$(document).on('click','.schedule_report_edit',function(e){
	var request_id = $(this).attr('report-id');
	$('.edit-scheduleReport-progress-loader').css('display','block');
	$('#schedule-report-edit div.uk-modal-body').addClass('uk-overflow-hidden');
	$('#edit-schedule-report-form').addClass('form-overlay');
	$('#update-schedule-report').attr('disabled','disabled');

	$(".display-edit-scheduledReport").load('/ajax_get_scheduled_report/'+request_id, function(responseTxt, statusTxt, xhr){
		if(statusTxt === 'success')
			$('.selectpicker').selectpicker('refresh');
		tinymce.init({
			selector: '#edit_text',
			menubar:false	
		});
		$('.edit-scheduleReport-progress-loader').css('display','none');
		$('#schedule-report-edit div.uk-modal-body').removeClass('uk-overflow-hidden');
		$('#edit-schedule-report-form').removeClass('form-overlay');
		$('#update-schedule-report').removeAttr('disabled','disabled');
	});
});

$(document).on('click','.edit-remove-append-email',function(e){
	$(this).parent().remove();
	if($('.editRecipientEmail').length == 1){
		$('.editRecipientEmail').addClass('hideTrash');
	}
});

$(document).on('click','#schedule-edit-recipient',function(){
	$('.editRecipientEmail').removeClass('hideTrash');
	$('.append-edit-recipient').append('<div class="editRecipientEmail uk-flex uk-flex-center"><input type="text" class="form-control edit-recipient-email" placeholder="example@domain.com" autocomplete="off"  name="add_recipient_email[]"><figure class="edit-remove-append-email"><i class="fa fa-trash"></i></figure></div>');
});

$(document).on('change','#edit-project-list',function(){
	var options  = $('#edit-project-list option:selected');
	var selected = [];
	$(options).each(function(){
		selected.push($(this).val());
	});
	$('.edit_selected_projects_id').val(selected);
});

$(document).on("keyup change", '#edit-schedule-report-form input,#edit-schedule-report-form select,#edit-schedule-report-form radio,#edit-schedule-report-form textarea', function(e) {
	if ($('.edit-recipient-email').val() === '') {
		$('.edit-recipient-email').addClass('error');
	}else{

		if(ValidateEmail($('.edit-recipient-email').val()) === 'error'){
			$('.edit-recipient-email').addClass('error');
		}else{
			$('.edit-recipient-email').removeClass('error');
		}
	}

	if ($('.edit_subject').val() === '') {
		$('.edit_subject').addClass('error');
	}else{
		$('.edit_subject').removeClass('error');
	}

	if (tinymce.get("edit_text").getContent() === '') {
		$('.edit_text').addClass('error');
	}else{
		$('.edit_text').removeClass('error');
	}

	if ($('.edit_selected_projects_id').val() === '') {
		$('.edit_selected_projects_id').parent().find('.bootstrap-select').addClass('error');
	}else{
		$('.edit_selected_projects_id').parent().find('.bootstrap-select').removeClass('error');
	}

	if ($('.edit-rotation-input').val() === '' && $('.add-day-input').val() === '') {
		$('.edit-rotation-input').parent().find('.bootstrap-select').addClass('error');
	}else{
		$('.edit-rotation-input').parent().find('.bootstrap-select').removeClass('error');
	}
	if ($('.edit-day-input').val() === '' && $('.add-rotation-input').val() === '') {
		$('.edit-day-input').parent().find('.bootstrap-select').addClass('error');
	}else{
		$('.edit-day-input').parent().find('.bootstrap-select').removeClass('error');
	}
});

$(document).on('keyup blur focusout','.edit-recipient-email', function () {
	if($(this).val() !== ''){
		if(ValidateEmail($(this).val()) === 'error'){
			$(this).addClass('error');
		}else{
			$(this).removeClass('error');
		}
	}else{
		$(this).removeClass('error');
	}
});

$(document).on('change','.edit-report-type',function(){
	if($(this).val() === "1"){
		$('#edit_report_format_pdf').prop('checked',true);
		$('#edit_report_format_csv').prop('checked',false);
		$('.edit-csv-radio').css('display','none');
	}else{
		$('.edit-csv-radio').css('display','block');
	}
});

$(document).on('click','#update-schedule-report',function(e){
	e.preventDefault();
	var summary = tinymce.get("edit_text").getContent();
	$('#update-schedule-report').attr('disabled','disabled');
	$('.edit-scheduleReport-progress-loader').css('display','block');
	$('#schedule-report-edit div.uk-modal-body').addClass('uk-overflow-hidden');
	var myform = document.getElementById("edit-schedule-report-form");
	var fd = new FormData(myform);
	fd.append("add_text", summary);
	$.ajax({
		url: BASE_URL+'/ajax_update_schedule_report',
		data: fd,
		cache: false,
		processData: false,
		contentType: false,
		type: 'POST',
		success: function (dataofconfirm) {
			if(dataofconfirm['status'] === 0){
				if(dataofconfirm['message']['add_recipient_email'] && dataofconfirm['message']['add_recipient_email'].length !== 0){
					Command: toastr["error"]('An invalid email was detected, please double check your email(s)!');
					if(dataofconfirm['message']['add_recipient_email'].length === 1){
						$(".EditRecipientEmails input").addClass('error');
					}else{
						$(dataofconfirm['message']['add_recipient_email']).each(function(index) {
							$(".EditRecipientEmails input:nth-child("+ index +")").addClass('error');
						});
					}
				} 

				if(dataofconfirm['message']['add_subject']){
					Command: toastr["error"]('Subject is required');
				}

				if(dataofconfirm['message']['add_text']){
					Command: toastr["error"]('Text is required');
				}

				if(dataofconfirm['message']['add_project_list']){
					Command: toastr["error"]('Please select a project to report!');
					$('.edit_selected_projects_id').parent().find('.bootstrap-select').addClass('error');
				} 
				
				if(dataofconfirm['message']['full_report_options']){
					Command: toastr["error"](dataofconfirm['message']['full_report_options']);
					$('.full-report-add-day-input').parent().find('.bootstrap-select').addClass('error');
					$('.full-report-add-rotation-input').parent().find('.bootstrap-select').addClass('error');
				}

				if(dataofconfirm['message']['keyword_report_options']){
					Command: toastr["error"](dataofconfirm['message']['keyword_report_options']);
					$('.keyword-report-add-day-input').parent().find('.bootstrap-select').addClass('error');
					$('.keyword-report-add-rotation-input').parent().find('.bootstrap-select').addClass('error');
				}

				if(dataofconfirm['message']['report_options']){
					Command: toastr["error"](dataofconfirm['message']['report_options']);
					$('#Keywords_report_edit').parent().addClass('error');
					$('#full_report_edit').parent().addClass('error');
				}

			}

			if(dataofconfirm['status'] === 1){
				$(".uk-close").trigger("click");
				ScheduleReportTable();
				Command: toastr["success"](dataofconfirm['message']);
			}

			if(dataofconfirm['status'] === 2){
				$("#confirmation-schedule-report").addClass('uk-flex uk-open');
				$('.confirmation-text').text(dataofconfirm['message']);
				$('.delete_existing_report').attr({
					'delete-report-id': dataofconfirm['delete_report_id'],
					'report-id': dataofconfirm['report_id']
				});
			}

			$('#update-schedule-report').removeAttr('disabled','disabled');

			setTimeout(function(){
				$('.scheduleReport-progress-loader').addClass('complete');
				setTimeout(function(){
					$('.edit-scheduleReport-progress-loader').css('display','none');
					$('.edit-scheduleReport-progress-loader').removeClass('complete');
					$('#schedule-report-edit div.uk-modal-body').removeClass('uk-overflow-hidden');
				}, 500);
			}, 500);
		}	        
	});
});

$(document).on('click','.delete_existing_report',function(){
	var delete_report_id = $(this).attr('delete-report-id');
	var report_id = $(this).attr('report-id');
	var summary = tinymce.get("edit_text").getContent();
	var myform = document.getElementById("edit-schedule-report-form");
	var request_data = new FormData(myform);
	request_data.append("add_text", summary);
	request_data.append("report_id", report_id);
	request_data.append("delete_report_id", delete_report_id);
	$.ajax({
		url: BASE_URL+'/ajax_update_existing_reports',
		data: request_data,
		cache: false,
		processData: false,
		contentType: false,
		type: 'POST',
		success:function(dataofconfirm){
			if(dataofconfirm['status'] === 1){
				$("#confirmation-schedule-report .uk-close").trigger("click");
				$("#schedule-report-edit .uk-close").trigger("click");
				ScheduleReportTable();
				Command: toastr["success"](dataofconfirm['message']);
			}

			if(dataofconfirm['status'] === 0){
				Command: toastr["error"](dataofconfirm['message']);
			}
		}
	});
});


$(document).on('keyup','.schedule-report-search',function (e) {
	if(e.which === 13) {
		e.preventDefault();
		return false;
	}
	$('#refresh-scheduleReport-search').css('display','block');
});


$(document).on('keyup','.schedule-report-search',delay(function (e) {
	if(e.which === 13) {
		e.preventDefault();
		return false;
	}
	if($('.schedule-report-search').val() != '' || $('.schedule-report-search').val() != null){
		$('.scheduleReport-search-clear').css('display','block');
		ScheduleReportTable();
	}
	if($('.schedule-report-search').val() == '' || $('.schedule-report-search').val() == null){
		$('.scheduleReport-search-clear').css('display','none');
	}
	$('#refresh-scheduleReport-search').css('display','none');
}, 1000));

$(document).on('click','.scheduleReport-search-clear',function(e){
	e.preventDefault();
	$('.schedule-report-search').val('');
	if($('.schedule-report-search').val() == '' || $('.schedule-report-search').val() == null){
		$('.scheduleReport-search-clear').css('display','none');
		ScheduleReportTable();
	} 
	$('#refresh-scheduleReport-search').css('display','none');
});

$(document).on('change','#ScheduleReport-limit',function(e){
	e.preventDefault();
	$('#schedule_report_table tr td').addClass('ajax-loader');
	$('.schedulereport ').addClass('ajax-loader');
	var limit = $(this).val();
	$('#limit_scheduleReport').val(limit);
	var page = 1;
	var search = $('.schedule-report-search').val();
	var column_name =  $('#column_name_scheduleReport').val();
	var order_type = $('#sort_type_scheduleReport').val();

	ScheduleReportList(column_name, order_type, limit, page,search);
});

$(document).on('click','.scheduleReport_sorting',function(e){
	e.preventDefault();
	var column_name = $(this).attr('data-column_name');
	var order_type = $(this).attr('data-sorting_type');

	$('#schedule_report_table tr td').addClass('ajax-loader');
	$('.schedulereport ').addClass('ajax-loader');

	if(order_type == 'asc')
	{
		$(this).attr('data-sorting_type', 'desc');
		reverse_order = 'desc';
		$('.asc').removeClass('asc');
		$('.desc').removeClass('desc');
		$(this).addClass('desc');
	}


	if(order_type == 'desc')
	{
		$(this).attr('data-sorting_type', 'asc');
		reverse_order = 'asc';
		$('.desc').removeClass('desc');
		$('.asc').removeClass('asc');
		$(this).addClass('asc');

	}

	$('#column_name_scheduleReport').val(column_name);
	$('#sort_type_scheduleReport').val(reverse_order);
	var limit = $('#limit_scheduleReport').val();
	var page = $('#page_scheduleReport').val();
	var search = $('.schedule-report-search').val();

	ScheduleReportList(column_name, order_type, limit, page,search);
});

$(document).on('click', '.schedulereport a', function(e) {
	e.preventDefault();

	$('li').removeClass('active');
	$(this).parent().addClass('active');
	var page = $(this).attr('href').split('page=')[1];
	$('#schedule_report_table tr td').addClass('ajax-loader');
	$('.schedulereport ').addClass('ajax-loader');

	var column_name = $('#column_name_scheduleReport').val();
	var order_type = $('#sort_type_scheduleReport').val();
	var limit = $('#limit_scheduleReport').val();
	var search = $('.schedule-report-search').val();

	$('#page_scheduleReport').val(page);
	ScheduleReportList(column_name, order_type, limit, page,search);
});


/*oct29*/
$(document).on('change','#full_report',function(e){
	e.preventDefault();
	if($(this).is(':checked') == true){
		$('#full_report_frequency').slideToggle();
		$('#full_report,#Keywords_report').parent().removeClass('error');

		if ($('.full-report-add-rotation-input').val() === '' && $('.full-report-add-day-input').val() === '') {
			$('.full-report-add-rotation-input').parent().find('.bootstrap-select').addClass('error');
		}else{
			$('.full-report-add-rotation-input').parent().find('.bootstrap-select').removeClass('error');
		}

		if ($('.full-report-add-day-input').val() === '' && $('.full-report-add-rotation-input').val() === '') {
			$('.full-report-add-day-input').parent().find('.bootstrap-select').addClass('error');
		}else{
			$('.full-report-add-day-input').parent().find('.bootstrap-select').removeClass('error');
		}	
	}else if($(this).is(':checked') == false){
		$('#full_report_frequency').slideToggle();
		$('.full-report-add-rotation-input').parent().find('.bootstrap-select').removeClass('error');
		$('.full-report-add-day-input').parent().find('.bootstrap-select').removeClass('error');
	}
});

$(document).on('change','#Keywords_report',function(e){
	e.preventDefault();
	if($(this).is(':checked') == true){
		$('#keyword_report_frequency').slideToggle();
		$('#full_report,#Keywords_report').parent().removeClass('error');

		if ($('.keyword-report-add-rotation-input').val() === '' && $('.keyword-report-add-day-input').val() === '') {
			$('.keyword-report-add-rotation-input').parent().find('.bootstrap-select').addClass('error');
		}else{
			$('.keyword-report-add-rotation-input').parent().find('.bootstrap-select').removeClass('error');
		}

		if ($('.keyword-report-add-day-input').val() === '' && $('.keyword-report-add-rotation-input').val() === '') {
			$('.keyword-report-add-day-input').parent().find('.bootstrap-select').addClass('error');
		}else{
			$('.keyword-report-add-day-input').parent().find('.bootstrap-select').removeClass('error');
		}	
	}else if($(this).is(':checked') == false){
		$('#keyword_report_frequency').slideToggle();
		$('.keyword-report-add-rotation-input').parent().find('.bootstrap-select').removeClass('error');
		$('.keyword-report-add-day-input').parent().find('.bootstrap-select').removeClass('error');
	}
});


$('.full-add-rotation').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {	

	var options  = $('.full-add-rotation option:selected');
	var selected = [];
	$(options).each(function(){
		selected.push($(this).val()); 
	});
	$('.full-report-add-rotation-input').val(selected);
	if (selected === '') {
		$('.full-report-add-rotation-input').parent().find('.bootstrap-select').addClass('error');
		$('.full-report-add-day-input').parent().find('.bootstrap-select').addClass('error');
	}else{
		$('.full-report-add-rotation-input').parent().find('.bootstrap-select').removeClass('error');
		$('.full-report-add-day-input').parent().find('.bootstrap-select').removeClass('error');
	}
});


$('.full-add-day').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {	

	var options  = $('.full-add-day option:selected');
	var selected = [];
	$(options).each(function(){
		selected.push($(this).val()); 
	});
	$('.full-report-add-day-input').val(selected);
	if (selected === '') {
		$('.full-report-add-rotation-input').parent().find('.bootstrap-select').addClass('error');
		$('.full-report-add-day-input').parent().find('.bootstrap-select').addClass('error');
	}else{
		$('.full-report-add-rotation-input').parent().find('.bootstrap-select').removeClass('error');
		$('.full-report-add-day-input').parent().find('.bootstrap-select').removeClass('error');
	}
});


$('.keyword-add-rotation').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {	

	var options  = $('.keyword-add-rotation option:selected');
	var selected = [];
	$(options).each(function(){
		selected.push($(this).val()); 
	});
	$('.keyword-report-add-rotation-input').val(selected);
	if (selected === '') {
		$('.keyword-report-add-rotation-input').parent().find('.bootstrap-select').addClass('error');
		$('.keyword-report-add-day-input').parent().find('.bootstrap-select').addClass('error');
	}else{
		$('.keyword-report-add-rotation-input').parent().find('.bootstrap-select').removeClass('error');
		$('.keyword-report-add-day-input').parent().find('.bootstrap-select').removeClass('error');
	}
});


$('.keyword-add-day').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {	

	var options  = $('.keyword-add-day option:selected');
	var selected = [];
	$(options).each(function(){
		selected.push($(this).val()); 
	});
	$('.keyword-report-add-day-input').val(selected);
	if (selected === '') {
		$('.keyword-report-add-rotation-input').parent().find('.bootstrap-select').addClass('error');
		$('.keyword-report-add-day-input').parent().find('.bootstrap-select').addClass('error');
	}else{
		$('.keyword-report-add-rotation-input').parent().find('.bootstrap-select').removeClass('error');
		$('.keyword-report-add-day-input').parent().find('.bootstrap-select').removeClass('error');
	}
});


$(document).on('change','#full_report_edit',function(e){
	e.preventDefault();
	if($(this).is(':checked') == true){
		$('#full_report_frequency_edit').slideToggle();
		$('#full_report_edit,#Keywords_report_edit').parent().removeClass('error');

		if ($('.full-report-add-rotation-input-edit').val() === '' && $('.full-report-add-day-input-edit').val() === '') {
			$('.full-report-add-rotation-input-edit').parent().find('.bootstrap-select').addClass('error');
		}else{
			$('.full-report-add-rotation-input-edit').parent().find('.bootstrap-select').removeClass('error');
		}

		if ($('.full-report-add-day-input-edit').val() === '' && $('.full-report-add-rotation-input-edit').val() === '') {
			$('.full-report-add-day-input-edit').parent().find('.bootstrap-select').addClass('error');
		}else{
			$('.full-report-add-day-input-edit').parent().find('.bootstrap-select').removeClass('error');
		}	
	}else if($(this).is(':checked') == false){
		$('#full_report_frequency_edit').slideToggle();
		$('.full-report-add-rotation-input-edit').parent().find('.bootstrap-select').removeClass('error');
		$('.full-report-add-day-input-edit').parent().find('.bootstrap-select').removeClass('error');
	}
});

$(document).on('change','#Keywords_report_edit',function(e){
	e.preventDefault();
	if($(this).is(':checked') == true){
		$('#keyword_report_frequency_edit').slideToggle();
		$('#full_report_edit,#Keywords_report_edit').parent().removeClass('error');

		if ($('.keyword-report-add-rotation-input-edit').val() === '' && $('.keyword-report-add-day-input-edit').val() === '') {
			$('.keyword-report-add-rotation-input-edit').parent().find('.bootstrap-select').addClass('error');
		}else{
			$('.keyword-report-add-rotation-input-edit').parent().find('.bootstrap-select').removeClass('error');
		}

		if ($('.keyword-report-add-day-input-edit').val() === '' && $('.keyword-report-add-rotation-input-edit').val() === '') {
			$('.keyword-report-add-day-input-edit').parent().find('.bootstrap-select').addClass('error');
		}else{
			$('.keyword-report-add-day-input-edit').parent().find('.bootstrap-select').removeClass('error');
		}	
	}else if($(this).is(':checked') == false){
		$('#keyword_report_frequency_edit').slideToggle();
		$('.keyword-report-add-rotation-input-edit').parent().find('.bootstrap-select').removeClass('error');
		$('.keyword-report-add-day-input-edit').parent().find('.bootstrap-select').removeClass('error');
	}
});



$(document).on('change','.edit-full-add-rotation',function(){
	var options  = $('.edit-full-add-rotation option:selected');
	var selected = [];
	$(options).each(function(){
		selected.push($(this).val()); 
	});
	console.log(selected);

	$('.edit-full-report-add-rotation-input').val(selected);
	if (selected === '') {
		$('.edit-full-report-add-rotation-input').parent().find('.bootstrap-select').addClass('error');
		$('.edit-full-report-add-day-input').parent().find('.bootstrap-select').addClass('error');
	}else{
		$('.edit-full-report-add-rotation-input').parent().find('.bootstrap-select').removeClass('error');
		$('.edit-full-report-add-day-input').parent().find('.bootstrap-select').removeClass('error');
	}
});


$(document).on('change','.edit-full-add-day',function(){
	var options  = $('.edit-full-add-day option:selected');
	var selected = [];
	$(options).each(function(){
		selected.push($(this).val()); 
	});
	$('.edit-full-report-add-day-input').val(selected);
	console.log(selected);
	if (selected === '') {
		$('.edit-full-report-add-rotation-input').parent().find('.bootstrap-select').addClass('error');
		$('.edit-full-report-add-day-input').parent().find('.bootstrap-select').addClass('error');
	}else{
		$('.edit-full-report-add-rotation-input').parent().find('.bootstrap-select').removeClass('error');
		$('.edit-full-report-add-day-input').parent().find('.bootstrap-select').removeClass('error');
	}
});


$(document).on('change','.edit-keyword-add-rotation',function(){
	var options  = $('.edit-keyword-add-rotation option:selected');
	var selected = [];
	$(options).each(function(){
		selected.push($(this).val()); 
	});
	$('.edit-keyword-report-add-rotation-input').val(selected);
	if (selected === '') {
		$('.edit-keyword-report-add-rotation-input').parent().find('.bootstrap-select').addClass('error');
		$('.edit-keyword-report-add-day-input').parent().find('.bootstrap-select').addClass('error');
	}else{
		$('.edit-keyword-report-add-rotation-input').parent().find('.bootstrap-select').removeClass('error');
		$('.edit-keyword-report-add-day-input').parent().find('.bootstrap-select').removeClass('error');
	}
});


$(document).on('change','.edit-keyword-add-day',function(){
	var options  = $('.edit-keyword-add-day option:selected');
	var selected = [];
	$(options).each(function(){
		selected.push($(this).val()); 
	});
	$('.edit-keyword-report-add-day-input').val(selected);
	if (selected === '') {
		$('.edit-keyword-report-add-rotation-input').parent().find('.bootstrap-select').addClass('error');
		$('.edit-keyword-report-add-day-input').parent().find('.bootstrap-select').addClass('error');
	}else{
		$('.edit-keyword-report-add-rotation-input').parent().find('.bootstrap-select').removeClass('error');
		$('.edit-keyword-report-add-day-input').parent().find('.bootstrap-select').removeClass('error');
	}
});