var BASE_URL = $('.base_url').val();
var APP_URL = $('.app_url').val();

$(document).on("click", '#ShareKey', function (e) {
	e.preventDefault();
	$(".share-key-popup").addClass("open");
	if($(this).attr('data-share-key') !== ''){
		var getValue = APP_URL+'project-detail/'+$(this).attr('data-share-key');
		$('.copy_share_key_value').val(getValue);
		$('.project-id').val($(this).attr('data-id'));
	}else{
		 var project_id = $(this).attr('data-id');
   		 reset_share_key(project_id);
	}
});

$(document).on("click",'.close-share-key', function () {
	$('.share-key-btn').val("Click to copy");
	$(".share-key-popup").removeClass("open");
});

new ClipboardJS('.btn.share-key-btn');

$(document).on("click", ".share-key-btn", function () {
	$(this).val("Copied!");
});


$(document).on('click','.archive_row',function(e){
	e.preventDefault();

	if (!confirm("Are you sure you want to archive this project ?")) {
		return false;
	} 

	var request_id = $(this).attr('data-id');
	
	if(request_id !=''){
		$.ajax({
			type:'POST',
			url:BASE_URL + '/ajax_archive_campaign',
			data:{request_id:request_id,_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if(response['status'] == 1){
					Command: toastr["success"](response['message']);
					setTimeout(function () { 
						var page = $('#hidden_page').val();
						var limit = $('#limit').val();
						var query = $('.campaign_search').val();
						var column_name = $('#hidden_column_name').val();
						var order_type = $('#hidden_sort_type').val();
						var query_type = $('.selected-filter-text').text();
						
						fetch_campaign_data(page,limit,query,column_name,order_type,query_type);
						//$("#defaultCampaignList").load(location.href + " #defaultCampaignList");
						$("#add-project-li").load(location.href+" #add-project-li>*","");
						packageSubscription();
						sidebar_section($('.campaign_id').val());
						//$("#ProjectActions").remove();
					}, 2000); 
					return false;
				}else if(response['status'] == 0){
					Command: toastr["error"](response['message']);
					return false;
				}else{
					Command: toastr["error"]('Getting Error!');
					return false;
				}
			}
		});
	}
});


