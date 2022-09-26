var BASE_URL = $('.base_url').val();


$(document).ready(function(){
	if ($('#all_section').find('.all-main-data').length == 0) {
		$("#all_section").load('/all_alerts_content', function(responseTxt, statusTxt, xhr){
			if(statusTxt == "success")
				var page = $('#alerts_all_page').val();
				var limit = $('#alerts_all_limit').val();
				var query = $('#alerts_search').val();
				fetch_alerts_data(page,limit,query);


			if(statusTxt == "error")
				console.log("Error: " + xhr.status + ": " + xhr.statusText);
		});
		
	}
});


function fetch_alerts_data(page,limit,query){
	$('#all_alerts tbody tr td').addClass('ajax-loader');
	$('.all-alerts-text').addClass('ajax-loader');
	$('.allAlerts').addClass('ajax-loader');
	$.ajax({
		url:BASE_URL +'/ajax_fetch_alerts_list',
		type:'GET',
		data:{page,limit,query},
		success:function(response){
			$('#all_alerts tbody').html(response);
			$('.ajax-loader').removeClass('ajax-loader');
			$('#refresh-alerts-search').css('display','none');
		}
	});

	$.ajax({
		url:BASE_URL +'/ajax_fetch_alerts_pagination',
		type:'GET',
		data:{page,limit,query},
		success:function(response){
			$('.all-alerts-foot').html(response);
			$('.all-alerts-text').removeClass('ajax-loader');
			$('.allAlerts').removeClass('ajax-loader');
		}
	});
}

/*limit functionality*/
$(document).on('change','#alerts_limit',function(e){
	e.preventDefault();
	$('#alerts_all_limit').val($(this).val());
	load_section();
});

/* search functionality*/
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

$(document).on('keyup','#alerts_search',function(){
	$('#refresh-alerts-search').css('display','block');
});

$('#alerts_search').keyup(delay(function (e) {
	if($('#alerts_search').val() != '' || $('#alerts_search').val() != null){
		$('.alerts-search-clear').css('display','block');
	}

	if($('#alerts_search').val() == '' || $('#alerts_search').val() == null){
		$('.alerts-search-clear').css('display','none');
	}
	load_section();
}, 500));


$(document).on('click','.AlertsClear',function(e){
	e.preventDefault();
	$('#alerts_search').val('');
	if($('#alerts_search').val() == '' || $('#alerts_search').val() == null){
		$('.alerts-search-clear').css('display','none');
		load_section();
	}
});

function load_section(){
	//if all tab is enabled
	if($('#AlertsTab li.uk-active').text() == 'All'){
			$("#all_section").load('/all_alerts_content', function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					var page = 1;
					var limit = $('#alerts_all_limit').val();
					var query = $('#alerts_search').val();
					fetch_alerts_data(page,limit,query);


				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});
		}

		//if positive tab is enabled
		if($('#AlertsTab li.uk-active').text() == 'Positive'){
			$("#positive_section").load('/positive_alerts_content', function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					var page = 1;
					var limit = $('#alerts_all_limit').val();
					var query = $('#alerts_search').val();
					fetch_positive_alerts_data(page,limit,query);


				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});			
		}
	
		//if negative tab is enabled
		if($('#AlertsTab li.uk-active').text() == 'Negative'){
			$("#negative_section").load('/negative_alerts_content', function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					var page = 1;
					var limit = $('#alerts_all_limit').val();
					var query = $('#alerts_search').val();
					fetch_negative_alerts_data(page,limit,query);


				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});
		}
}

/*pagination*/
$(document).on('click','.allAlerts a',function(e){
	e.preventDefault();
	$('li').removeClass('active');
	$(this).parent().addClass('active');

	var page = $(this).attr('href').split('page=')[1];
	$('#alerts_all_page').val(page);
	var limit = $('#alerts_all_limit').val();
	var query = $('#alerts_search').val();
	
	fetch_alerts_data(page,limit,query);
	$('html,body').animate({
		scrollTop: $("#all_alerts").offset().top
	},'slow');

});


