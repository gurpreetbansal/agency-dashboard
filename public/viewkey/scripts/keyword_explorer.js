var BASE_URL = $('.base_url').val();

$(document).on("click","#OpenCustomDropdown", function (e) {
	$(".custom-dropdown-menu").toggleClass("show");
	$('.input-search-keyword').focus();
	e.stopPropagation();
});
$(document).on("click", function (e) {
	if ($(e.target).is(".custom-dropdown-menu, .custom-dropdown-menu *") === false) {
		$(".custom-dropdown-menu").removeClass("show");
	}
});

$(document).on("click", "#OpenCustomDropdownDomain",function (e) {
	$(".custom-dropdown-menu.domainDiv").toggleClass("show");
	$('.input-search-domain').focus();
	e.stopPropagation()
});
$(document).on("click", function (e) {
	if ($(e.target).is(".custom-dropdown-menu.domainDiv, .custom-dropdown-menu.domainDiv *") === false) {
		$(".custom-dropdown-menu.domainDiv").removeClass("show");
	}
});

$(document).on("click", "#OpenCustomDropdownDetailKeyword",function (e) {
	$(".custom-dropdown-menu.DetailKeywordDiv").toggleClass("show");
	$('.input-search-keyword-detail').focus();
	e.stopPropagation();
});
$(document).on("click", function (e) {
	if ($(e.target).is(".custom-dropdown-menu.DetailKeywordDiv, .custom-dropdown-menu.DetailKeywordDiv *") === false) {
		$(".custom-dropdown-menu.DetailKeywordDiv").removeClass("show");
	}
});

$(document).on("click", "#OpenCustomDropdownDetailDomain",function (e) {
	$(".custom-dropdown-menu.DetailDomainDiv").toggleClass("show");
	$('.input-search-domain-detail').focus();
	e.stopPropagation();
});
$(document).on("click", function (e) {
	if ($(e.target).is(".custom-dropdown-menu.DetailDomainDiv, .custom-dropdown-menu.DetailDomainDiv *") === false) {
		$(".custom-dropdown-menu.DetailDomainDiv").removeClass("show");
	}
});

$(document).ready(function(){
	getDfsLanguages();
	getDfsLocations('','','search-section');
});


$(document).on('keyup','.input-search-keyword',function() {
	$(this).next('.refresh-icon').css('display','block');
});

$(document).on('keyup','.input-search-keyword',delay(function (e) {
	e.preventDefault();
	var location = $('.input-search-keyword').val();
	getDfsLocations(location,1,'search-section');
},500)); 



$(document).on('keyup','.input-search-domain',function (e) {
	$(this).next('.refresh-icon').css('display','block');
});
$(document).on('keyup','.input-search-domain',delay(function (e) {
	e.preventDefault();
	var location = $('.input-search-domain').val();
	getDfsLocations(location,2,'search-section');
},500)); 


$(document).on('keyup','.input-search-keyword-detail',function (e) {
	$(this).next('.refresh-icon').css('display','block');
});
$(document).on('keyup','.input-search-keyword-detail',delay(function (e) {
	e.preventDefault();
	var location = $('.input-search-keyword-detail').val();
	getDfsLocations(location,1,'ideas');
},500)); 


$(document).on('keyup','.input-search-domain-detail',function (e) {
	$(this).next('.refresh-icon').css('display','block');
});
$(document).on('keyup','.input-search-domain-detail',delay(function (e) {
	e.preventDefault();
	var location = $('.input-search-domain-detail').val();
	getDfsLocations(location,2,'ideas');
},500)); 


$(document).on('click','.location-data',function(){
	var dataTypeAttribute = $(this).find('a').attr('location_id');
	var dataTypeAttributeVal = $(this).find('a').attr('location-name');
	if($('#searchKeyword.search-section').hasClass('uk-active')){
		$('.keyword_location_id').val('');
		$('.keyword_location_id').val(dataTypeAttribute);
		$('.keyword-locations').val(dataTypeAttributeVal);
		$('.custom-dropdown-menu').removeClass('show');
	}
	if($('#searchDomain.search-section').hasClass('uk-active')){
		$('.domain_location_id').val('');
		$('.domain_location_id').val(dataTypeAttribute);
		$('.domain-locations').val(dataTypeAttributeVal);
		$('.custom-dropdown-menu.domainDiv').removeClass('show');
	}

	if($('#searchKeyword.ideas').hasClass('uk-active')){
		$('.detail_keyword_location_id').val('');
		$('.detail_keyword_location_id').val(dataTypeAttribute);
		$('.keyword-locations').val(dataTypeAttributeVal);
		$('.custom-dropdown-menu.DetailKeywordDiv').removeClass('show');
	}
	if($('#searchDomain.ideas').hasClass('uk-active')){
		$('.detail_domain_location_id').val('');
		$('.detail_domain_location_id').val(dataTypeAttribute);
		$('.domain-locations').val(dataTypeAttributeVal);
		$('.custom-dropdown-menu.DetailDomainDiv').removeClass('show');
	}
});



function getDfsLanguages(){
	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_get_dfs_languages',
		dataType:'json',
		success:function(response){
			$('.selectpicker').selectpicker('refresh');
			$('#keyword_language').html(response);
			$('.selectpicker').selectpicker('refresh');
		}
	});
}


