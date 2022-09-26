$(document).on('click', function(event) {
	if (event.target.id !== "archived_campaign_search") {
		$("#archived-filter-search-form").removeClass("open");
	}
});

if($('.archived_campaign_search').val() == ''){	
	$('.project-search.style2 input.archived_campaign_search').focus(function () {
		$(this).parent().addClass('open');
	});
}

$(document).on('click','.archived_sorting',function(e){
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
	
	$('#hidden_column_name').val(column_name);
	$('#hidden_sort_type').val(reverse_order);
	var page = $('#hidden_page').val();
	var query = $('#archived_campaign_search').val();
	var limit = $('#limit').val();
	var query_type = $('.archived-selected-filter-text').text();

	fetch_campaign_data(page,limit, query,column_name,reverse_order,query_type);
});


function fetch_campaign_data(page,limit,query,column_name,order_type,query_type){
	$('#archived-campaign-list tbody tr td').addClass('ajax-loader');
	$('.project-entries').addClass('ajax-loader');
	$('.archived-pagination').addClass('ajax-loader');
	$.ajax({
		url:BASE_URL +'/ajax_fetch_archived_campaign_data',
		type:'GET',
		data:{page,limit,query,column_name,order_type,query_type},
		success:function(response){
			$('#archived-campaign-list tbody').html('');
			$('#archived-campaign-list tbody').html(response);
			$('.ajax-loader').removeClass('ajax-loader');
			$('#refresh-archived-search').css('display','none');
		}
	});

	$.ajax({
		url:BASE_URL +'/ajax_fetch_archived_campaign_pagination',
		type:'GET',
		data:{page,limit,query,column_name,order_type,query_type},
		success:function(response){
			$('.archived-project-table-foot').html(response);
			$('.project-entries').removeClass('ajax-loader');
			$('.archived-pagination').removeClass('ajax-loader');
		}
	});


}


$(document).on('click','.delete_archived_project',function(e){
	e.preventDefault();
	if (!confirm("Are you sure you want to delete?")) {
		return false;
	} 

	var request_id = $(this).attr('data-id');
	if(request_id !=''){
		$.ajax({
			type:'POST',
			url:BASE_URL +'/ajax_delete_archived_project',
			data:{request_id:request_id,_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if(response['status'] == 1){
					Command: toastr["success"](response['message']);
					setTimeout(function () { 
						var page = $('#hidden_page').val();
						var limit = $('#limit').val();
						var query = $('#archived_campaign_search').val();
						var column_name = $('#hidden_column_name').val();
						var order_type = $('#hidden_sort_type').val();
						var query_type = $('.archived-selected-filter-text').text();
						fetch_campaign_data(page,limit, query,column_name,order_type,query_type);
						//$("#defaultCampaignList").load(location.href+" #defaultCampaignList>*","");
						sidebar_section($('.campaign_id').val());
					}, 5000); 
					return false;
				}else if(response['status'] == 0){
					Command: toastr["error"](response['message']);
					return false;
				}else{
					Command: toastr["error"]('Error!! Please try again.');
					return false;
				}
			}
		});
	}
});


$(document).on('click','.restore_row',function(e){
	e.preventDefault();

	var request_id = $(this).attr('data-id');

	if(request_id !=''){
		$.ajax({
			type:'POST',
			url:BASE_URL +'/ajax_restore_archived_project',
			data:{request_id:request_id,_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if(response['status'] == 1){
					Command: toastr["success"](response['message']);
					setTimeout(function () { 
						var page = $('#hidden_page').val();
						var limit = $('#limit').val();
						var query = $('#archived_campaign_search').val();
						var column_name = $('#hidden_column_name').val();
						var order_type = $('#hidden_sort_type').val();
						var query_type = $('.archived-selected-filter-text').text();
						fetch_campaign_data(page,limit, query,column_name,order_type,query_type);
						//$("#defaultCampaignList").load(location.href+" #defaultCampaignList>*","");
						sidebar_section($('.campaign_id').val());
					}, 2000); 
					return false;
				}else if(response['status'] == 0){
					Command: toastr["error"](response['message']);
					return false;
				}else if(response['status'] == 2){
					Command: toastr["error"](response['message']);
					return false;
				}else{
					Command: toastr["error"]('Error!! Please try again.');
					return false;
				}
			}
		});
	}
});

$('#archived-campaign-list #checkAllArchived').click(function() {
	if ($("#archived-campaign-list #checkAllArchived").is(':checked')) {
		$('#archived-campaign-list input[type=checkbox]').prop('checked', true);
	} else {
		$('#archived-campaign-list input[type=checkbox]').prop('checked', false);
	}
});


$('#archived-campaign-list input[type=checkbox]').on('change',function(){
	if($('.selected_archived_campaigns:checked').length == $('.selected_archived_campaigns').length){
		$('#checkAllArchived').prop('checked',true);
	}else{
		$('#checkAllArchived').prop('checked',false);
	}
});


