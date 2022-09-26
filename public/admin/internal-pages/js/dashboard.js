var BASE_URL = $('.base_url').val();

$(document).ready(function(){
	loadDefaultTable();
});

function loadDefaultTable(){
	var page = $('#dashboard_hidden_page').val();
	var limit = $('#dashboard_limit').val();
	var query = $('.account_search').val();
	
	fetch_account_data(page,limit,query);
}

function fetch_account_data(page,limit,query){
	$('#account-list tbody tr td').addClass('ajax-loader');
	$('.project-entries').addClass('ajax-loader');
	$('.pagination').addClass('ajax-loader');
	$.ajax({
		url:BASE_URL +'/admin/ajax_fetch_account_data',
		type:'GET',
		data:{page,limit,query},
		success:function(response){
			$('#account-list tbody').html(response);
			$('.ajax-loader').removeClass('ajax-loader');
			$('#admin-refresh-search').css('display','none');			
		}
	});

	$.ajax({
		url:BASE_URL +'/admin/ajax_fetch_account_pagination',
		type:'GET',
		data:{page,limit,query},
		success:function(response){
			$('.project-table-foot').html(response);
			$('.project-entries').removeClass('ajax-loader');
			$('.pagination').removeClass('ajax-loader');
		}
	});
}

/*search functionality*/
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

$(document).on('keyup','.account_search',function (e) {
	$('#admin-refresh-search').css('display','block');
});

$(document).on('keyup','.account_search',delay(function(e){
	e.preventDefault();
	if($('#account_search_id').val() != '' || $('#account_search_id').val() != null){
		$('.admin-dashboard-search-clear').css('display','block');
	}
	fetch_account_data(1,$('#dashboard_limit').val(),$(this).val());
},1000));

$(document).on('change','#admin_dashboard_accounts',function(){
	var page = 1;
	var limit = $(this).val();
	$('#dashboard_limit').val($(this).val());
	var query = $('.account_search').val();
	
	fetch_account_data(page,limit,query);
});

$(document).on('click','.AdminDashboardClear',function(e){
	e.preventDefault();
	$('.account_search').val('');
	if($('#account_search_id').val() == '' || $('#account_search_id').val() == null){
		$('.admin-dashboard-search-clear').css('display','none');
		var page = 1;
		var limit = $('#dashboard_limit').val();
		var query = $('.account_search').val();
		fetch_account_data(page,limit,query);
	}
});

$(document).on('click','.admin-account-dashboard a',function(e){
	e.preventDefault();
	$('li').removeClass('active');
	$(this).parent().addClass('active');

	var page = $(this).attr('href').split('page=')[1];
	$('#dashboard_hidden_page').val(page);

	var limit = $('#dashboard_limit').val();
	var query = $('.account_search').val();
	fetch_account_data(page,limit,query);

	$('html,body').animate({
		scrollTop: $("#account-list").offset().top
	},'slow');

});


$(document).on('click','.loginAsClient',function(e){
	$.ajax({
		type:'GET',
		dataType:'json',
		data:{user_id:$(this).attr('data-id')},
		url:BASE_URL +'/admin/login_as_client',
		success:function(res){
			var detailsWindow;
			if(res['status'] == 1){
		        detailsWindow = window.open(res['link'],"_self");
		        detailsWindow.onload = function () {
		            detailsWindow.document.getElementById('logged_in_as').value = res['prev'];
		        }
			}else{
				Command: toastr["error"]('Error!! Please try again.');
				return false;
			}

		}
	})
});