function getDfsLocations(location = null,category=null,type=null){
	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_get_dfs_locations',
		dataType:'json',
		data:{location:location,category:category,type:type},
		success:function(response){
			$('.selectpicker').selectpicker('refresh');
			if(type === 'search-section'){
				if(category == 1){
					$('.input-search-keyword').next('.refresh-icon').css('display','none');
					$('#keyword_locations').html(response).selectpicker('refresh');
				}else if(category == 2){
					$('.input-search-domain').next('.refresh-icon').css('display','none');
					$('#domain_locations').html(response).selectpicker('refresh');
				}else{
					$('#keyword_locations').html(response).selectpicker('refresh');
					$('#domain_locations').html(response).selectpicker('refresh');
				}
			}
			if(type === 'ideas'){
				if(category == 1){
					$('.input-search-keyword-detail').next('.refresh-icon').css('display','none');
					$('#detail_keyword_locations').html('');
					$('#detail_keyword_locations').html(response).selectpicker('refresh');
				}else if(category == 2){
					$('.input-search-domain-detail').next('.refresh-icon').css('display','none');
					$('#detail_domain_locations').html('');
					$('#detail_domain_locations').html(response).selectpicker('refresh');
				}else{
					$('#detail_keyword_locations, #detail_domain_locations').html('');
					$('#detail_keyword_locations').html(response).selectpicker('refresh');
					$('#detail_domain_locations').html(response).selectpicker('refresh');
				}
			}
			$('.selectpicker').selectpicker('refresh');
		}
	});
}

function getDfsLanguagesDetail(language = null){
	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_get_dfs_languages',
		dataType:'json',
		data:{language},
		success:function(response){
			$('.selectpicker').selectpicker('refresh');
			$('#detail_keyword_language').html(response);
			$('.selectpicker').selectpicker('refresh');
		}
	});
}

$(document).on('changed.bs.select', '#keyword_language', function () {
	var dataTypeAttribute = $('option:selected', this).attr("language_id");
	$('.keyword_language_id').val('');
	$('.keyword_language_id').val(dataTypeAttribute);
});

$(document).on('click','.find_keywords',function(e){
	e.preventDefault();

	var category =  $(this).attr('data-category');
	var campaign_id = $('.campaign_id').val();
	var user_id = $('#user_id').val();	

	if(category == 1){
		var search_query = ($('.query_field').val()).trim();
		var locations = $('.keyword_location_id').val();
		var language = $('.keyword_language_id').val();
		$('.domain_query_field').removeClass('error');
		$('.domain_query_field').val('');
		if(search_query == ''){
			$('.query_field').addClass('error');
		}else{
			$('.query_field').removeClass('error');
		}
	}else if(category == 2){
		var search_query = ($('.domain_query_field').val()).trim();
		$('.query_field').removeClass('error');
		$('.query_field').val('');
		if (!is_url(search_query)) {
			$('.domain_query_field').addClass('error');
		}else{
			$('.domain_query_field').removeClass('error');
		}
		var locations = $('.domain_location_id').val();
		var language = '';
	}


	$('.location').val(locations);
	$('.language').val(language);
	$('.search_term').val(search_query);
	$('.category').val(category);
	$('.ke-table-data').addClass('ajax-loader');
	
	if(!$('.domain_query_field').hasClass('error') && !$('.query_field').hasClass('error')){
		$(this).attr('disabled','disabled');
		$('.detail_find_keywords').attr('disabled','disabled');
		call_kw_api(search_query,locations,language,campaign_id,user_id,category);
		
		$('.find_keywords').removeAttr('disabled','disabled');
		$('.detail_find_keywords').removeAttr('disabled','disabled');
	}
});

$(document).on('click','.search_keywords',function(){
	$(".keyword-explorer").load('/keyword_explorer_records/search', function(responseTxt, statusTxt, xhr){
		$('.selectpicker').selectpicker('refresh');
		if(statusTxt == "success")
			getDfsLanguages();
		getDfsLocations('',null,'search-section');
		if(statusTxt == "error")
			console.log("Error: " + xhr.status + ": " + xhr.statusText);
	});
});

function getKeywordResponse(id){
	$.ajax({
		type:'GET',
		url:BASE_URL+'/get_keyword_response/'+id+'/0',
		dataType:'json',
		success:function(response){
			$('.ke-table-data').html(response['data']);
			$('#scroll-status').val('completed');
			$('#custom-tooltip-text').removeClass('ajax-loader');
			$('.display-time').html(response['searched_date']);
			$('.display-time').after(response['tooltip_text']);
			scrollTable();
			$('.keyword-search-id').val(id);
			setTimeout(function(){
				showTooltip();
				for(var i=1;i <= response['count'];i++){
					var chart_id = 'myChart'+i;
					showChart(chart_id,response['ids'][i-1]);
				}
			},1500);
			
			$('.show-total-count').html(response['count']);
			$('#total-count').val(response['total']);
		}
	});
}

/*copy keyword text*/
new ClipboardJS('.copy-keyword-text');
$(document).on("click", ".copy-keyword-text", function () {
	Command: toastr["success"]('Successfully copied');
});
/*copy keyword text*/


function showChart(chart_id,data_id){
	var svTrend = data_id['sv_trend'];
	response = [];
	labels = [];
	searches = [];
	svTrend.forEach((entry,index) => {
		if(entry.month == 13){
			labels[index]  =  entry.year+'-1';
		}else{
			labels[index]  =  entry.year +'-'+ entry.month;
		}
		searches[index]  = entry.monthly_search;
	});
	response.push(labels);
	response.push(searches);
	DrawChart(chart_id,response);
	// setTimeout(function(){
	// 	showTooltipGraph();
	// },1500);
}

var timeout ;
$(document).on({
	mouseenter: function(event){
		$(event.currentTarget).find('.refresh').addClass('show-spinner');
		timeout = setTimeout(function(){
			$('.bar-canvas').removeClass("graph_tooltip"); 
			$(event.currentTarget).addClass("graph_tooltip"); 
			showTooltipGraph();
		}, 1000);
	},
	mouseleave: function(){
		$(event.currentTarget).find('.refresh').removeClass('show-spinner');
		clearTimeout(timeout);
	}
}, '.bar-canvas');