/*may 10*/
$(document).on('click','.selectedAlerts',function(e){
	e.preventDefault();
	var selected = $("a",this).attr('href');
	if(selected == '#All'){
		if ($('#all_section').find('.all-main-data').length >= 0) {
			$("#all_section").load('/all_alerts_content', function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					var page = $('#alerts_all_page').val();
					var limit = $('#alerts_all_limit').val();
					var query = $('#alerts_search').val();
					fetch_alerts_data(page,limit,query);


				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});
			
		}
	}

	if(selected == '#Positive'){
		if ($('#positive_section').find('.positive-main-data').length >= 0) {
			$("#positive_section").load('/positive_alerts_content', function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					var page = $('#alerts_positive_page').val();
					var limit = $('#alerts_all_limit').val();
					var query = $('#alerts_search').val();
					fetch_positive_alerts_data(page,limit,query);


				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});			
		}
	}

	if(selected == '#Negative'){
		if ($('#negative_section').find('.negative-main-data').length >= 0) {
			$("#negative_section").load('/negative_alerts_content', function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					var page = $('#alerts_negative_page').val();
					var limit = $('#alerts_all_limit').val();
					var query = $('#alerts_search').val();
					fetch_negative_alerts_data(page,limit,query);


				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});
		}
	}
});

/*positive section*/
function fetch_positive_alerts_data(page,limit,query){
	$('#positive_alerts tbody tr td').addClass('ajax-loader');
	$('.positive-alerts-text').addClass('ajax-loader');
	$('.PositiveAlerts').addClass('ajax-loader');
	$.ajax({
		url:BASE_URL +'/ajax_fetch_positive_alerts_list',
		type:'GET',
		data:{page,limit,query},
		success:function(response){
			$('#positive_alerts tbody').html(response);
			$('.ajax-loader').removeClass('ajax-loader');
			$('#refresh-alerts-search').css('display','none');
		}
	});

	$.ajax({
		url:BASE_URL +'/ajax_fetch_positive_alerts_pagination',
		type:'GET',
		data:{page,limit,query},
		success:function(response){
			$('.positive-alerts-foot').html(response);
			$('.positive-alerts-text').removeClass('ajax-loader');
			$('.PositiveAlerts').removeClass('ajax-loader');
		}
	});
}


$(document).on('click','.PositiveAlerts a',function(e){
	e.preventDefault();
	$('li').removeClass('active');
	$(this).parent().addClass('active');

	var page = $(this).attr('href').split('page=')[1];
	$('#alerts_positive_page').val(page);
	var limit = $('#alerts_all_limit').val();
	var query = $('#alerts_search').val();
	
	fetch_positive_alerts_data(page,limit,query);
	$('html,body').animate({
		scrollTop: $("#positive_alerts").offset().top
	},'slow');

});

/*negative section*/
function fetch_negative_alerts_data(page,limit,query){
	$('#negative_alerts tbody tr td').addClass('ajax-loader');
	$('.negative-alerts-text').addClass('ajax-loader');
	$('.NegativeAlerts').addClass('ajax-loader');
	$.ajax({
		url:BASE_URL +'/ajax_fetch_negative_alerts_list',
		type:'GET',
		data:{page,limit,query},
		success:function(response){
			$('#negative_alerts tbody').html(response);
			$('.ajax-loader').removeClass('ajax-loader');
			$('#refresh-alerts-search').css('display','none');
		}
	});

	$.ajax({
		url:BASE_URL +'/ajax_fetch_negative_alerts_pagination',
		type:'GET',
		data:{page,limit,query},
		success:function(response){
			$('.negative-alerts-foot').html(response);
			$('.negative-alerts-text').removeClass('ajax-loader');
			$('.NegativeAlerts').removeClass('ajax-loader');
		}
	});
}

$(document).on('click','.NegativeAlerts a',function(e){
	e.preventDefault();
	$('li').removeClass('active');
	$(this).parent().addClass('active');

	var page = $(this).attr('href').split('page=')[1];
	$('#alerts_negative_page').val(page);
	var limit = $('#alerts_all_limit').val();
	var query = $('#alerts_search').val();
	
	fetch_negative_alerts_data(page,limit,query);
	$('html,body').animate({
		scrollTop: $("#negative_alerts").offset().top
	},'slow');

});


