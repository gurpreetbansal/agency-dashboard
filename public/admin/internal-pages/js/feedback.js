var BASE_URL = $('.base_url').val();
$(document).ready(function(){
	loadDefaultTable();
});

function loadDefaultTable(){
	var page = $('#feedback_hidden_page').val();
	var limit = $('#feedback_limit').val();
	var query = $('.feedback-list-search').val();
	
	fetch_feedback_data(page,limit,query);
}

function fetch_feedback_data(page,limit,query){
	$('#feedback-error-list tr td').addClass('ajax-loader');
	$('.project-entries').addClass('ajax-loader');
	$('.pagination').addClass('ajax-loader');
	$.ajax({
		url:BASE_URL+'/admin/ajax_fetch_feedback_data',
		type:'GET',
		data:{page,limit,query},
		success:function(response){
			$('#feedback-error-list tbody').html(response);
			$('.ajax-loader').removeClass('ajax-loader');
			$('#admin-feedback-refresh-search').css('display','none');
		}
	});


	$.ajax({
		url:BASE_URL +'/admin/ajax_fetch_feedback_pagination',
		type:'GET',
		data:{page,limit,query},
		success:function(response){
			$('.project-table-foot').html(response);
			$('.project-entries').removeClass('ajax-loader');
			$('.pagination').removeClass('ajax-loader');
		}
	});
}

function delay(callback, ms) {
	var timer = 0;
	return function() {
		var context = this, args = arguments;
		clearTimeout(timer);
		timer = setTimeout(function () {
			callback.apply(context, args);
		}, ms || 0);
	};
}

$(document).on('keyup','.feedback-list-search',function (e) {
	$('#admin-feedback-refresh-search').css('display','block');
});

$(document).on('keyup','.feedback-list-search',delay(function(e){
	e.preventDefault();
	if($('#feedback_list_search').val() != '' || $('#feedback_list_search').val() != null){
		$('.admin-feedback-list-clear').css('display','block');
	}
	fetch_feedback_data(1,$('#feedback_limit').val(),$(this).val());
},1000));

$(document).on('click','.FeedbackListClear',function(e){
	e.preventDefault();
	$('.feedback-list-search').val('');
	if($('#feedback_list_search').val() == '' || $('#feedback_list_search').val() == null){
		$('.admin-feedback-list-clear').css('display','none');
		var page = 1;
		var limit = $('#feedback_limit').val();
		var query = $('.feedback-list-search').val();
		fetch_feedback_data(page,limit,query);
	}
});

$(document).on('click','.admin-feedback-list a',function(e){
	e.preventDefault();
	$('li').removeClass('active');
	$(this).parent().addClass('active');

	var page = $(this).attr('href').split('page=')[1];
	$('#feedback_hidden_page').val(page);
	var limit = $('#feedback_limit').val();
	var query = $('.feedback-list-search').val();
	fetch_feedback_data(page,limit,query);
	$('html,body').animate({
		scrollTop: $("#feedback-error-list").offset().top
	},'slow');

});

$(document).on('change','#admin_feedback_list',function(){
	var page = 1;
	var limit = $(this).val();
	$('#feedback_limit').val($(this).val());
	var query = $('.feedback-list-search').val();
	fetch_feedback_data(page,limit,query);
});

$(document).on('click','.show-client_feedback',function(){
	var feedback_id = $(this).attr('data-id');
	$("#clientFeedbackForm").load('/admin/ajax_fetch_client_feedback/' + feedback_id, function(responseTxt, statusTxt, xhr){
	});
});	

$(document).on('click','#cancel-feedback-button',function(){
	$('#close-feedback-button').trigger('click');
});