$(document).on({
	mouseleave: function(){
		$(".bar-canvas-popup").removeClass("show_graph");
		$('.bar-canvas').removeClass("graph_tooltip");
	}
}, '.bar-canvas-popup');


function DrawChart(chart_id,response){
	var chart = new Chart(document.getElementById(chart_id).getContext('2d'), {
		type: 'bar',
		data: {
			labels: response[0],
			datasets: [
			{
				data: response[1],
				hoverBackgroundColor: '#327aee',
				hoverBorderColor: '#327aee',
				backgroundColor: '#c9c9c9',
				borderColor: '#3e4348'
			}
			]
		},
		options: {
			hover: {
				mode: 'dataset',
			},
			click: {
				mode: 'dataset',
			},
			responsive: false,
			legend: {display: false},
			tooltips: {enabled: false},
			scales: {
				yAxes: [
				{
					display: false
				}
				],
				xAxes: [
				{
					display: false
				}
				]
			},
			animation: {
				duration: 500
			}
		}
	});
}




$(document).on('click','.selectKeyword',function(){
	var checked = []
	$("input[name='select_keywords[]']:checked").each(function () {
		checked.push(parseInt($(this).attr('data-id')));
	});

	selectedCheckbox(checked.length);
});

$(document).on('click','thead #selectAllIdeas', function(e){
	var checked = [];
    $("#ke-table tbody input[type=checkbox]").prop('checked', $(this).prop('checked'));
    $("input[name='select_keywords[]']:checked").each(function () {
		checked.push(parseInt($(this).attr('data-id')));
	});

	selectedCheckbox(checked.length);
	e.stopPropagation();
});

function selectedCheckbox(checked_length){
	$('.selected-keyword-count').html(checked_length+' /');
	$('.listing-selected-keyword-count').html(checked_length+' /');
	$('.total-keyword-ideas').val(checked_length);
	
	if(checked_length == 0){
		$('.right').find('a').addClass('disabled');
	}else{
		if($(".selectKeyword").length == checked_length) {
			$("#selectAllIdeas").prop("checked", true);
		} else {
			$("#selectAllIdeas").prop("checked", false);
		}
		$('.right').find('a').removeClass('disabled');
	}
}

$(document).on('click', '#ke-table tbody input[type="checkbox"]', function(e){
	var rows_selected = [];
	var $row = $(this).closest('tr');
	var data = $row.data();
	var rowId = data[0];
	var index = $.inArray(rowId, rows_selected);
	if(this.checked && index === -1){
		rows_selected.push(rowId);
	} else if (!this.checked && index !== -1){
		rows_selected.splice(index, 1);
	}

	if($('.total-keyword-ideas').val() == 0){
		$('.right').find('a').addClass('disabled');
	} else {
		$('.right').find('a').removeClass('disabled');
	}
	e.stopPropagation();
});


$(document).on('click','#export_keyword_ideas',function(e){
	e.preventDefault();
	var checked =[];
	var type = $(this).attr('data-type');

	$("input[name='select_keywords[]']:checked").each(function () {
		checked.push(parseInt($(this).attr('data-id')));
	});

	var kw_search_id = $('.keyword-search-id').val();
	var list_id = $('.keyword-list-id').val();

	$("#export_keyword_ideas a").attr("target","_blank");
	var url = BASE_URL +"/ajax_export_keyword_ideas?checked=" +checked+"&kw_search_id="+kw_search_id+'&type='+type+'&list_id='+list_id;
	window.open(url, '_blank');
});

function is_url(str)
{
	regexp = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
	if (regexp.test(str))
	{
		return true;
	} else
	{
		return false;
	}
}


$(document).on('click','.kw-ideas-sorting',function(e){
	e.preventDefault();
	var column_name = $(this).attr('data-field');
	var sorting_type = $(this).attr('data-sorting_type');
	$('#ke-table tbody tr').addClass('ajax-loader');
	if(sorting_type == 'asc')
	{
		$(this).attr('data-sorting_type', 'desc');
		reverse_order = 'desc';
		$('.asc').removeClass('asc');
		$('.desc').removeClass('desc');
		$(this).addClass('desc');
	}

	if(sorting_type == 'desc')
	{
		$(this).attr('data-sorting_type', 'asc');
		reverse_order = 'asc';
		$('.desc').removeClass('desc');
		$('.asc').removeClass('asc');
		$(this).addClass('asc');
	}

	$('.hidden_column_name').val(column_name);
	$('.hidden_sort_type').val(reverse_order);
	var category = $('.category').val();
	var searched_term = $('.search_term').val();
	var hidden_location = $('.location').val();
	var hidden_language = $('.language').val();
	var keyword_search_id = $('.keyword-search-id').val();
	$('#ke-table').removeClass('remove-hover-effect');
	getKeywordIdeas(column_name,reverse_order,searched_term,hidden_location,hidden_language,category,keyword_search_id);
});

function getKeywordIdeas(column_name,reverse_order,searched_term,hidden_location,hidden_language,category,keyword_search_id){
	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_get_keyword_ideas_data',
		data:{column_name,reverse_order,searched_term,hidden_location,hidden_language,category,keyword_search_id},
		dataType:'json',
		success:function(response){
			$('.ke-table-data').html('');
			$('.ke-table-data').html(response['data']);
			$('.keyword-search-id').val(keyword_search_id);
			setTimeout(function(){
				for(var i=1;i <= response['count'];i++){
					var chart_id = 'myChart'+i;
					showChart(chart_id,response['ids'][i-1]);
				}
			},2000);
			$('.show-total-count').html(response['count']);
		}
	});
}	

$(document).on('click','.search_query',function(){
	var category =  $(this).attr('data-category');
	var campaign_id = $('.campaign_id').val();
	var user_id = $('#user_id').val();
	var search_query = $(this).attr('data-query');
	var locations = '';
	var language = '';

	$('.location').val(locations);
	$('.language').val(language);
	$('.search_term').val(search_query);
	$('.category').val(category);
	
	call_kw_api(search_query,locations,language,campaign_id,user_id,category);
});


