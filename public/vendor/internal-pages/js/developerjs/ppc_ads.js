var BASE_URL = $('.base_url').val();

function ads_list(account_id,response){

	var column_name = $('#hidden_ads_column_name').val();
	var order_type = $('#hidden_ads_sort_type').val();
	var limit =  $('#hidden_ads_limit').val();
	var query = $('.ads-search').val();
	var page = $('#hidden_ads_page').val();
	var start_date = $('.start_date').val();
	var end_date = $('.end_date').val();
	var campaign_id = $('.campaign_id').val();
	
	fetch_ads_list(account_id,column_name,order_type,limit,query,page,start_date,end_date,campaign_id,response);
}

function fetch_ads_list(account_id,column_name,order_type,limit,query,page,start_date,end_date,campaign_id,response = null){
	
	$('#ads-list tr td').addClass('ajax-loader');

	if(response !== null){
		var compare = response['compare'];
		var endDate = response['endDate'];
		var preEndDate = response['preEndDate'];
		var preStartDate = response['preStartDate'];
		var startDate = response['startDate'];
		var duration = response['duration'];
	}else{
		var compare = null;
		var endDate = null;
		var preEndDate = null;
		var preStartDate = null;
		var startDate = null;
		var duration = null;
	}

	$.ajax({
		// url:BASE_URL +'/ajax_fetch_ads_data',
		url:BASE_URL +'/ppc/ads/list',
		type:'GET',
		data:{account_id,column_name,order_type,limit,query,page,start_date,end_date,campaign_id,compare, endDate, preEndDate, preStartDate, startDate , duration},
		success:function(response){
			$('#ads-list tbody').html('');
   			$('#ads-list tbody').html(response);

   			$('#ads-list tr th').removeClass('ajax-loader');
   			$('#ads-list tr td').removeClass('ajax-loader');
   			$('#refresh-ads-search').css('display','none');
		}
	});

	$.ajax({
		// url:BASE_URL +'/ajax_fetch_ads_pagination',
		url:BASE_URL +'/ppc/ads/pagination',
		type:'GET',
		data:{account_id,column_name,order_type,limit,query,page,start_date,end_date,campaign_id,compare, endDate, preEndDate, preStartDate, startDate , duration},
		success:function(response){
   			$('#ads-foot').html('');
   			$('#ads-foot').html(response);
		}
	});
}

$(document).on('click','.ads_sorting',function(e){
	e.preventDefault();
	var column_name = $(this).attr('data-column_name');
  	var order_type = $(this).attr('data-sorting_type');


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
	 

	$('#hidden_ads_column_name').val(column_name);
	$('#hidden_ads_sort_type').val(reverse_order);
	
	var limit = $('#hidden_ads_limit').val();
	var account_id = $('.account_id').val();
	var page = $('#hidden_ads_page').val();
	var query = $('.ads-search').val();
	var start_date = $('.start_date').val();
	var end_date = $('.end_date').val();
	var campaign_id = $('.campaign_id').val();

	var value = $('.adwords_list.active').attr('data-value');
	var key = $('#encriptkey').val();
	var compare = false;
	if ($('.adwords_compare').prop("checked") == true) {
		var compare = true;
	}

	$.ajax({
        type: "GET",
        url: BASE_URL + "/ajax_fetch_ppc_summary_statistics",
        data: {value,campaign_id,compare,account_id,key},
        dataType: 'json',
        success: function(response) {
        	fetch_ads_list(account_id,column_name,reverse_order,limit,query,page,start_date,end_date,campaign_id,response);
        	
        }
  });

});