$(document).on('click','.delete_project',function(e){
	e.preventDefault();
	if (!confirm("Are you sure you want to delete this project?")) {
		return false;
	} 

	var page = $('#hidden_page').val();
	var limit = $('#limit').val();
	var query = $('.campaign_search').val();
	var column_name = $('#hidden_column_name').val();
	var order_type = $('#hidden_sort_type').val();
	var query_type = $('.selected-filter-text').text();

	var request_id = $(this).attr('data-id');
	if(request_id !=''){
		$.ajax({
			type:'POST',
			url:BASE_URL +'/ajax_delete_project',
			data:{request_id:request_id,_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if(response['status'] == 1){
					Command: toastr["success"](response['message']);
					fetch_campaign_data(page,limit,query,column_name,order_type,query_type);
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


$(document).ready(function(){
	loadDefaultTable();
	packageSubscription();
});

$(document).on('click','.pagination a',function(e){
	e.preventDefault();
	$('li').removeClass('active');
	$(this).parent().addClass('active');

	var page = $(this).attr('href').split('page=')[1];
	$('#hidden_page').val(page);

	var limit = $('#limit').val();
	var query = $('.campaign_search').val();
	var column_name = $('#hidden_column_name').val();
	var order_type = $('#hidden_sort_type').val();
	var query_type = $('.selected-filter-text').text();
	
	fetch_campaign_data(page,limit,query,column_name,order_type,query_type);
	$('html,body').animate({
		scrollTop: $("#campaign-list").offset().top - 90
	},'slow');

});


function calculate_time(timer){
	$.ajax({
		type: 'GET',
		url:  BASE_URL + '/ajax_check_campaign_time',
		data: {timer},
		dataType: 'json',
		success: function(result) {
			if (result['status'] == '1') {
				$('#campaign-list tbody tr').removeClass('disable_project');
				$('.timer').val('');
				return false;
			}
			if(result['status'] == '0'){
				setTimeout(function(){
					calculate_time($('.timer').val());
				},10000);
			}

		}
	});
}

$(document).click((event) => {
	if (!$(event.target).closest('.actionBtn').length) {
	    $(".favourite_campaign").removeClass("open");
		$("#ProjectActions").remove();
		$('.actionBtn').find("i").attr("class", "fa fa-ellipsis-h");
	}     
});


function actionGroup() {
	$("#campaign-list").find("tbody > tr").each(function () {
		var tdHeight = $(this).find("td:last-child").outerHeight();
		var tdWidth = $(this).find("td:last-child").outerWidth();
		var actionBtn = $(this).find(".actionBtn");
		var shareKey = actionBtn.attr('data-share-key');


		actionBtn.on("click", function () {

		var campaign_id = actionBtn.attr('data-id');
		var audit = actionBtn.attr('data-audit-status');
		if(audit == 'run'){
			var audit_li = '<li><a href="/audit/detail/'+campaign_id+'" target="_blank"><figure><img src="/public/vendor/internal-pages/images/run-site-audit.png"></figure>Site Audit</a></li>';
		}else if(audit == 'check'){
			var audit_li = '<li><a href="/audit/detail/'+campaign_id+'" target="_blank"><figure><img src="/public/vendor/internal-pages/images/check-site-audit.png"></figure>Site Audit</a></li>';
		}

		var campaign_name = actionBtn.attr('data-name');
		var campaign_url = actionBtn.attr('data-url');
		var is_favourite = actionBtn.attr('data-favourite');
		if(is_favourite == 0){
			var heart = '<i class="fa fa-heart-o"></i>';
		}else{
			var heart = '<i class="fa fa-heart"></i>';
		}

			var TRow = $(this).parents("tr");
			var Left = actionBtn.offset().left - 200 + tdWidth;
			var Top = actionBtn.offset().top + tdHeight - 10;

			TRow.siblings().removeClass("open");
			var share_key = "{{URL::asset(public/vendor/internal-pages/images/share-icon-small.png)}}";
			var html ='<div class="project-actions-dropmenu show" id="ProjectActions"><ul><li><a data-id="'+campaign_id+'" data-share-key="'+shareKey+'" id="ShareKey" href="javascript:;"><figure><img src="/public/vendor/internal-pages/images/share-icon-small.png"></figure>Generate Shared Key</a></li><li><a href="/project-settings/'+campaign_id+'" target="_blank"><figure><img src="/public/vendor/internal-pages/images/setting-icon-small.png"></figure>Project Setting</a></li><li data-id="'+campaign_id+'" data-name="'+campaign_name+'" data-url="'+campaign_url+'" class="archive_row"><a href="javascript:;"><figure><img src="/public/vendor/internal-pages/images/archive-icon-small.png"></figure>Archive Project</a></li><li data-id="'+campaign_id+'" data-name="'+campaign_name+'" data-url="'+campaign_url+'" class="favourite_row"><a href="javascript:;"><figure>'+heart+'</figure>Favourite Project</a></li><li data-id="'+campaign_id+'" data-name="'+campaign_name+'" data-url="'+campaign_url+'" class="AddProjectTags"  data-pd-popup-open="add_project_tags_popup"><a href="javascript:;"><figure><i class="fa fa-tag"></i></figure>Project Tags</a></li><li><a href="/serp/'+campaign_id+'" target="_blank"><figure><img src="/public/vendor/internal-pages/images/organic-keywords-img.png"></figure>Live Keyword Tracking</a></li> <li><a href="/activities-details/'+campaign_id+'" target="_blank"><figure><img src="/public/vendor/internal-pages/images/activity-img.png"></figure>Activities</a></li>'+audit_li+' </ul></div>';
			if($('body').find('#ProjectActions').length == 0 ){
				$('body').append(html);
			}

			$("#ProjectActions").css({
				"top": Top,
				"left": Left
			});

			if (TRow.hasClass("open")) {
				TRow.removeClass("open");
				$("#ProjectActions").remove();
				$(this).find("i").attr("class", "fa fa-ellipsis-h");
			} else {
				TRow.addClass("open");
				$(".actionBtn").find("i").attr("class", "fa fa-ellipsis-h");
				$(this).find("i").attr("class", "fa fa-times");
			}

		});
	});
}

function fetch_campaign_data(page,limit,query,column_name,order_type,query_type){
	$('#campaign-list tbody tr td').addClass('ajax-loader');
	$('.project-entries').addClass('ajax-loader');
	$('.pagination').addClass('ajax-loader');
	$.ajax({
		url:BASE_URL +'/ajax_fetch_campaign_data',
		type:'GET',
		data:{page,limit,query,column_name,order_type,query_type},
		success:function(response){
			$('#campaign-list tbody').html(response);
			$('.ajax-loader').removeClass('ajax-loader');
			actionGroup();

			if($('#check_class').hasClass('disable_project')){
				var timer = $(".timer").val();
				calculate_time(timer);
			}
			$('#refresh-dashboard-search').css('display','none');
		}
	});

	$.ajax({
		url:BASE_URL +'/ajax_fetch_campaign_pagination',
		type:'GET',
		data:{page,limit,query,column_name,order_type,query_type},
		success:function(response){
			// $('.project-table-foot').html('');
			$('.project-table-foot').html(response);
			$('.project-entries').removeClass('ajax-loader');
			$('.pagination').removeClass('ajax-loader');
		}
	});
}

$(document).on('change','.CampaignsToList',function(e){
	e.preventDefault();
	
	var limit = $(this).val();
	$('#limit').val(limit);
	var page = 1;
	var query = $('.campaign_search').val();

	var column_name = $('#hidden_column_name').val();
	var order_type = $('#hidden_sort_type').val();
	var query_type = $('.selected-filter-text').text();

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

$(document).on('keyup','#campaign_search_id',function (e) {
	$('#refresh-dashboard-search').css('display','block');
});

$('#campaign_search_id').keyup(delay(function (e) {
	if($('#campaign_search_id').val() != '' || $('#campaign_search_id').val() != null){
	   $('.dashboard-search-clear').css('display','block');
	}
  ajax_campaignlist($(this).val());
}, 1000));

function ajax_campaignlist(query){
	var page = 1;
	var limit = $('#limit').val();
	var column_name = $('#hidden_column_name').val();
	var order_type = $('#hidden_sort_type').val();
	var query_type = $('.selected-filter-text').text();

	fetch_campaign_data(page,limit, query,column_name,order_type,query_type);

	if(query != ''){
		$('.search-filter').css('display','none');
		if($('#filter-search-form').hasClass('open')){
			$('#filter-search-form').removeClass('open');
		}
	}else{
		$('.search-filter').css('display','block');
		$('#filter-search-form').addClass('open');
	}
}


$(document).on('click','.ActiveCampaignsClear',function(e){
	e.preventDefault();
	$('#campaign_search_id').val('');
	if($('#campaign_search_id').val() == '' || $('#campaign_search_id').val() == null){
		$('.dashboard-search-clear').css('display','none');
	   ajax_campaignlist($('#campaign_search_id').val());
	}
});


$(document).on('click','.sorting',function(e){
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


	 //add_icon(column_name,reverse_order);

	 $('#hidden_column_name').val(column_name);
	 $('#hidden_sort_type').val(reverse_order);
	 var page = $('#hidden_page').val();
	 var query = $('.campaign_search').val();
	 var limit = $('#limit').val();
	 var query_type = $('.selected-filter-text').text();

	 fetch_campaign_data(page,limit, query,column_name,reverse_order,query_type);
	});




$('#campaign-list #checkAll').click(function() {
	if ($("#campaign-list #checkAll").is(':checked')) {
		$('#campaign-list input[type=checkbox]').prop('checked', true);
	} else {
		$('#campaign-list input[type=checkbox]').prop('checked', false);
	}
});

$('#campaign-list input[type=checkbox]').on('change',function(){
	if($('.selected_campaigns:checked').length == $('.selected_campaigns').length){
		$('#checkAll').prop('checked',true);
	}else{
		$('#checkAll').prop('checked',false);
	}
});


$(document).on('click','.archive_projects',function(){

	var page = $('#hidden_page').val();
	var limit = $('#limit').val();
	var query = $('.campaign_search').val();
	var column_name = $('#hidden_column_name').val();
	var order_type = $('#hidden_sort_type').val();
	var query_type = $('.selected-filter-text').text();

	var checked =[];

	$("input[name='selected_campaigns[]']:checked").each(function () {
		checked.push(parseInt($(this).val()));
	});

	if(checked.length == 0 ){
		Command: toastr["error"]('Please select Campaigns to Archive.');
		return false;
	}


	if(checked.length > 0){
		$.ajax({
			type: "POST",
			url: BASE_URL + '/ajax_archive_campaigns',
			data: {checked:checked,_token:$('meta[name="csrf-token"]').attr('content')},
			dataType: 'json',
			success: function(result) {
				if(result.status == 1){
					Command: toastr["success"](result['message']);
					fetch_campaign_data(page,limit, query,column_name,order_type,query_type);
					//$("#defaultCampaignList").load(location.href + " #defaultCampaignList");	
					$("#add-project-li").load(location.href+" #add-project-li>*","");
					packageSubscription();
					sidebar_section($('.campaign_id').val());
					// $("#ProjectActions").remove();
				} 
				if(result.status == 0){
					Command: toastr["warning"](result['message']);
				}

			}
		});
	}
});


$(document).on('click','.favourite_row',function(e){
	e.preventDefault();

	var page = $('#hidden_page').val();
	var limit = $('#limit').val();
	var query = $('.campaign_search').val();
	var column_name = $('#hidden_column_name').val();
	var order_type = $('#hidden_sort_type').val();
	var query_type = $('.selected-filter-text').text();

	var request_id = $(this).attr('data-id');
	
	if(request_id !=''){
		$.ajax({
			type:'POST',
			url:BASE_URL + '/ajax_favourite_project',
			data:{request_id:request_id,_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if(response['status'] == 1){
					Command: toastr["success"](response['message']);
					fetch_campaign_data(page,limit, query,column_name,order_type,query_type);
					$("#ProjectActions").remove();
				}else if(response['status'] == 0){
					Command: toastr["error"](response['message']);
					return false;
				}else{
					Command: toastr["error"]('Getting Error!');
					return false;
				}
			}
		});
	}

});

/*dashboard search*/

$(document).on('click', function(event) {
	if (event.target.id !== "campaign_search_id") {
		$("#filter-search-form").removeClass("open");
	}
});


if($('.campaign_search').val() == ''){	
	$('.project-search.style2 input.campaign_search').focus(function () {
		$(this).parent().addClass('open');
	});
}

$(document).on('click','.search-filter-list',function(e){
	var selected = $(this).find('span').text();
	$('.selected-filter-text').text(selected);
	$('#filter-search-form').removeClass('open');
	e.stopPropagation();
});


function loadDefaultTable(){
	var page = $('#hidden_page').val();
	var limit = $('#limit').val();
	var query = $('.campaign_search').val();
	var column_name = $('#hidden_column_name').val();
	var order_type = $('#hidden_sort_type').val();
	var query_type = $('.selected-filter-text').text();
	
	fetch_campaign_data(page,limit,query,column_name,order_type,query_type);
}

/*tags section*/

$('.add_project_tag_input').tagsinput({
  maxTags: 3
});


function validateURL(link) {
	if (link.indexOf("http://") == 0 || link.indexOf("https://") == 0) {
		return link;
	} else {
		return '//' + link;
	}
}

$(document).on('click','.AddProjectTags',function(e){
	e.preventDefault();
	$('.campaign_id').val($(this).attr('data-id'));
	$('.tags_project_href').attr('href', validateURL($(this).attr('data-url')));
	$('#tags_project_name').text($(this).attr('data-url'));
	$('.add_project_tag_input').tagsinput('removeAll');
	
	$('.add_project_tag_input').removeClass('error');
	$('#tagsinput_error').parent().css('display','none');
	document.getElementById('tagsinput_error').innerHTML = '';
	

	fetch_existing_project_tags($(this).attr('data-id'));
});

function fetch_existing_project_tags(campaign_id){
	$.ajax({
		type:    "GET",
		url:     BASE_URL+ "/ajax_get_project_tags",
		data:    {campaign_id},
		dataType: 'json',
		success: function(result) {
			$('#append_project_tag_div').html('');
			$('#append_project_tag_div').html(result['html']);
		}
	});
}

$(document).on('click','#add_project_tag',function(e){
	var tags = $('.add_project_tag_input').val();

	if(tags.length == 0){
		Command: toastr["error"]('Add tag(s) to save !');
		return false;
	}
	$(this).attr('disabled','disabled');
	$.ajax({
		type:"POST",
		url:BASE_URL+ "/ajax_save_project_tags",
		data:{campaign_id: $('.campaign_id').val(),  tags: $('.add_project_tag_input').val() , _token:$('meta[name="csrf-token"]').attr('content')},
		dataType: 'json',
		success: function(response) {
			if(response['status'] == 'success'){
				$('#add_project_tags_popup_close').trigger('click');
				$('#add_project_tags_popup').css('display', 'none');
				$('body').removeClass('popup-open');
				Command: toastr["success"]('Tags saved successfully!');
				setTimeout(function(){
					loadDefaultTable();
				},400);
				$("#ProjectActions").remove();
			}
			if(response['status'] == 'error'){
				$('.add_project_tag_input').addClass('error');
				$('#tagsinput_error').parent().css('display','block');
				document.getElementById('tagsinput_error').innerHTML = 'Error updating tags.';
			}

			if(response['status'] == 'count-error'){
				$('.add_project_tag_input').addClass('error');
				$('#tagsinput_error').parent().css('display','block');
				document.getElementById('tagsinput_error').innerHTML = 'Only 3 tags allowed, remove existing to continue.';
			}
			$('#add_project_tag').removeAttr('disabled','disabled');
		}
	});
});

$(document).on('click','.delete_project_tag',function(e){
	e.preventDefault();
	var tag_id = $(this).attr('data-tag-id');
	var request_id = $(this).attr('data-request-id');
	var tag = $(this).attr('data-value');
	$.ajax({
		type:'POST',
		url:BASE_URL + '/ajax_delete_project_tag',
		data:{tag_id,request_id,tag,_token:$('meta[name="csrf-token"]').attr('content')},
		dataType:'json',
		success:function(response){
			if(response['status'] == 1){
				Command: toastr["success"](response['message']);
				fetch_existing_project_tags($('.campaign_id').val());
				loadDefaultTable();
				$("#ProjectActions").remove();
			}

			if(response['status'] == 0){
				Command: toastr["error"](response['message']);
			}
		}
	});
});


function packageSubscription(){
	$.ajax({
		type:'GET',
		url: BASE_URL+'/ajax_get_package_subscription',
		dataType:'json',
		success:function(response){
			$('.dashboard-keyword-detail').html(response['keyword_detail']);
			$('.dashboard-project-detail').html(response['project_detail']);
			$('.dashboard-project-name').html(response['package_name']);
		}
	});
}


$(document).on('click','.showOtherChart',function(e){
	e.preventDefault();
	if ($('#dashboard-project-stats').find('.dashboard-stats-added').length == 0) {
		$('.dashboard-keywords-up').addClass('ajax-loader');
		$('.dashboard-top-three').addClass('ajax-loader');
		$('.dashboard-top-ten').addClass('ajax-loader');
		$('.dashboard-top-twenty').addClass('ajax-loader');
		$('.dashboard-top-thirty').addClass('ajax-loader');
		$('.dashboard-top-hundred').addClass('ajax-loader');

		$('.dashboard-top-all-since').addClass('ajax-loader');
		$('.dashboard-top-three-since').addClass('ajax-loader');
		$('.dashboard-top-ten-since').addClass('ajax-loader');
		$('.dashboard-top-twenty-since').addClass('ajax-loader');
		$('.dashboard-top-thirty-since').addClass('ajax-loader');
		$('.dashboard-top-hundred-since').addClass('ajax-loader');
		
		$('.dashboard-project-stats-span').addClass('dashboard-stats-added');
		keywordDetail();
	}
});

function keywordDetail(){
	$.ajax({
		type:'GET',
		url: BASE_URL+'/ajax_get_keyword_detail',
		dataType:'json',
		success:function(response){
			var since_three = since_ten = since_twenty = since_thirty = since_hundred = '';
			if (response['since_three'] > 0) {
				var since_three = '<i class="icon ion-arrow-up-a"></i>';
			} else if (response['since_three'] < 0) {
				var since_three = '<i class="icon ion-arrow-down-a"></i>';
			}

			if (response['since_ten'] > 0) {
				var since_ten = '<i class="icon ion-arrow-up-a"></i>';
			} else if (response['since_ten'] < 0) {
				var since_ten = '<i class="icon ion-arrow-down-a"></i>';
			}

			if (response['since_twenty'] > 0) {
				var since_twenty = '<i class="icon ion-arrow-up-a"></i>';
			} else if (response['since_twenty'] < 0) {
				var since_twenty = '<i class="icon ion-arrow-down-a"></i>';
			}

			if (response['since_thirty'] > 0) {
				var since_thirty = '<i class="icon ion-arrow-up-a"></i>';
			} else if (response['since_thirty'] < 0) {
				var since_thirty = '<i class="icon ion-arrow-down-a"></i>';
			}
			if (response['since_hundred'] > 0) {
				var since_hundred = '<i class="icon ion-arrow-up-a"></i>';
			} else if (response['since_hundred'] < 0) {
				var since_hundred = '<i class="icon ion-arrow-down-a"></i>';
			}
			$('.dashboard-keywords-up').html(response['keywords_up']);
			$('.dashboard-top-three').html(response['three'] + '<small>/' + response['total']+'</small>');

			if(response['since_three'] > 0){
				$('.dashboard-top-three').addClass('green');
				$('.dashboard-top-three').removeClass('red');
			}else if(response['since_three'] < 0){
				$('.dashboard-top-three').addClass('red');
				$('.dashboard-top-three').removeClass('green');
			}else{
				$('.dashboard-top-three').removeClass('red');
				$('.dashboard-top-three').removeClass('green');
			}
			$('.dashboard-top-three-since').html(since_three + '<strong>' + response['since_three'] + '</strong> since start');
			$('.dashboard-top-ten').html(response['ten'] + '<small>/' + response['total']+'</small>');

			if(response['since_ten'] > 0){
				$('.dashboard-top-ten').addClass('green');
				$('.dashboard-top-ten').removeClass('red');
			}else if(response['since_ten'] < 0){
				$('.dashboard-top-ten').addClass('red');
				$('.dashboard-top-ten').removeClass('green');
			}else{
				$('.dashboard-top-ten').removeClass('red');
				$('.dashboard-top-ten').removeClass('green');
			}
			$('.dashboard-top-ten-since').html(since_ten + '<strong>' + response['since_ten'] + '</strong> since start');
			$('.dashboard-top-twenty').html(response['twenty']+ '<small>/' + response['total']+'</small>');
			if(response['since_twenty'] > 0){
				$('.dashboard-top-twenty').addClass('green');
				$('.dashboard-top-twenty').removeClass('red');
			}else if(response['since_twenty'] < 0){
				$('.dashboard-top-twenty').addClass('red');
				$('.dashboard-top-twenty').removeClass('green');
			}else{
				$('.dashboard-top-twenty').removeClass('red');
				$('.dashboard-top-twenty').removeClass('green');
			}
			$('.dashboard-top-twenty-since').html(since_twenty + '<strong>' + response['since_twenty'] + '</strong> since start');
			$('.dashboard-top-thirty').html(response['thirty']+ '<small>/' + response['total']+'</small>');
			if(response['since_thirty'] > 0){
				$('.dashboard-top-thirty').addClass('green');
				$('.dashboard-top-thirty').removeClass('red');
			}else if(response['since_thirty'] < 0){
				$('.dashboard-top-thirty').addClass('red');
				$('.dashboard-top-thirty').removeClass('green');
			}else{
				$('.dashboard-top-thirty').removeClass('red');
				$('.dashboard-top-thirty').removeClass('green');
			}
			$('.dashboard-top-thirty-since').html(since_thirty + '<strong>' + response['since_thirty'] + '</strong> since start');
			$('.dashboard-top-hundred').html(response['hundred']+ '<small>/' + response['total']+'</small>');
			if(response['since_hundred'] > 0){
				$('.dashboard-top-hundred').addClass('green');
				$('.dashboard-top-hundred').removeClass('red');
			}else if(response['since_hundred'] < 0){
				$('.dashboard-top-hundred').addClass('red');
				$('.dashboard-top-hundred').removeClass('green');
			}else{
				$('.dashboard-top-hundred').removeClass('red');
				$('.dashboard-top-hundred').removeClass('green');
			}
			$('.dashboard-top-hundred-since').html(since_hundred + '<strong>' + response['since_hundred'] + '</strong> since start');
			$('.dashboard-keywords-up').removeClass('ajax-loader');
			$('.dashboard-top-three').removeClass('ajax-loader');
			$('.dashboard-top-ten').removeClass('ajax-loader');
			$('.dashboard-top-twenty').removeClass('ajax-loader');
			$('.dashboard-top-thirty').removeClass('ajax-loader');
			$('.dashboard-top-hundred').removeClass('ajax-loader');

			$('.dashboard-top-all-since').removeClass('ajax-loader');
			$('.dashboard-top-three-since').removeClass('ajax-loader');
			$('.dashboard-top-ten-since').removeClass('ajax-loader');
			$('.dashboard-top-twenty-since').removeClass('ajax-loader');
			$('.dashboard-top-thirty-since').removeClass('ajax-loader');
			$('.dashboard-top-hundred-since').removeClass('ajax-loader');
		}
	});
}

$(document).on('click','#renew-package',function(e){
	var detailsWindow;
	detailsWindow = window.open(BASE_URL + '/profile-settings', '_blank');
	detailsWindow.onload = function(){
		detailsWindow.document.getElementById("profile-account-li").classList.remove("uk-active");
		detailsWindow.document.getElementById("account-profile-div").classList.remove("uk-active");
		detailsWindow.document.getElementById("plan_div").className = 'uk-active';
		detailsWindow.document.getElementById("profile-plan-li").className = 'uk-active';
	} 
});


/*hide share key on outside click*/
// $(document).click((event) => {
// 	if (!$(event.target).closest('#ShareKey').length && !$(event.target).closest('.share-key-btn').length) {
// 		$('.share-key-btn').val("Click to copy");
// 		$(".share-key-popup").removeClass("open");
// 	}     
// });