function call_kw_api(search_query,locations,language,campaign_id,user_id,category){
	$(".keyword-explorer").load('/keyword_explorer_records/ideas', function(responseTxt, statusTxt, xhr){
		if(statusTxt == "success")

			$.ajax({
				type:'GET',
				url:BASE_URL+'/ajax_fetch_keyword_ideas_data',
				data:{search_query,locations,language,campaign_id,user_id,category},
				dataType:'json',
				success:function(response){
					$('.detail_find_keywords').attr('disabled','disabled');
					$('.selectpicker').selectpicker('refresh');
					if($('.category').val() == 1){
						$('.searchByKeyword').addClass('uk-active');
						$('.searchByDomain').removeClass('uk-active');
						$('.detail_query_field').val($('.search_term').val());
						$('.keyword-locations').val(response['location_name']);
						$('#searchDomain').removeClass('uk-active');
						$('#searchKeyword').addClass('uk-active');
					}else if($('.category').val() == 2){
						$('.searchByKeyword').removeClass('uk-active');
						$('.searchByDomain').addClass('uk-active');
						$('.detail_domain_query_field').val($('.search_term').val());
						$('.domain-locations').val(response['location_name']);
						$('#searchDomain').addClass('uk-active');
						$('#searchKeyword').removeClass('uk-active');
					}

					if(response['status'] == 'error'){
						//Command: toastr["error"]('Try again later');
						Command: toastr["error"]('Sorry, We could not find data for this keyword, try searching other keyword.');
						$('#ke-table').removeClass('remove-hover-effect');
						$('.ke-table-data').find('.ajax-loader').removeClass('ajax-loader');
						$('.ke-table-data').html('');
						$('.ke-table-data').html(response['html']);
						$('.detail_find_keywords').removeAttr('disabled','disabled');
						$('.display-time').html('');
						getDfsLanguagesDetail(language);
						//getDfsLocationsDetail(locations);
						getDfsLocations('','','ideas');
						$('#custom-tooltip-text').removeClass('ajax-loader');
						return false;
					}
					if(response['status'] !== 'error'){
						getKeywordResponse(response['id']);
						getDfsLanguagesDetail(language);
						getDfsLocations('','','ideas');
						$('.detail_find_keywords').removeAttr('disabled','disabled');
						$('#ke-table').removeClass('remove-hover-effect');
						triggerHeight();
					}
				}
			});

		if(statusTxt == "error")
			console.log("Error: " + xhr.status + ": " + xhr.statusText);
	});
}

$(document).on('click','.detail_find_keywords',function(e){
	e.preventDefault();

	$(this).attr('disabled','disabled');

	var category =  $(this).attr('data-category');
	var campaign_id = $('.campaign_id').val();
	var user_id = $('#user_id').val();

	if(category == 1){
		var search_query = ($('.detail_query_field').val()).trim();
		if($('.detail_keyword_location_id').val() !== ''){
			var locations = $('.detail_keyword_location_id').val();
		}else{
			var locations = $('.location').val();
		}
		var language = $('.detail_keyword_language_id').val();
		$('.detail_domain_query_field').removeClass('error');
		$('.detail_domain_query_field').val('');
		if(search_query == ''){
			$('.detail_query_field').addClass('error');
		}else{
			$('.detail_query_field').removeClass('error');
		}
	}else if(category == 2){
		var search_query = ($('.detail_domain_query_field').val()).trim();
		$('.detail_query_field').removeClass('error');
		$('.detail_query_field').val('');
		if (!is_url(search_query)) {
			$('.detail_domain_query_field').addClass('error');
		}else{
			$('.detail_domain_query_field').removeClass('error');
		}

		if($('.detail_domain_location_id').val() !== ''){
			var locations = $('.detail_domain_location_id').val();
		}else{
			var locations = $('.location').val();
		}
		var language = '';
	}


	$('.location').val(locations);
	$('.language').val(language);
	$('.search_term').val(search_query);
	$('.category').val(category);
	$('.ke-table-data').addClass('ajax-loader');
	
	if(!$('.detail_domain_query_field').hasClass('error') && !$('.detail_query_field').hasClass('error')){
		$.ajax({
			type:'GET',
			url:BASE_URL+'/ajax_fetch_keyword_ideas_data',
			data:{search_query,locations,language,campaign_id,user_id,category},
			dataType:'json',
			success:function(response){
				if(response['status'] == 'error'){
					Command: toastr["error"]('Sorry, We could not find data for this keyword, try searching other keyword.');
					$('#ke-table').removeClass('remove-hover-effect');
					$('.ke-table-data').removeClass('ajax-loader');
					$('.ke-table-data').html('');
					$('.ke-table-data').html(response['html']);
					$('.detail_find_keywords').removeAttr('disabled','disabled');
					$('.display-time').html('');
					$('.total-count').val(0);
					$('.show-total-count').html(0);
					getDfsLanguagesDetail(language);
					getDfsLocations('','','ideas');
					return false;
				}

				$(".keyword-explorer").load('/keyword_explorer_records/ideas', function(responseTxt, statusTxt, xhr){
					$('.selectpicker').selectpicker('refresh');
					if(statusTxt == "success")
						if($('.category').val() == 1){
							$('.searchByKeyword').addClass('uk-active');
							$('.searchByDomain').removeClass('uk-active');
							$('.detail_query_field').val($('.search_term').val());
							$('.keyword-locations').val(response['location_name']);
							$('#searchDomain').removeClass('uk-active');
							$('#searchKeyword').addClass('uk-active');
						}else if($('.category').val() == 2){
							$('.searchByKeyword').removeClass('uk-active');
							$('.searchByDomain').addClass('uk-active');
							$('.detail_domain_query_field').val($('.search_term').val());
							$('.domain-locations').val(response['location_name']);
							$('#searchDomain').addClass('uk-active');
							$('#searchKeyword').removeClass('uk-active');
						}

						$('#ke-table').removeClass('remove-hover-effect');
						getKeywordResponse(response['id']);
						getDfsLanguagesDetail(language);
						getDfsLocations('','','ideas');
						$('.detail_find_keywords').removeAttr('disabled','disabled');

						if(statusTxt == "error")
							console.log("Error: " + xhr.status + ": " + xhr.statusText);
					});
				
			}
		});
	}
});