$(document).on('change','#ads-limit',function(event){
	event.preventDefault();

	var limit = $(this).val();
	$('#hidden_ads_limit').val(limit);
	var column_name = 	$('#hidden_ads_column_name').val();
	var order_type = $('#hidden_ads_sort_type').val();
	var account_id = $('.account_id').val();
	var page = $('#hidden_ads_page').val(1);
	var query = $('.ads-search').val();
	var start_date = $('.start_date').val();
	var end_date = $('.end_date').val();
	var campaign_id = $('.campaign_id').val();

	var value = $('.adwords_list.active').attr('data-value');
	var key = $('#encriptkey').val();
	var compare = false;
	if ($('.adwords_compare').prop("checked") == true) {
		var compare = true;
	}

	$.ajax({
        type: "GET",
        url: BASE_URL + "/ajax_fetch_ppc_summary_statistics",
        data: {value,campaign_id,compare,account_id,key},
        dataType: 'json',
        success: function(response) {
        	fetch_ads_list(account_id,column_name,order_type,limit,query,1,start_date,end_date,campaign_id,response);
        	
        }
  });

	
});

$(document).on('keyup','.ads-search',function(e){
	e.preventDefault();
	if($('.ads-search').val() !== ''){
		$('#refresh-ads-search,.ads-search-clear').css('display','block');
	}else{
		$('#refresh-ads-search,.ads-search-clear').css('display','none');
	}

	var limit = 20;
	$('#hidden_ads_limit').val(limit);
	$('select[id^="ads-limit"] option[value="'+limit+'"]').attr("selected","selected");

	var column_name = 	$('#hidden_ads_column_name').val();
	var order_type = $('#hidden_ads_sort_type').val();
	var account_id = $('.account_id').val();
	var page = 1; 
	$('#hidden_ads_page').val(page);
	var query = $(this).val();
	var start_date = $('.start_date').val();
	var end_date = $('.end_date').val();
	var campaign_id = $('.campaign_id').val();
	
	fetch_ads_list(account_id,column_name,order_type,limit,query,page,start_date,end_date,campaign_id);
});

$(document).on('click','.ads_pagination a',function(e){
	e.preventDefault();

	$('li').removeClass('active');
	$(this).parent().addClass('active');

	var page = $(this).attr('href').split('page=')[1];

	$('#hidden_ads_page').val(page);

	var limit = $('#hidden_ads_limit').val();
	var column_name = 	$('#hidden_ads_column_name').val();
	var order_type = $('#hidden_ads_sort_type').val();
	var account_id = $('.account_id').val();
	var query = $('.ads-search').val();
	var start_date = $('.start_date').val();
	var end_date = $('.end_date').val();
	var campaign_id = $('.campaign_id').val();

	var value = $('.adwords_list.active').attr('data-value');
	var key = $('#encriptkey').val();
	var compare = false;
	if ($('.adwords_compare').prop("checked") == true) {
		var compare = true;
	}

	$.ajax({
        type: "GET",
        url: BASE_URL + "/ajax_fetch_ppc_summary_statistics",
        data: {value,campaign_id,compare,account_id,key},
        dataType: 'json',
        success: function(response) {
        	fetch_ads_list(account_id,column_name,order_type,limit,query,page,start_date,end_date,campaign_id,response);
        	
        }
  });

	
});



$(document).on('click','.adsListClear',function(e){
  e.preventDefault();
  $('.ads-search').val('');
  if($('.ads-search').val() == '' || $('.ads-search').val() == null){
    $('.ads-search-clear').css('display','none');
   
	var limit = $('#hidden_ads_limit').val();
	$('select[id^="ads-limit"] option[value="'+limit+'"]').attr("selected","selected");
	var column_name = 	$('#hidden_ads_column_name').val();
	var order_type = $('#hidden_ads_sort_type').val();
	var account_id = $('.account_id').val();
	var page = $('#hidden_ads_page').val();
	var query = $('.ads-list-search').val();
	var start_date = $('.start_date').val();
	var end_date = $('.end_date').val();
	var campaign_id = $('.campaign_id').val();
	
	var value = $('.adwords_list.active').attr('data-value');
	var key = $('#encriptkey').val();
	var compare = false;
	if ($('.adwords_compare').prop("checked") == true) {
		var compare = true;
	}

	$.ajax({
        type: "GET",
        url: BASE_URL + "/ajax_fetch_ppc_summary_statistics",
        data: {value,campaign_id,compare,account_id,key},
        dataType: 'json',
        success: function(response) {
        	fetch_ads_list(account_id,column_name,order_type,limit,query,page,start_date,end_date,campaign_id,response);
        	
        }
  });

	
  }
});