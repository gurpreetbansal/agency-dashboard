var BASE_URL = $('.base_url').val();

$(document).ready(function(){
	loadDefaultTable();
});

function loadDefaultTable(){
	var page = $('#agency_account_details_hidden_page').val();
	var limit = $('#agency_account_details_limit').val();
	var query = $('.agency-account-details-list-search').val();
	var agency_id = $('#agency_id').val();
	var column_name = $('#hidden_agency_acct_column_name').val();
	var order_type = $('#hidden_agency_acct_sort_type').val();

	fetch_list_data(page,limit,query,$('#agency_id').val(), column_name, order_type);
}

function fetch_list_data(page,limit,query,agency_id, column_name, order_type){
	$('#agency-account-details-list tr td').addClass('ajax-loader');
	$('.project-entries').addClass('ajax-loader');
	$('.pagination').addClass('ajax-loader');
	$.ajax({
		url:BASE_URL+'/admin/ajax_fetch_agency_account_data',
		type:'GET',
		data:{page,limit,query,agency_id,column_name,order_type},
		success:function(response){
			$('#agency-account-details-list tbody').html(response);
			$('.ajax-loader').removeClass('ajax-loader');
			$('#agency-account-details-refresh-search').css('display','none');
		}
	});


	$.ajax({
		url:BASE_URL +'/admin/ajax_fetch_agency_account_pagination',
		type:'GET',
		data:{page,limit,query,agency_id,column_name,order_type},
		success:function(response){
			$('.project-table-foot').html(response);
			$('.project-entries').removeClass('ajax-loader');
			$('.pagination').removeClass('ajax-loader');
		}
	});
}


$(document).on('click','.admin-agency-account-details a',function(e){
	e.preventDefault();
	$('li').removeClass('active');
	$(this).parent().addClass('active');

	var page = $(this).attr('href').split('page=')[1];
	$('#agency_account_details_hidden_page').val(page);
	var limit = $('#agency_account_details_limit').val();
	var query = $('.agency-account-details-list-search').val();
	var column_name = $('#hidden_agency_acct_column_name').val();
	var order_type = $('#hidden_agency_acct_sort_type').val();

	fetch_list_data(page,limit,query,$('#agency_id').val(), column_name, order_type);
	$('html,body').animate({
		scrollTop: $("#agency-account-details-list").offset().top
	},'slow');

});

$(document).on('change','#admin_agency_account_details_limit',function(){
	var page = 1;
	var limit = $(this).val();
	$('#agency_account_details_limit').val($(this).val());
	var query = $('.agency-account-details-list-search').val();	
	var column_name = $('#hidden_agency_acct_column_name').val();
	var order_type = $('#hidden_agency_acct_sort_type').val();

	fetch_list_data(page,limit,query,$('#agency_id').val(), column_name, order_type);
});

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

$(document).on('keyup','.agency-account-details-list-search',function (e) {
	$('#agency-account-details-refresh-search').css('display','block');
});

$(document).on('keyup','.agency-account-details-list-search',delay(function(e){
	e.preventDefault();
	if($('#agency_account_details_list_search').val() != '' || $('#agency_account_details_list_search').val() != null){
		$('.agency-account-details-list-clear').css('display','block');
	}
	fetch_list_data(1,$('#agency_account_details_limit').val(),$(this).val(),$('#agency_id').val(),$('#hidden_agency_acct_column_name').val(),$('#hidden_agency_acct_sort_type').val());
},1000));

$(document).on('click','.AgencyAccountDetailsListClear',function(e){
	e.preventDefault();
	$('.agency-account-details-list-search').val('');
	if($('#agency_account_details_list_search').val() == '' || $('#agency_account_details_list_search').val() == null){
		$('.agency-account-details-list-clear').css('display','none');
		var page = 1;
		var limit = $('#agency_account_details_limitagency_account_details_limit').val();
		var query = $('.agency-account-details-list-search').val();
		var column_name = $('#hidden_agency_acct_column_name').val();
		var order_type = $('#hidden_agency_acct_sort_type').val();
		fetch_list_data(page,limit,query,$('#agency_id').val(),column_name,order_type);
	}
});


/*nov15*/
$(document).on('click', '.agency_acct_sorting', function (e) {
	e.preventDefault();
	var column_name = $(this).attr('data-column_name');
	var order_type = $(this).attr('data-sorting_type');


	if (order_type == 'asc') {
		$(this).attr('data-sorting_type', 'desc');
		reverse_order = 'desc';
		$('.asc').removeClass('asc');
		$('.desc').removeClass('desc');
		$(this).addClass('desc');
	}


	if (order_type == 'desc') {
		$(this).attr('data-sorting_type', 'asc');
		reverse_order = 'asc';
		$('.desc').removeClass('desc');
		$('.asc').removeClass('asc');
		$(this).addClass('asc');

	}

	$('#hidden_agency_acct_column_name').val(column_name);
	$('#hidden_agency_acct_sort_type').val(reverse_order);
	var page = $('#agency_account_details_hidden_page').val();
	var query = $('.agency-account-details-list-search').val();
	var limit = $('#agency_account_details_limit').val();
	var query_type = $('.selected-filter-text').text();

	fetch_list_data(page,limit,query,$('#agency_id').val(), column_name, order_type);
});