$(document).on('changed.bs.select', '#detail_keyword_language', function () {
	var dataTypeAttribute = $('option:selected', this).attr("language_id");
	$('.detail_keyword_language_id').val('');
	$('.detail_keyword_language_id').val(dataTypeAttribute);
});

$(document).on('click','.show-history',function(e){
	e.preventDefault();
	var user_id = $('#user_id').val();
	$('.keyTabs').find('.active').removeClass('active');
	$(this).addClass('active');
	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_fetch_user_history',
		data:{user_id},
		dataType:'json',
		success:function(response){
			$('#clear_search_history').attr('data-user-id',user_id);
			$('#clear_search_history').attr('data-history-count',response['count']);
			$('.scroll-history').html('');
			if(response['count'] == 0){
				$('#clear_search_history').addClass('disabled');
			}else{
				$('#clear_search_history').removeClass('disabled');
			}
			$('.scroll-history').html(response['html']);
		}
	});
});

$(document).on('click','#clear_search_history',function(e){
	e.preventDefault();
	if($(this).attr('data-history-count') > 0){
		var user_id = $(this).attr('data-user-id');
		$.ajax({
			type:'GET',
			data:{user_id},
			url:BASE_URL+'/ajax_clear_search_history',
			dataType:'json',
			success:function(response){
				$('.scroll-history').html('');
				$('.scroll-history').html(response['html']);
				$('#clear_search_history').addClass('disabled');
			}
		});
	}
});

$(document).on('click','#create_list',function(e){
	e.preventDefault();

	var checked =[];
	$("input[name='select_keywords[]']:checked").each(function () {
		checked.push(parseInt($(this).attr('data-id')));
	});

	$.ajax({
		type:'POST',
		data:{user_id:$('#user_id').val(),campaign_id:$('.campaign_id').val(),name:$('.create-list-name').val(),_token:$('meta[name="csrf-token"]').attr('content'),checked:checked,flag:'new'},
		url:BASE_URL+'/ajax_create_keyword_list',
		success:function(response){
			Command: toastr["success"](response['text']);
			$('.keywordList-popup').find('.uk-close').trigger('click');
			$('.existingList-li').addClass('uk-active');
			$('.newList-li').removeClass('uk-active');
			$('#existingList').addClass('uk-active');
			$('#newList').removeClass('uk-active');
			$('.list-name').val('');
			fetch_listing($('#user_id').val());
			call_kw_api($('.search_term').val(),$('.location').val(),$('.language').val(),$('.campaign_id').val(),$('#user_id').val(),$('.category').val());
		}
	});
});	

$(document).on('click','#add_to_list',function(e){
	fetch_listing($('#user_id').val());
});

function fetch_listing(user_id){
	$.ajax({
		type:'GET',
		data:{user_id:$('#user_id').val()},
		url:BASE_URL+'/ajax_fetch_keyword_list',
		success:function(response){
			$('.single').removeClass('ajax-loader');
			$('#display-keyword-list').html('');
			$('#display-keyword-list').html(response);
		}
	});
}

$(document).on('click','.select-keyword-list',function(e){
	e.preventDefault();
	$('#display-keyword-list').find('.active').removeClass('active');
	$(this).addClass('active');
});

$(document).on('click','#add_to_list_btn',function(e){
	e.preventDefault();
	var checked =[];
	$("input[name='select_keywords[]']:checked").each(function () {
		checked.push(parseInt($(this).attr('data-id')));
	});

	$.ajax({
		type:'POST',
		data:{user_id:$('#user_id').val(),campaign_id:$('.campaign_id').val(),name:$('.select-keyword-list.active').attr('data-name'),list_id:$('.select-keyword-list.active').attr('data-selected-id'),_token:$('meta[name="csrf-token"]').attr('content'),checked:checked,flag:'existing'},
		url:BASE_URL+'/ajax_create_keyword_list',
		success:function(response){
			Command: toastr["success"](response['text']);
			$('.keywordList-popup').find('.uk-close').trigger('click');
			$('.existingList-li').addClass('uk-active');
			$('.newList-li').removeClass('uk-active');
			$('#existingList').addClass('uk-active');
			$('#newList').removeClass('uk-active');
			$('.list-name').val('');
			fetch_listing($('#user_id').val());
			call_kw_api($('.search_term').val(),$('.location').val(),$('.language').val(),$('.campaign_id').val(),$('#user_id').val(),$('.category').val());
		}
	});
});	

$(document).on('keyup','.search-in-list',function(){
	var table = $('#display-keyword-list')
	table.find('li').each(function(index, row) {
		var allDataPerRow = $(row);
		if (allDataPerRow.length > 0) {
			var found = false;
			allDataPerRow.each(function(index, td) {
				var regExp = new RegExp($(this).val(), "i");

				if (regExp.test($(td).text())) {
					found = true
					return false;
				}
			});
			if (found === true) {
				$(row).show();
			} else {
				$(row).hide();
			}
		}
	});
});