$(document).on('click','.archived_delete_campaign',function(e){
	e.preventDefault();

	var page = $('#hidden_page').val();
	var limit = $('#limit').val();
	var query = $('#archived_campaign_search').val();
	var column_name = $('#hidden_column_name').val();
	var order_type = $('#hidden_sort_type').val();
	var query_type = $('.archived-selected-filter-text').text();

	var checked =[];

	$("input[name='selected_archived_campaigns[]']:checked").each(function () {
		checked.push(parseInt($(this).val()));
	});
	// console.log(checked);

	if(checked.length == 0 ){
		Command: toastr["error"]('Please select Campaigns to delete permanently.');
		return false;
	}


	if (!confirm("Are you sure you want to delete permanently ?")) {
		return false;
	} 



	if(checked.length > 0){
		$.ajax({
			type: "POST",
			url: BASE_URL + '/ajax_delete_campaigns',
			data: {checked:checked,_token:$('meta[name="csrf-token"]').attr('content')},
			dataType: 'json',
			success: function(result) {
				if(result.status == 1){
					fetch_campaign_data(page,limit, query,column_name,order_type,query_type);
					//$("#defaultCampaignList").load(location.href+" #defaultCampaignList>*","");
					sidebar_section($('.campaign_id').val());
					Command: toastr["success"](result['message']);
				} 
				if(result.status == 0){
					Command: toastr["warning"](result['message']);
				}

			}
		});
	}
});



$(document).on('click','.restore_archived_campaigns',function(e){
	e.preventDefault();

	var page = $('#hidden_page').val();
	var limit = $('#limit').val();
	var query = $('#archived_campaign_search').val();
	var column_name = $('#hidden_column_name').val();
	var order_type = $('#hidden_sort_type').val();
	var query_type = $('.archived-selected-filter-text').text();

	var checked =[];

	$("input[name='selected_archived_campaigns[]']:checked").each(function () {
		checked.push(parseInt($(this).val()));
	});
	// console.log(checked);

	if(checked.length == 0 ){
		Command: toastr["error"]('Please select Campaigns to restore.');
		return false;
	}


	if (!confirm("Are you sure you want to restore ?")) {
		return false;
	} 



	if(checked.length > 0){
		$.ajax({
			type: "POST",
			url: BASE_URL + '/ajax_restore_campaigns',
			data: {checked:checked,_token:$('meta[name="csrf-token"]').attr('content')},
			dataType: 'json',
			success: function(result) {
				if(result.status == 1){
					fetch_campaign_data(page,limit, query,column_name,order_type,query_type);
					//$("#defaultCampaignList").load(location.href+" #defaultCampaignList>*","");
					sidebar_section($('.campaign_id').val());
					Command: toastr["success"](result['message']);
				} 

				if(result.status == 2){
					Command: toastr["error"](result['message']);
					return false;
				} 
				if(result.status == 0){
					Command: toastr["warning"](result['message']);
				}

			}
		});
	}
});


$(document).on('change','.ArchivedCampaignsToList',function(e){
	e.preventDefault();
	
	var limit = $(this).val();
	var page = 1;
	var query = $('#archived_campaign_search').val();

	var column_name = $('#hidden_column_name').val();
	var order_type = $('#hidden_sort_type').val();
	var query_type = $('.archived-selected-filter-text').text();

	fetch_campaign_data(page,limit,query,column_name,order_type,query_type);

});


//search functionality
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

$(document).on('keyup','#archived_campaign_search',function (e) {
	$('#refresh-archived-search').css('display','block');
});

$('#archived_campaign_search').keyup(delay(function (e) {
	if($('#archived_campaign_search').val() != '' || $('#archived_campaign_search').val() != null){
	   $('.archived-search-clear').css('display','block');
	}
  ajax_campaignlist($(this).val());
}, 1000));

function ajax_campaignlist(query){
	var page = 1;
	var limit = $('#limit').val();
	var column_name = $('#hidden_column_name').val();
	var order_type = $('#hidden_sort_type').val();
	var query_type = $('.archived-selected-filter-text').text();

	

	if(query != ''){
		$('.archived-search-filter').css('display','none');
		if($('#archived-filter-search-form').hasClass('open')){
			$('#archived-filter-search-form').removeClass('open');
		}
	}else{
		$('.archived-search-filter').css('display','block');
		$('#archived-filter-search-form').addClass('open');
	}
	fetch_campaign_data(page,limit, query,column_name,order_type,query_type);
}

$(document).on('click','.ArchivedCampaignsClear',function(e){
	e.preventDefault();
	$('#archived_campaign_search').val('');
	if($('#archived_campaign_search').val() == '' || $('#archived_campaign_search').val() == null){
		$('.archived-search-clear').css('display','none');
	   ajax_campaignlist($('#archived_campaign_search').val());
	}
});




$(document).ready(function(){
	var page = $('#hidden_page').val();
	var limit = $('#limit').val();
	var query = $('#archived_campaign_search').val();
	var column_name = $('#hidden_column_name').val();
	var order_type = $('#hidden_sort_type').val();
	var query_type = $('.archived-selected-filter-text').text();
	
	fetch_campaign_data(page,limit,query,column_name,order_type,query_type);
});


$(document).on('click','.archived-pagination a',function(e){
	e.preventDefault();
	$('li').removeClass('active');
	$(this).parent().addClass('active');

	var page = $(this).attr('href').split('page=')[1];
	$('#hidden_page').val(page);

	var limit = $('#limit').val();
	var query = $('#archived_campaign_search').val();
	var column_name = $('#hidden_column_name').val();
	var order_type = $('#hidden_sort_type').val();
	var query_type = $('.archived-selected-filter-text').text();
	
	fetch_campaign_data(page,limit,query,column_name,order_type,query_type);
	$('html,body').animate({
		scrollTop: $("#archived-campaign-list").offset().top
	},'slow');

});

$(document).on('click','.search-filter-list',function(e){
	e.preventDefault();
	var selected = $(this).find('span').text();
	$('.archived-selected-filter-text').text(selected);
	$('#archived-filter-search-form').removeClass('open');
	e.stopPropagation();
});