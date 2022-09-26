var BASE_URL = $('.base_url').val();

function ads_campaign_list(account_id,response){

	var column_name = $('#hidden_campaign_column_name').val();
	var order_type = $('#hidden_campaign_sort_type').val();
	var limit =  $('#hidden_campaign_limit').val();
	var query = $('.campaign_list_search').val();
	var page = $('#hidden_campaign_page').val();
	var start_date = $('.start_date').val();
	var end_date = $('.end_date').val();
	var campaign_id = $('.campaign_id').val();
	fetch_campaign_list(account_id,column_name,order_type,limit,query,page,start_date,end_date,campaign_id,response);
}

function fetch_campaign_list(account_id,column_name,order_type,limit,query,page,start_date,end_date,campaign_id,response = null){

	$('#ads-campaign-list tr td').addClass('ajax-loader');
	
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
		url:BASE_URL +'/ppc/campaign/list',
		type:'GET',
		data:{account_id,column_name,order_type,limit,query,page,start_date,end_date,campaign_id,compare, endDate, preEndDate, preStartDate, startDate , duration},
		success:function(response){
			$('#ads-campaign-list tbody').html('');
   			$('#ads-campaign-list tbody').html(response);
   			
   			$('#ads-campaign-list tr th').removeClass('ajax-loader');
   			$('#ads-campaign-list tr td').removeClass('ajax-loader');
   			$('#refresh-campaignlist-search').css('display','none');
		}
	});

	$.ajax({
		url:BASE_URL +'/ppc/campaigns/pagination',
		type:'GET',
		data:{account_id,column_name,order_type,limit,query,page,start_date,end_date,campaign_id,compare, endDate, preEndDate, preStartDate, startDate , duration},
		success:function(response){
   			$('#AdsCampaignList_foot').html('');
   			$('#AdsCampaignList_foot').html(response);
		}
	});
}

$(document).on('click','.campaign_list_sorting',function(e){
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
	 

	$('#hidden_campaign_column_name').val(column_name);
	$('#hidden_campaign_sort_type').val(reverse_order);
	
	var limit = $('#hidden_campaign_limit').val();
	var account_id = $('.account_id').val();
	var page = $('#hidden_campaign_page').val();
	var query = $('.campaign_list_search').val();
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
        	fetch_campaign_list(account_id,column_name,reverse_order,limit,query,page,start_date,end_date,campaign_id,response);
        }
  });
	// var requestArr = {compare:'', endDate:'', preEndDate:'', preStartDate:'', startDate:'' , duration:duration};
	// console.log(requestArr);
	

});

$(document).on('change','#ppc_campaign_limit',function(e){
	e.preventDefault();

	var limit = $(this).val();

	var column_name = 	$('#hidden_campaign_column_name').val();
	var order_type = $('#hidden_campaign_sort_type').val();
	var account_id = $('.account_id').val();
	var page = 1;
	var query = $('.campaign_list_search').val();
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
        	fetch_campaign_list(account_id,column_name,order_type,limit,query,page,start_date,end_date,campaign_id,response);
        }
  });

	
});

$(document).on('keyup','.campaign_list_search',function(e){
	e.preventDefault();
	if($('.campaign_list_search').val() !== ''){
		$('#refresh-campaignlist-search,.campaignlist-search-clear').css('display','block');
	}else{
		$('#refresh-campaignlist-search,.campaignlist-search-clear').css('display','none');
	}

	var limit = $('#campaign_limit').val();
	$('select[id^="campaign_limit"] option[value="'+limit+'"]').attr("selected","selected");
	var column_name = 	$('#hidden_campaign_column_name').val();
	var order_type = $('#hidden_campaign_sort_type').val();
	var account_id = $('.account_id').val();
	var page = $('#hidden_campaign_page').val();
	var query = $(this).val();
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
        	fetch_campaign_list(account_id,column_name,order_type,limit,query,page,start_date,end_date,campaign_id,response);
        }
  });

	
});

$(document).on('click','.ads_campaign a',function(e){
	e.preventDefault();

	$('li').removeClass('active');
	$(this).parent().addClass('active');

	var page = $(this).attr('href').split('page=')[1];

	$('#hidden_campaign_page').val(page);

	var limit = $('#campaign_limit').val();
	var column_name = 	$('#hidden_campaign_column_name').val();
	var order_type = $('#hidden_campaign_sort_type').val();
	var account_id = $('.account_id').val();
	var query = $(this).val();
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
        	fetch_campaign_list(account_id,column_name,order_type,limit,query,page,start_date,end_date,campaign_id,response);
        }
  });
	
});





function ads_campaign_list_pdf(account_id){

	var column_name = $('#hidden_campaign_column_name').val();
	var order_type = $('#hidden_campaign_sort_type').val();
	var limit =  95;
	var query = $('.campaign_list_search').val();
	var page = $('#hidden_campaign_page').val();
	var start_date = $('.start_date').val();
	var end_date = $('.end_date').val();
	var campaign_id = $('.campaign_id').val();
	fetch_campaign_list_pdf(account_id,column_name,order_type,limit,query,page,start_date,end_date,campaign_id);
}

function fetch_campaign_list_pdf(account_id,column_name,order_type,limit,query,page,start_date,end_date,campaign_id){

	$('#ads-campaign-list tr td').addClass('ajax-loader');

	$.ajax({
		url:BASE_URL +'/ppc/campaign/list',
		type:'GET',
		data:{account_id,column_name,order_type,limit,query,page,start_date,end_date,campaign_id},
		success:function(response){
			$('#ads-campaign-list tbody').html('');
   			$('#ads-campaign-list tbody').html(response);
   			
   			$('#ads-campaign-list tr th').removeClass('ajax-loader');
   			$('#ads-campaign-list tr td').removeClass('ajax-loader');

		}
	});

	/*$.ajax({
		url:BASE_URL +'/ppc/campaigns/pagination',
		type:'GET',
		data:{account_id,column_name,order_type,limit,query,page,start_date,end_date,campaign_id},
		success:function(response){
   			$('#AdsCampaignList_foot').html('');
   			$('#AdsCampaignList_foot').html(response);
		}
	});*/
}


$(document).on('click','.campaignListClear',function(e){
  e.preventDefault();
  $('.campaign_list_search').val('');
  if($('.campaign_list_search').val() == '' || $('.campaign_list_search').val() == null){
    $('.campaignlist-search-clear').css('display','none');
    var limit = $('#campaign_limit').val();
	$('select[id^="campaign_limit"] option[value="'+limit+'"]').attr("selected","selected");
	var column_name = 	$('#hidden_campaign_column_name').val();
	var order_type = $('#hidden_campaign_sort_type').val();
	var account_id = $('.account_id').val();
	var page = $('#hidden_campaign_page').val();
	var query = $('.campaign_list_search').val();
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
        	fetch_campaign_list(account_id,column_name,order_type,limit,query,page,start_date,end_date,campaign_id,response);
        	
        }
  });
	
  }
});