$(document).on('click','.select-history',function(e){
	e.preventDefault();
	$('.single').removeClass('active');
	$(this).addClass('active');
	$('.search_term').val($(this).find('.keyword-data').attr('data-keyword'));
	$('.location').val($(this).find('.keyword-data').attr('data-location-id'));
	$('.language').val($(this).find('.keyword-data').attr('data-language-id'));
	$('.category').val($(this).find('.keyword-data').attr('data-category'));
	$('.keyTabs').find('.active').removeClass('active');
	$('#offcanvas-History').removeClass('uk-open');
	call_kw_api($('.search_term').val(),$('.location').val(),$('.language').val(),$('.campaign_id').val(),$('#user_id').val(),$('.category').val());
});

$(document).on('click','.remove-from-list',function(e){
	e.preventDefault();
	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_remove_keyword_from_list',
		dataType:'json',
		data:{list_id:$('.tooltip-content').attr('data-list-id'),keyword_id :$('.tooltip-content').attr('data-keyword-id')},
		success:function(response){
			if(response['status'] == 1){
				Command: toastr["success"](response['message']);
				call_kw_api($('.search_term').val(),$('.location').val(),$('.language').val(),$('.campaign_id').val(),$('#user_id').val(),$('.category').val());
			}else if(response['status'] == 0){
				Command: toastr["error"](response['message']);
			}
		}
	});
});

$(document).on('click','.show-list',function(e){
	e.preventDefault();
	var user_id = $('#user_id').val();
	$('.keyTabs').find('.active').removeClass('active');
	$(this).addClass('active');
	showList(user_id,'','list');
});

function showList(user_id,search_keyword,type){
	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_fetch_lists',
		data:{user_id,search_keyword,type},
		dataType:'json',
		success:function(response){
			$('.scroll-list').html('');
			if(response['count'] == 0){
				$('.scroll-list').html(response['html']);
			}

			if(response['count'] == 1){
				$('.searchBox').css('display','none');
				$('.scroll-list').html(response['html']);
			}

			if(response['count'] > 0){
				$('.searchBox').removeAttr('style');
				$('.scroll-list').html(response['html']);
			}			
		}
	});
}

$(document).on('click','.rename-the-list',function(e){
	e.preventDefault();
	$('.list-name').addClass('readonly');
	$('.list-name').attr('readonly','true');

	var inputObj = $(this).closest('.single').find('.list-name');

	inputObj.removeClass('readonly');
	inputObj.removeAttr('readonly');	
	inputObj.focus();

	var list_id = $(this).attr('data-id');
	var preText = inputObj.val();

	inputObj.keyup(function(event){
	    if(event.which === 13) { // press ENTER-key
	    	var text = $(this).val();
	    	if(text !== ''){
	    		if(preText.trim() !== text){
	    			updateListName(text,list_id);
	    			inputObj.val(text);
	    			inputObj.removeAttr('style');
	    		}

	    		$('.list-name').addClass('readonly');
	    		$('.list-name').attr('readonly','true');
	    	}else{
	    		inputObj.css('border','1px solid red');
	    	}
	    }else if(event.which === 27) {  // press ESC-key
	    	inputObj.val(preText);
	    	$('.list-name').addClass('readonly');
	    	$('.list-name').attr('readonly','true');
	    }
	});

	inputObj.click(function(){
		return false;
	});
});



function updateListName(text,list_id){
	$.ajax({
		type:'POST',
		data:{text,list_id,_token:$('meta[name="csrf-token"]').attr('content')},
		url:BASE_URL+'/ajax_update_list_name',
		dataType:'json',
		success:function(response){
			if(response['status'] == 0){
				Command: toastr["error"](response['message']);
			}

			if(response['status'] == 1){
				Command: toastr["success"](response['message']);
			}
		}
	});
}

$(document).on('click','.export-list',function(e){
	e.preventDefault();
	var list_id = $(this).attr('data-id');
	var list_name = $(this).closest('.single').find('.list-name').val();
	var url = BASE_URL +"/ajax_export_keyword_list?list_name=" +list_name+"&list_id="+list_id;
	window.open(url, '_blank');
});

$(document).on('click','.delete-list',function(e){
	e.preventDefault();
	$('#showPopup').trigger('click');
	$('#showPopup').css('display', 'block');
	$('body').addClass('popup-open');
	$('.display-list-name').text($(this).attr('data-name'));
	$('#DeleteList').attr('data-list-id',$(this).attr('data-id'));
	$('#DeleteList').attr('data-list-name',$(this).attr('data-name'));
	$('#DeleteList').attr('data-list-type','ideas');
});


$(document).on('click','#DeleteList',function(){
	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_delete_list',
		data:{list_id:$(this).attr('data-list-id'),list_name:$(this).attr('data-list-name')},
		dataType:'json',
		success:function(response){
			Command: toastr["success"](response['message']);
			$('#showPopup').css('display', 'none');
			$('body').removeClass('popup-open');
			$('.single').addClass('ajax-loader');
			$('.search_keywords').trigger('click');
			fetch_listing($('#user_id').val());
		}
	});
});

$(document).on('keyup','.search-in-listing',delay(function (e) {
	showList($('#user_id').val(),$(this).val(),'search');
}, 1500));

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

$(document).on('click','.select-list',function(){
	var list_id = $(this).find('.list-name').attr('data-list-id');
	var list_name = $(this).find('.list-name').val();
	$(".keyword-explorer").load('/keyword_explorer_records/listing', function(responseTxt, statusTxt, xhr){
		if(statusTxt == "success")
			$('.keyword-list-id').val(list_id);
		$('.listing-name').val(list_name);
		$('.keywordList-close').trigger('click');
		getSelectedListData(list_id);

		if(statusTxt == "error")
			console.log("Error: " + xhr.status + ": " + xhr.statusText);
	});
});

