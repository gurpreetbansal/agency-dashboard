var BASE_URL = $('.base_url').val();

window.onload = function () {
	tinymce.init({
		selector: '#description',
		height:500,
		toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment'
	});


	tinymce.init({
		selector: '#update_description',
		height:500,
		toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment'
	});
}

$(document).ready(function(){
	loadDefaultTable();
});

function loadDefaultTable(){
	var page = $('#audit_hidden_page').val();
	var limit = $('#audit_limit').val();
	var query = $('.audit-list-search').val();
	
	fetch_audit_list_data(page,limit,query);
}

function fetch_audit_list_data(page,limit,query){
	$('#audit-error-list tr td').addClass('ajax-loader');
	$('.project-entries').addClass('ajax-loader');
	$('.pagination').addClass('ajax-loader');
	$.ajax({
		url:BASE_URL+'/admin/ajax_fetch_audit_list_data',
		type:'GET',
		data:{page,limit,query},
		success:function(response){
			$('#audit-error-list tbody').html(response);
			$('.ajax-loader').removeClass('ajax-loader');
			$('#admin-audit-refresh-search').css('display','none');
		}
	});


	$.ajax({
		url:BASE_URL +'/admin/ajax_fetch_audit_list_pagination',
		type:'GET',
		data:{page,limit,query},
		success:function(response){
			$('.project-table-foot').html(response);
			$('.project-entries').removeClass('ajax-loader');
			$('.pagination').removeClass('ajax-loader');
		}
	});
}


$(document).on('click','.admin-audit-list a',function(e){
	e.preventDefault();
	$('li').removeClass('active');
	$(this).parent().addClass('active');

	var page = $(this).attr('href').split('page=')[1];
	$('#audit_hidden_page').val(page);
	var limit = $('#audit_limit').val();
	var query = $('.audit-list-search').val();
	fetch_audit_list_data(page,limit,query);
	$('html,body').animate({
		scrollTop: $("#audit-error-list").offset().top
	},'slow');

});

$(document).on('change','#admin_audit_list',function(){
	var page = 1;
	var limit = $(this).val();
	$('#audit_limit').val($(this).val());
	var query = $('.audit-list-search').val();
	
	fetch_audit_list_data(page,limit,query);
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

$(document).on('keyup','.audit-list-search',function (e) {
	$('#admin-audit-refresh-search').css('display','block');
});

$(document).on('keyup','.audit-list-search',delay(function(e){
	e.preventDefault();
	if($('#audit_list_search').val() != '' || $('#audit_list_search').val() != null){
		$('.admin-audit-list-clear').css('display','block');
	}
	fetch_audit_list_data(1,$('#audit_limit').val(),$(this).val());
},1000));

$(document).on('click','.AuditListClear',function(e){
	e.preventDefault();
	$('.audit-list-search').val('');
	if($('#audit_list_search').val() == '' || $('#audit_list_search').val() == null){
		$('.admin-audit-list-clear').css('display','none');
		var page = 1;
		var limit = $('#audit_limit').val();
		var query = $('.audit-list-search').val();
		fetch_audit_list_data(page,limit,query);
	}
});