function getSelectedListData(list_id){
	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax-get-listing',
		data:{list_id},
		dataType:'json',
		success:function(response){
			$('.ke-table-data').html(response['data']);
			$('.kw-stats').removeClass('ajax-loader');
			$('#search_sum').html(response['search_sum']);
			$('#avg_top_bid_high').html(response['avg_top_bid']);
			$('#avg_ci').html(response['avg_ci']);
			$('#ke-table').removeClass('remove-hover-effect');
			setTimeout(function(){
				showTooltip();
				for(var i=1;i <= response['count'];i++){
					var chart_id = 'myChart'+i;
					showChart(chart_id,response['ids'][i-1]);
				}
			},2000); 
			$('.listing-show-total-count').html(response['count']);
			triggerHeight();
		}
	});
}


$(document).on('click','.rename-listing',function(e){
	e.preventDefault();

	$('.uk-inline').find('.uk-open').removeClass('uk-open');
	var inputObj = $(this).closest('.list-result').find('.listing-name');
	inputObj.removeClass('readonly');
	inputObj.removeAttr('readonly');	

	inputObj.focus();

	var list_id = $('.keyword-list-id').val();
	var preText = inputObj.val();

	inputObj.keyup(function(event){
	    if(event.which === 13) { // press ENTER-key
	    	var text = $(this).val();
	    	if(text !== ''){
	    		if(preText.trim() !== text){
	    			updateListName(text,list_id);
	    			inputObj.val(text);
	    			inputObj.removeAttr('style');
	    		}

	    		$('.listing-name').addClass('readonly');
	    		$('.listing-name').attr('readonly','true');
	    	}else{
	    		inputObj.css('border','1px solid red');
	    	}
	    }else if(event.which === 27) {  // press ESC-key
	    	inputObj.val(preText);
	    	$('.listing-name').addClass('readonly');
	    	$('.listing-name').attr('readonly','true');
	    }
	});

	inputObj.click(function(){
		return false;
	});
});

$(document).on('click','.delete-listing',function(e){
	e.preventDefault();
	$('#showPopup').trigger('click');
	$('#showPopup').css('display', 'block'); 
	$('body').addClass('popup-open');
	$('.display-list-name').text($('.listing-name').val());
	$('#DeleteList').attr('data-list-id', $('.keyword-list-id').val());
	$('#DeleteList').attr('data-list-name',$('.listing-name').val());
	$('#DeleteList').attr('data-list-type','listing');
});

function showTooltip(){
	$(".star-tooltip").hover(
		function () {
			$(this).addClass("is_visible");
		},
		function () {
			$(this).removeClass("is_visible");
		}
		);


	$(".keywordsTable").find("table tbody > tr").each(function () {
		var tdHeight = $(this).find("td:first-child").outerHeight();
		var tdWidth = $(this).find("td:first-child").outerWidth();
		var actionStarBtn = $(this).find("td .uk-flex a.marked.active");
		var table = $(".keywordsTable");

		actionStarBtn.hover(function () {
			var data_content = actionStarBtn.find('.tooltip-c').html();
			var finalString = data_content.slice(1, -1);
			var json = JSON.parse(finalString);
			var htmlData = '';
			for (var i = 0; i < json.length; i++) {
				var k_id = json[i]['keyword-id'];
				var l_id = json[i]['list-id'];
				var l_name = json[i]['list_name'];
				htmlData += '<span class="tooltip-content" data-list-id="'+l_id+'" data-keyword-id="'+k_id+'"><a href="javascript:void(0)"><i class="fa fa-star"></i><span>'+l_name+'</span></a><a href="javascript:void(0)" class="remove-from-list" title="Delete from list"><span uk-icon="close"></span></a></span>';
			}

			$('.star-tooltip').html('<div>'+htmlData+'</div>');		
			$(".star-tooltip").toggleClass("is_visible");
			var Left = 47;
			var Top = actionStarBtn.offset().top - table.offset().top - 2;
			$(".star-tooltip").css({
				"top": Top,
				"left": Left
			});
		});
	});
}

function showTooltipGraph(){
	$(".bar-canvas .refresh").removeClass("show-spinner");
	var actionBtnCanvas = $('.graph_tooltip');
	var actionCanvasPopup = $(".bar-canvas-popup");
	var table = $(".keywordsTable");
	var keyword_name = actionBtnCanvas.find('.sv-trend-data').attr('data-name');
	var data_content = actionBtnCanvas.find('.sv-trend-data').html();
	var json = JSON.parse(data_content);
	var htmlData = '<div class="inner"><h6>'+keyword_name+'</h6><div class="canvas-graph"><canvas id="popup-graph" width="630" height="150"></canvas></div></div>';

	$('.bar-canvas-popup').html('<div>'+htmlData+'</div>');		

	var Left = actionBtnCanvas.offset().left - table.offset().left - (actionCanvasPopup.width() / 2) + (actionBtnCanvas.width() / 2);
	var Top = (actionBtnCanvas.offset().top - table.offset().top - actionCanvasPopup.height() - (actionBtnCanvas.height() / 2))+17;

	$(".bar-canvas-popup").css({
		"top": Top,
		"left": Left
	});
	showChartTooltip(json);
}

function getMonthName(month){
	const d = new Date();
	d.setMonth(month-1);
	const monthName = d.toLocaleString("default", {month: "short"});
	return monthName;
}

function showChartTooltip(svTrend){
	response = [];
	labels = [];
	searches = [];
	svTrend.forEach((entry,index) => {

		if(entry.month == 13){
			labels[index]  =  entry.year+' Jan';
		}else{
			var monthNumber = entry.month;
			labels[index]  =  entry.year +' '+ getMonthName(monthNumber);
		}
		searches[index]  = entry.monthly_search;
	});
	response.push(labels);
	response.push(searches);
	DrawTooltipChart(response);
	$(".bar-canvas-popup").addClass("show_graph");
}

function DrawTooltipChart(response){
	var chart = new Chart(document.getElementById('popup-graph').getContext('2d'), {
		type: 'bar',
		data: {
			labels: response[0],
			datasets: [
			{
				data: response[1],
				backgroundColor: '#327aee',
				maxBarThickness:25
			}
			]
		},
		options: {
			hover: {
				mode: 'dataset',
			},
			click: {
				mode: 'dataset',
			},
			responsive: false,
			legend: {display: false},
			tooltips: {enabled: false},
			scales: {
				yAxes: [
				{
					display: true,
					ticks: {
						autoSkip: true,
						maxTicksLimit: 4
					}
				}
				],
				xAxes: [
				{
					display: true,
					gridLines: {
						color: "rgba(0, 0, 0, 0)",
					},
					// ticks: {
					// 	major: {
					// 		enabled: true
					// 	},
					// 	source: 'data',
					// 	// stepSize: 3,
     //       				min: 0,
					// 	autoSkip: true ,
					// 	autoSkipPadding: 2,
					// 	maxTicksLimit: 4
					// }
					ticks: {
						autoSkip : true,
						autoSkipPadding: 2,
						callback: function(value, index, values) {
							return new moment(value).format("MMM'YY");
						}
					},
				}
				]
			},
			animation: {
				duration: 500
			}
		}
	});
}

function scrollTable(){
	//$(document).ready(function(){
		$(".keywordsTable .project-table-body").on('scroll',function(){
			var counter = parseInt($('#scroll-counter').val());
			if(($(this).scrollTop() + $(this).innerHeight()) >= ($(this)[0].scrollHeight  - (50 * counter))) {
				var row = ($('#row').val());
				var allcount = ($('#total-count').val());
				var rowperpage = 50;
				row = parseInt(row) + parseInt(rowperpage);
				if($('#scroll-status').val() === 'completed'){
					$('#scroll-status').val('in-progress');
					if(row <= allcount){
						$('#row').val(row);
						$('#scroll-counter').val(counter +1)
						getKeywordResponseLoad($('.keyword-search-id').val(),row);
					}
				}
			}
		});
	//});
}

function getKeywordResponseLoad(id,row){
	$('.animation-load').show();
	var start = row+1;
	var end = row+50;
	$.ajax({
		type:'GET',
		url:BASE_URL+'/get_keyword_response/'+id+'/'+row,
		dataType:'json',
		success:function(response){
			$(".ke-table-data tr:last").after(response['data']).show().fadeIn("slow");
			$('#scroll-status').val('completed');
			$('#custom-tooltip-text').removeClass('ajax-loader');
			$('.display-time').html(response['searched_date']);
			//$('.display-time').after(response['tooltip_text']);
			$('.keyword-search-id').val(id);
			setTimeout(function(){
				showTooltip();
				var i = 1;
				for(start;start <= end;start++){
					var chart_id = 'myChart'+start;
					showChart(chart_id,response['ids'][i-1]);
					i++;
				}
			},1500);
			$('.show-total-count').html(response['show_counter']);
			$('#total-count').val(response['total']);
			$('.animation-load').hide();
		}
	});
}

function triggerHeight(){
	//$(document).ready(function () {   
		var keywordsTable = $(".keywordsTable .project-table-body");
		var tableFooter = $(".keywordsTable .tableFooter").innerHeight();
		var maxHeight = keywordsTable.offset().top + tableFooter + 110;
		keywordsTable.css('max-height', 'calc(100vh - ' + maxHeight+ 'px)');
	//});
}

$(document).on('click','.refresh-keyword-data',function(){
	var kw_search_id = $(this).attr('data-keyword-id');
	$(".keyword-explorer").load('/keyword_explorer_records/ideas', function(responseTxt, statusTxt, xhr){
		if(statusTxt == "success")
			console.log("success: " + xhr.status + ": " + xhr.statusText);
		$.ajax({
			type:'GET',
			url:BASE_URL+'/ajax_get_refreshed_data',
			data:{kw_search_id},
			dataType:'json',
			success:function(response){
				$('.detail_find_keywords').attr('disabled','disabled');
				$('.selectpicker').selectpicker('refresh');
				if($('.category').val() == 1){
					$('.searchByKeyword').addClass('uk-active');
					$('.searchByDomain').removeClass('uk-active');
					$('.detail_query_field').val($('.search_term').val());
					$('.keyword-locations').val(response['location_name']);
					$('#searchDomain').removeClass('uk-active');
					$('#searchKeyword').addClass('uk-active');
				}else if($('.category').val() == 2){
					$('.searchByKeyword').removeClass('uk-active');
					$('.searchByDomain').addClass('uk-active');
					$('.detail_domain_query_field').val($('.search_term').val());
					$('.domain-locations').val(response['location_name']);
					$('#searchDomain').addClass('uk-active');
					$('#searchKeyword').removeClass('uk-active');
				}

				if(response['status'] == 'error'){
					// Command: toastr["error"]('Try again later');
					$('#ke-table').removeClass('remove-hover-effect');
					$('.ke-table-data').find('.ajax-loader').removeClass('ajax-loader');
					$('.ke-table-data').html('');
					$('.ke-table-data').html(response['html']);
					$('.detail_find_keywords').removeAttr('disabled','disabled');
					getDfsLanguagesDetail($('.language').val());
					getDfsLocations('','','ideas');
					$('.display-time').html('');
					$('#custom-tooltip-text').removeClass('ajax-loader');
					return false;
				}
				if(response['status'] !== 'error'){
					getKeywordResponse(response['id']);
					getDfsLanguagesDetail($('.language').val());
					getDfsLocations('','','ideas');
					$('.detail_find_keywords').removeAttr('disabled','disabled');
					$('#ke-table').removeClass('remove-hover-effect');
					triggerHeight();
				}
			}
		});
		if(statusTxt == "error")
			console.log("Error: " + xhr.status + ": " + xhr.statusText);
	});
});