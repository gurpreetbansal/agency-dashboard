/*Site audit click to copy*/
var BASE_URL = $('.base_url').val();
var APP_URL = $('.app_url').val();


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

$('.single-site-audit').keyup(delay(function(event) {
	event.preventDefault()

	var url = ($('.run-site-audit').val()).toLowerCase();
	var domainame = url.replace(/^https?:\/\//, '');
	$('.run-site-audit').val(domainame);
	if (checkUrl(domainame)) {
		
		$('.red').show();
		$('.green').hide();
		$('.run-site-audit').addClass('error');
		return false;
	}
	
	if (event.keyCode === 13)
    {	
    	$('.run-audit').attr('disabled','disabled');
		var isSecured = $('#audit_url_type_input').val();
		var domainurl = isSecured + domainame;	
		if (!checkUrl(domainurl) ) {
			$('.red').show();
			$('.green').hide();
			$('.run-site-audit').addClass('error');
		}else{
			$('.green').show();
			$('.red').hide();
			$('.run-site-audit').removeClass('error');
		}

		if(!$('.run-site-audit').hasClass('error') && !$('.run-site-audit').hasClass('error')){
			auditSiteLoader();
			checkValidDomain(domainurl);
	    }else{
	    	$('.red').show();
			$('.green').hide();
			$('.run-site-audit').addClass('error');
	    	$('.run-audit').removeAttr('disabled');
	    }
   
    }else{
    	var isSecured = $('#audit_url_type_input').val();
		var domainurl = isSecured + domainame;	
    	isDomainExist(domainurl);
    }
},1000));



$(document).on('click','.run-audit',function(e){
	e.preventDefault();

	var domainame = ($('.run-site-audit').val()).toLowerCase();
	if (checkUrl(domainame)) {
		var urlReplace = domainame.replace(/^https?:\/\//, '');

		$('.run-site-audit').val(urlReplace);
		$('.red').show();
		$('.green').hide();
		$('.run-site-audit').addClass('error');
		return false;
	}

	$('.run-audit').attr('disabled','disabled');
	var isSecured = $('#audit_url_type_input').val();
	var domainurl = isSecured + domainame;	

	if (!checkUrl(domainurl) ) {
		$('.red').show();
		$('.green').hide();
		$('.run-site-audit').addClass('error');
	}else{
		$('.green').show();
		$('.red').hide();
		$('.run-site-audit').removeClass('error');
	}

	if(!$('.run-site-audit').hasClass('error') && !$('.run-site-audit').hasClass('error')){
		
		auditSiteLoader();
		checkValidDomain(domainurl);
	    
    }else{
    	$('.red').show();
		$('.green').hide();
		$('.run-site-audit').addClass('error');
    	$('.run-audit').removeAttr('disabled');
    }
  
});

$(document).on('click','.individual-refresh', function(e){

	$(this).addClass('refresh-gif');
	var pageAuditId =  $(this).attr('audit-id');
	Command: toastr["success"]('Audit request has been sent!');
	var pageType = $(this).data('type');
	if(pageType == 'detail-page'){
		pagesOverviewLoader();
		pagesSummaryLoader();
		pagesDetailLoader();
	}

	$.ajax({
		type:'get',
		url:BASE_URL +'/audit/detail/update/'+pageAuditId,
		dataType:'json',
		success:function(response){
			if(response['status'] == true){
				if(pageType == 'detail-page'){
					auditPageOverview(pageAuditId);
					auditPageSummary(pageAuditId);
					auditPageDetail(pageAuditId);
				}else{
					var auditType = '';
					if($('.sideAudit-page').find('.audit-type').length > 0){
						auditType = $('.audit-type').val();
					}
					auditPagesList(response['data']['audit_id'],1,'','',auditType);
				}
				
			}else{
				domainurl = response['url'];
			}
			$('.individual-refresh').removeClass('refresh-gif');
		}
	});
});

$(document).on('keyup','.projects_autocomplete',function(e){
  $('#refresh-sidebar-search').css('display','block');
   if($('.projects_autocomplete').val() != '' || $('.projects_autocomplete').val() != null){
    $('.sidebar-search-clear').css('display','block');
    searchByColumn($('.projects_autocomplete').val());
    $('#refresh-sidebar-search').css('display','none');
  }

  if($('.projects_autocomplete').val() == '' || $('.projects_autocomplete').val() == null){
    $('.sidebar-search-clear').css('display','none');
    searchByColumn($('.projects_autocomplete').val());
    $('#refresh-sidebar-search').css('display','none'); 
  }
});

$(document).on('click','.sidebarClear',function(e){
e.preventDefault();
  $('.projects_autocomplete').val('');
  if($('.projects_autocomplete').val() == '' || $('.projects_autocomplete').val() == null){
    $('.sidebar-search-clear').css('display','none');
    // sidebarScirpt();
    searchByColumn($('.projects_autocomplete').val());
    $('#refresh-sidebar-search').css('display','none');
  }
});



$(document).ready(function(e){

	scrollbar();

	if($('body').find('.detail-overview').length > 0){
		
		if($('.audit-id').val() !== ''){
			var auditType = '';
			if($('.sideAudit-page').find('.audit-type').length > 0){
				auditType = $('.audit-type').val();
			}
			auditSummary($('.audit-id').val(),auditType);
			auditPagesList($('.audit-id').val(),1,'','',auditType);
			siteAuditSummaryUpdate($('.audit-id').val());
		}else{
			checkValidDomain($('.url').val());
		}
		// auditOverviewChart();	
	}

	if($('body').find('.audit-page-details').length > 0){
		auditPageOverview($('.audit-id').val());
		auditPageSummary($('.audit-id').val());
		auditPageDetail($('.audit-id').val());

	}

	if($('body').find('.page-details').length > 0){
		auditPageOverview($('.audit-id').val());
		auditPageSummary($('.audit-id').val());
		auditPageDetail($('.audit-id').val());

	}

	if($('.sAudit-section').hasClass('pdf-download')){
		auditOverviewChart();
	}

	if($('.projectNavContainer').hasClass('audit-share-key')){
		var auditType = $('#audit-type').val();
		var auditId = $('#audit_id').val();
		auditSummary(auditId,auditType);
		auditPagesList(auditId,1,'','',auditType);
	}

	// $('.ajax-loader').removeClass('ajax-loader');


});

$(document).on("click",'#ShareKey', function (e) {
	e.preventDefault();
	$(".share-key-popup").addClass("open");
	var keys = $(this).attr('data-share-key');
	
	if($(this).hasClass('ShareKeyAudit')){
		if($(this).attr('data-share-key') !== ''){
			var getValue = APP_URL+'audit-share/'+$(this).attr('data-share-key');
			$('.copy_share_key_value').val(getValue);
			$('.project-id').val($(this).attr('data-id'));
		}else{
			var project_id = $(this).attr('data-id');
			reset_share_key(project_id);
		}
	}else{
		if($(this).attr('data-share-key') !== ''){
			var getValue = APP_URL+'project-detail/'+$(this).attr('data-share-key');
			$('.copy_share_key_value').val(getValue);
			$('.project-id').val($(this).attr('data-id'));
		}else{
			var project_id = $(this).attr('data-id');
			reset_share_key(project_id);
		}
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

$(document).on('click','.individual-audit',function(e){
	e.preventDefault();

	if($(this).data('page') == 'home'){
		if($('.viewkey-output').find('.campaign_id').length > 0){
			$('#seoDashboard').css('display','block');
		}else{
			$('.sa-audits').css('display','flex');
			$('.run-site-audit').val('');
		}
		
		$('.red').hide();
		$('.green').hide();
		$('#audit_url_type_input').val('https://');
		$('.audit-url-type-ul li').removeClass('active');
		$('.audit-url-type-ul li:first').addClass('active');
		$('.sa-audit-overview').css('display','none');
		$('.sa-audit-details').css('display','none');
		
	}else if($(this).data('page') == 'summary'){
		$('main').css('min-height','0px');
		
		
		if($('.audit-page-details').find('.page-pdf-download').length > 0){
			var urlpdf =  $('.page-pdf-download').attr('href');
			var finalurlpdf = urlpdf.substring(0, urlpdf.lastIndexOf('/'));
		}else{
			var urlpdf =  $('.viewkeypdf').attr('href');
			var finalurlpdf = urlpdf.substring(0, urlpdf.lastIndexOf('/'));
		}

		$('.viewkeypdf').attr('href',finalurlpdf+'/audit');
		$('.sa-audit-overview').css('display','block');
		$('.sa-audits').css('display','none');
		$('.sa-audit-details').css('display','none');
	}


});

$(document).on('click','.audit-pages-details',function(e){
	e.preventDefault();
	var auditId = $(this).data('audit');
	$('.individual-audit').attr('audit-id',auditId);
	auditDetailLoader(auditId);
	
});


$(document).on('click','.audit-pagination a',function(e){
	e.preventDefault();

	$('.audit-pagination ul li').removeClass('active');
	$(this).parent().addClass('active');
	var page = $(this).attr('href').split('page=')[1];
	var duration = $('.sc_duration').val();
	var selected = '';
	var auditType = '';
	if($('.sideAudit-page').find('.audit-type').length > 0){
		auditType = $('.audit-type').val();
	}
	if($('.audit-share-key').find('#audit-type').length > 0){
		auditType = $('#audit-type').val();
	}
	auditPagesList($('.audit-id').val(),page,duration,selected,auditType);
	
	$('html, body').animate({
        scrollTop: $('#auditTable').offset().top - 150
    }, 'smooth');
});



$(document).on('click','.site-audit-refresh',function(e){
	
	$('.audit-disable').attr('style', 'cursor: not-allowed');
	$('.audit-disable a').attr('style', 'pointer-events: none');
	$('#audit-limit-change').attr('disabled', true);
	$('#audit-limit-change').selectpicker('refresh');

	if($('.site-audit-refresh').attr('data-status') == 'progress'){
		Command: toastr["warning"]('Request Already in progress!');
		$('.audit-disable').attr('style', 'cursor: not-allowed');
		$('.audit-disable a').attr('style', 'pointer-events: none');
		$('#audit-limit-change').attr('disabled', true);
		$('#audit-limit-change').selectpicker('refresh');
		return false;
	}

	// Command: toastr["success"]('Audit request has been sent!');
	var auditId =  $(this).data('auditid');
	var auditurl =  $(this).data('auditurl');
	$(this).addClass('refresh-gif');
	$(this).attr('data-status','progress');

	$('#audit-limit-change').selectpicker('refresh');
	auditSummaryLoader();
	auditListsLoader();
	$.ajax({
		type:'POST',
		url:BASE_URL +'/audit/update/'+auditId,
		data:{_token:$('meta[name="csrf-token"]').attr('content'),url:auditurl},
		dataType:'json',
		success:function(response){
			if(response['status'] == true){
				if(response['availability'] !== true){	
					crowlPages(response['audit_id']);
				}
				auditSiteCheck(auditurl);
			}else{
				if(response['failer_type'] == 'expire'){
					Command: toastr["error"](response['message']);
					DomainExpired();
					$('.site-audit-refresh').removeClass('refresh-gif');
					return false;
				}else if(response['failer_type'] == 'process'){
					Command: toastr["warning"](response['message']);
					$('.site-audit-refresh').removeClass('refresh-gif');
					return false;
				}else{
					domainurl = response['url'];	
				}
				
			}
		}
	});
	/*setTimeout(function () {
		auditSiteCheck(auditurl);
	},10000);*/
	// checkValidDomain(auditurl);
});


$(document).on('change','#audit-limit-change',function(e){

	$('.audit-disable').attr('style', 'cursor: not-allowed');
	$('.audit-disable a').attr('style', 'pointer-events: none');
	$('#audit-limit-change').attr('disabled', true);
	$('#audit-limit-change').selectpicker('refresh');

	if($('.site-audit-refresh').attr('data-status') == 'progress'){
		Command: toastr["warning"]('Request Already in progress!');
		$('.audit-disable').attr('style', 'cursor: not-allowed');
		$('.audit-disable a').attr('style', 'pointer-events: none');
		$('#audit-limit-change').attr('disabled', true);
		$('#audit-limit-change').selectpicker('refresh');
		return false;
	}

	// Command: toastr["success"]('Audit request has been sent!');
	var auditId =  $('.site-audit-refresh').data('auditid');
	var auditurl =  $('.site-audit-refresh').data('auditurl');
	$('.site-audit-refresh').addClass('refresh-gif');
	$('.site-audit-refresh').attr('data-status','progress');

	var limit = $(this).val();
	auditSummaryLoader();
	auditListsLoader();
	$.ajax({
		type:'POST',
		url:BASE_URL +'/audit/update/'+auditId,
		data:{_token:$('meta[name="csrf-token"]').attr('content'),url:auditurl,limit:limit},
		dataType:'json',
		success:function(response){
			if(response['status'] == true){
				if(response['availability'] !== true){	
					crowlPages(response['audit_id']);
				}
				auditSiteCheck(auditurl);
			}else{
				if(response['failer_type'] == 'expire'){
					Command: toastr["error"](response['message']);
					DomainExpired();
					$('.site-audit-refresh').removeClass('refresh-gif');
					return false;
				}else{
					domainurl = response['url'];	
				}
				
			}
		}
	});
	
});


function scrollbar(){
 $(".sidebar nav ul.uk-nav-default:last-of-type .uk-nav-sub").mCustomScrollbar({
  axis: "y",
  advanced:{
    updateOnContentResize: true
  }
});
}

function searchByColumn(searchVal) {
    var table = $('#defaultCampaignList')
    table.find('li').each(function(index, row) {
        var allDataPerRow = $(row);
        if (allDataPerRow.length > 0) {
            var found = false;
            allDataPerRow.each(function(index, td) {
                var regExp = new RegExp(searchVal, "i");

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
}

function isDomainExist(domain){
	$.ajax({
		type:'POST',
		url:BASE_URL +'/audit/domain/validation',
		data:{_token:$('meta[name="csrf-token"]').attr('content'),domain:domain},
		dataType:'json',
		success:function(response){
			if(response['http_code'] == 200 || response['http_code'] == 301 || response['http_code'] == 302){
				$('.errorStyle').css('display','none');		
				$('.run-site-audit').removeClass('error');
				$('.red').hide();
				$('.green').show();
			}else{
				$('.errorStyle').css('display','block');		
				$('#domain_url_error').html('This domain does not exist!');		
				$('.run-site-audit').addClass('error');		
				$('.red').show();
				$('.green').hide();
			}
		}
	});
}

function checkValidDomain(domain){
	$.ajax({
		type:'POST',
		url:BASE_URL +'/audit/domain/validation',
		data:{_token:$('meta[name="csrf-token"]').attr('content'),domain:domain},
		dataType:'json',
		success:function(response){
			
			if(response['http_code'] == 200 || response['http_code'] == 301 || response['http_code'] == 302){
				$('.sa-audit-overview').css('display','block');
				$('.sa-audits').css('display','none');
				$('.sa-audit-details').css('display','none');
				if(response['http_code'] !== 200){
					domainurl = response['redirect_url'];
				}else{
					domainurl = response['url'];
				}
				var campaign_id = null;
				if($('.detail-overview').find('.campaign_id').length > 0){
					campaign_id = $('.campaign_id').val();
				}
				saRunSiteAudit(domainurl,campaign_id);
			}else{
				$('.errorStyle').css('display','block');		
				$('#domain_url_error').val('This domain does not exist any more!');	
				$('.sa-audits').css('display','block');
				$('.sa-audit-overview').css('display','none');
				$('.sa-audit-details').css('display','none');
				DomainExpired();
			}
			$('.run-audit').removeAttr('disabled');
			
		}

	});
}


function checkUrl(str)
{
	regexp = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
	if (regexp.test(str) && (str.indexOf("http://") == 0 || str.indexOf("https://") == 0))
	{
		return true;
	} else
	{
		return false;
	}
}

function auditOverviewChart(){
    var ctx = document.getElementById('audit-overview').getContext('2d');
	var gradient1 = ctx.createLinearGradient(0, 0, 0, 450);
	gradient1.addColorStop(0, 'rgba(250, 161,155, 1)'); //pink
	gradient1.addColorStop(0.3, 'rgba(253 ,198, 128, 1)'); //yellow
	gradient1.addColorStop(0.6, 'rgba(107 ,255 ,133, 1)');//green
	var siteAuditmyChart = new Chart(ctx, {
		type: 'doughnut',
		data: {
			datasets: [{
				label: '# of Votes',
				data: [],
				backgroundColor:[gradient1,'#eeeeee'],
				borderColor:[gradient1,'#eeeeee'],
				borderWidth: 1
			}]
		},
		options: {
			cutoutPercentage: 85,
			maintainAspectRatio: this.maintainAspectRatio,
			scales: {
				y: {
					beginAtZero: true
				}
			},
			tooltips: {
				enabled: false
			}
		}
	});
	var page_score = ($('.audit-overview-value').val()).toString();
	var left = (100 - page_score).toFixed(2);
	siteAuditmyChart.data.datasets[0].data = [page_score,left];
	siteAuditmyChart.update();
}

function auditDetailChart(){
    var ctx = document.getElementById('audit-pages').getContext('2d');
	var gradient1 = ctx.createLinearGradient(0, 0, 0, 450);
	gradient1.addColorStop(0, 'rgba(250, 161,155, 1)'); //pink
	gradient1.addColorStop(0.3, 'rgba(253 ,198, 128, 1)'); //yellow
	gradient1.addColorStop(0.6, 'rgba(107 ,255 ,133, 1)');//green
	var siteAuditmyChart = new Chart(ctx, {
		type: 'doughnut',
		data: {
			datasets: [{
				label: '# of Votes',
				data: [],
				backgroundColor:[gradient1,'#eeeeee'],
				borderColor:[gradient1,'#eeeeee'],
				borderWidth: 1
			}]
		},
		options: {
			cutoutPercentage: 85,
			maintainAspectRatio: this.maintainAspectRatio,
			scales: {
				y: {
					beginAtZero: true
				}
			},
			tooltips: {
				enabled: false
			}
		}
	});
	var page_score = ($('.audit-pages-value').val()).toString();
	var left = (100 - page_score).toFixed(2);
	siteAuditmyChart.data.datasets[0].data = [page_score,left];
	siteAuditmyChart.update();
}

function auditPagesList(audit_id,page,duration,selected,auditType = ''){
	$.ajax({
		type:'GET',
		data:{audit_id,page,duration,selected,auditType},
		url:BASE_URL +'/audit/list/'+audit_id,
		success:function(response){
			$('.audit-pages-list').html(response);
		}
	});
}

function auditSummary(audit_id,auditType){
	$.ajax({
		type:'GET',
		url:BASE_URL +'/audit/summary/'+audit_id+'?auditType='+auditType,
		success:function(response){
			$('.overviewBox').html(response);
			auditOverviewChart();
			$('.selectpicker').selectpicker('refresh');
		}
	});
}

function auditPageOverview(audit_id){
	$.ajax({
		type:'GET',
		url:BASE_URL +'/audit/page/detail-overview/'+audit_id,
		success:function(response){
			$('.detail-page-overview').html(response);
			// auditOverviewChart();
			// $('.selectpicker').selectpicker('refresh');
		}
	});
}

function auditPageSummary(audit_id){
	$.ajax({
		type:'GET',
		url:BASE_URL +'/audit/page/detail-summary/'+audit_id,
		success:function(response){
			$('.detail-page-summary').html(response);
			auditDetailChart();	
			// auditOverviewChart();
			// $('.selectpicker').selectpicker('refresh');
		}
	});
}

function auditPageDetail(audit_id){
	$.ajax({
		type:'GET',
		url:BASE_URL +'/audit/page/detail-data/'+audit_id,
		success:function(response){
			$('.detail-page-reports').html(response);
			// auditOverviewChart();
			// $('.selectpicker').selectpicker('refresh');
		}
	});
}

function siteAuditSummaryUpdate(audit_id){

	var campaign = null;
	if($('.detail-overview').find('.campaign_id').length > 0){
		campaign = $('.campaign_id').val();
	}

	if($('.viewkey-output').find('.campaign_id').length > 0){
		$('.right-icons').html('');
		campaign = $('.campaign_id').val();
	}
	$.ajax({
		type:'GET',
		url:BASE_URL +'/audit/summary/update/'+audit_id+'?campaign='+campaign,
		success:function(response){
			var auditType = '';
			if($('.viewkey-output').find('.audit-type').length > 0){
				auditType = $('.audit-type').val();	
			}

			if($('.sideAudit-page').find('.audit-type').length > 0){
				auditType = $('.audit-type').val();
			}

			if($('.viewkey-output').find('#audit-type').length > 0){
				auditType = $('#audit-type').val();
			}

			if(response['audit_status'] == 'completed'){
				
				var auditType = '';
				$('.audit-disable').attr('style', '');
				$('.audit-disable a').attr('style', '');
				$('#audit-limit-change').attr('disabled', false);
				$('#audit-limit-change').selectpicker('refresh');
				if($('.viewkey-output').find('.audit-type').length > 0){
					auditType = $('.audit-type').val();
					$('.viewkeypdf').attr('href',response['pdf_key']);
					auditSummary(response['id'],auditType);
					auditPagesList(response['id'],1,'','',auditType);
					$('.right-icons nav .ajax-loader').removeClass('ajax-loader');
					return false;
				}
			}

			if($('.circle-donut').find('.audit-overview-value').length == 0){
				auditSummary(response['id']);
				auditPagesList(response['id'],1,'','',auditType);
				
			}else{

				$('.percent_text').html(response['result']);
				$('.audit-overview-value').val(response['result']);
				
				var passedTest = parseInt(response['total_tests']) - (parseInt(response['criticals']) + parseInt(response['warnings']) + parseInt(response['notices']));
				
				var criticalsAvg = (parseInt(response['criticals']) / parseInt(response['total_tests'])) * 100;
				var warningsAvg = (parseInt(response['warnings']) / parseInt(response['total_tests'])) * 100;
				var noticesAvg = (parseInt(response['notices']) / parseInt(response['total_tests'])) * 100;
				var passedAvg = ((parseInt(response['total_tests']) - (parseInt(response['criticals']) + parseInt(response['warnings']) + parseInt(response['notices']))) / parseInt(response['total_tests'])) * 100;
				
				if(response['total_tests'] == 0){
					$('.criticals p').html('0 high issues');
					$('.warnings p').html('0 medium issues');
					$('.notices p').html('0 low issues');
					$('.passed p').html('0 tests passed');

					$('#criticalsAvg').html('0%');
					$('#warningsAvg').html('0%');
					$('#noticesAvg').html('0%');
					$('#passedAvg').html('0%');
				}else{
					$('.criticals p').html(response['criticals']+' high issues');
					$('.warnings p').html(response['warnings']+' medium issues');
					$('.notices p').html(response['notices']+' low issues');
					$('.passed p').html(passedTest+' tests passed');

					$('#criticalsAvg').html(criticalsAvg.toFixed(2) +'%');
					$('#warningsAvg').html(warningsAvg.toFixed(2) +'%');
					$('#noticesAvg').html(noticesAvg.toFixed(2) +'%');
					$('#passedAvg').html(passedAvg.toFixed(2) +'%');
				}

				$('.criticalsProgress').attr('value',criticalsAvg);
				$('.warningsProgress').attr('value',warningsAvg);
				$('.noticesProgress').attr('value',noticesAvg);
				$('.passedProgress').attr('value',passedAvg);

				$('#crawled-pages').html(response['crawledPages']);
				$('#noindex-pages').html(response['noindex']);
				auditOverviewChart();
			}

			var auditType = '';

			$('.campaign-pdf').attr('href',response['pdf_key']);
			$('.ShareKeyAudit').attr('data-share-key',response['share_key']);

			if($('.sideAudit-page').find('.audit-type').length > 0){
				auditType = $('.audit-type').val();
			}
			auditPagesList(response['id'],1,'','',auditType);

			if(response['audit_status'] == 'process'){
				setTimeout(function () {
					siteAuditSummaryUpdate(audit_id);
				},5000);
			}else{

				$('.audit-disable').attr('style', '');
				$('.audit-disable a').attr('style', '');
				$('#audit-limit-change').attr('disabled', false);
				$('#audit-limit-change').selectpicker('refresh');

				$('.progress-loader').hide();
				$('.site-audit-refresh').attr('data-status','completed');
				$('.site-audit-refresh').removeClass('refresh-gif');
			}
			$('.right-icons nav .ajax-loader').removeClass('ajax-loader');
		}
	});
}

function crowlPages(audit_id){

	$.ajax({
		type:"post",
		url:BASE_URL+"/site/audit/crowler",
		dataType:'json',
		data:{_token:$('meta[name="csrf-token"]').attr('content'),audit_id:audit_id},
		success:function(response){
			var auditType = '';
			if($('.sideAudit-page').find('.audit-type').length > 0){
				auditType = $('.audit-type').val();
			}

			$('.audit-disable').attr('style', 'cursor: not-allowed');
			$('.audit-disable a').attr('style', 'pointer-events: none');
			$('#audit-limit-change').attr('disabled', true);
			$('#audit-limit-change').selectpicker('refresh');
			auditPagesList(audit_id,1,'','',auditType);
		}

	});
}

function auditSiteCheck(domain){

	var auditType = '';
	if($('.sideAudit-page').find('.audit-type').length > 0){
		auditType = $('.audit-type').val();
	}

	$.ajax({
		type:"post",
		url:BASE_URL+"/audit/overview",
		dataType:'json',
		data:{_token:$('meta[name="csrf-token"]').attr('content'),domain:domain},
		success:function(result){
			if(result['status'] == false){
				setTimeout(function () {
					auditSiteCheck(domain);
				},3000);
			}else{
				$('.site-audit-refresh').attr('data-auditid',result['id']);
				$('.site-audit-refresh').attr('data-auditurl',result['url']);
				$('.summary-pdf-download').attr('href',result['pdf_url']);
				$('.ShareKeyAudit').attr('data-id',result['id']);
				$('.ShareKeyAudit').attr('data-share-key',result['share_key']);
				auditSummary(result['id'],'individual');
				auditPagesList(result['id'],1,'','',auditType);
				
				if(result['audit_status'] == 'process'){
					
					$('.site-audit-refresh').attr('data-status','progress');
					siteAuditSummaryUpdate(result['id']);
				}else{
					$('.audit-disable').attr('style', '');
					$('.audit-disable a').attr('style', '');
					$('#audit-limit-change').attr('disabled', false);
					$('#audit-limit-change').selectpicker('refresh');
					$('.site-audit-refresh').attr('data-status','completed');
				}
				
			}
		}
	});
}

function auditSummaryLoader(){
	$.ajax({
		type:"get",
		url:BASE_URL+"/site/summary/loader",
		// dataType:'json',
		success:function(result){
			$('.overviewBox').html(result);
		}
	});
}
function auditListsLoader(){
	$.ajax({
		type:"get",
		url:BASE_URL+"/site/lists/loader",
		// dataType:'json',
		success:function(result){
			$('.pagesBox').html(result);
		}
	});
}

function auditSiteLoader(domain){
	$.ajax({
		type:"get",
		url:BASE_URL+"/audit/loader/overview",
		// dataType:'json',
		success:function(result){
			$('.sa-audit-overview').html(result);
			if($('.viewkey-output').find('.campaign_id').length > 0){
				$('.right-icons').hide();
			}
			/*$('.sa-audit-overview').show();
			$('.sa-audits').hide();
			$('.sa-audit-details').hide();*/
		}
	});
}

function auditDetailLoader(auditId){
	var pageType = $('#page-type').val();
	$.ajax({
		type:"get",
		url:BASE_URL+"/audit/loader/page-detail/"+auditId+'?pageType='+pageType,
		// dataType:'json',
		success:function(result){
			$('.sa-audit-details').html(result);
			if($('.viewkey-output').find('.campaign_id').length > 0){
				$('.right-icons').hide();
				var urlpdf =  $('.page-pdf-download').attr('href');
				// var finalurlpdf = urlpdf.substring(0, urlpdf.lastIndexOf('/'));
				$('.viewkeypdf').attr('href',urlpdf);
			}

			$('.sa-audit-details').css('display','block');
			$('.sa-audit-overview').css('display','none');
			$('.sa-audits').css('display','none');

			$('#page-refresh').attr('audit-id',auditId);
			$('html').scrollTop(0);
			auditPageOverview(auditId);
			auditPageSummary(auditId);
			auditPageDetail(auditId);
		}
	});
}

function pagesOverviewLoader(){
	$.ajax({
		type:"get",
		url:BASE_URL+"/pages/overview/loader",
		// dataType:'json',
		success:function(result){
			$('.detail-page-overview').html(result);
		}
	});
}

function pagesSummaryLoader(){
	$.ajax({
		type:"get",
		url:BASE_URL+"/pages/summary/loader",
		// dataType:'json',
		success:function(result){
			$('.detail-page-summary').html(result);
		}
	});
}

function pagesDetailLoader(){
	$.ajax({
		type:"get",
		url:BASE_URL+"/pages/detail/loader",
		// dataType:'json',
		success:function(result){
			$('.detail-page-reports').html(result);
		}
	});
}

function auditSiteOverview(domain){
	$.ajax({
		type:"get",
		url:BASE_URL+"/audit/detail/"+domain,
		// dataType:'json',
		success:function(result){
			$('.sa-audit-overview').html(result);
			$('.sa-audit-overview').css('display','block');
			$('.sa-audits').css('display','none');
			auditOverviewChart();
		}
	});
}

function saRunSiteAudit(url,campaign_id = null){
	$.ajax({
		type:'POST',
		url:BASE_URL+'/site/audit/run',
		/*dataType:'json',*/
		// data:{url},
		data:{_token:$('meta[name="csrf-token"]').attr('content'),url:url,campaign_id:campaign_id},
		success:function(response){
			if(response['status'] == true){	
				if(response['availability'] !== true){	
					crowlPages(response['audit_id']);
				}
				auditSiteCheck(url);
			}
			
		}
	});
}

function viewKeyConnect(){

	var result = '<div class="white-box mb-40 " id="gmb-view"><div class="integration-list"><article><figure><img src="https://waveitdigital.com/public/vendor/internal-pages/images/gmb-img.png"></figure><div><p>The Site Audit is not run on this campaign.</p><a href="mailto:info@seobank.ca" class="btn btn-border blue-btn-border">Contact us</a></div></article></div></div>';
	$('.sa-audit-overview').html(result);

}
function DomainExpired(){

	$.ajax({
		type:"get",
		url:BASE_URL+"/audit/expire",
		// dataType:'json',
		success:function(result){
			$('.overviewBox').html(result);
			$('.pagesBox').html('');
			$('.right-icons').css('display','none');
		}
	});

}

$(".domain-dropDownBox>button").on("click", function(){
  $(".domain-dropDownMenu").toggleClass("show");
});

$(document).on('click','.audit-url-type-list',function(e){
  var selected = $(this).find('h6').text();
  $('.audit_url_type').text(selected);
  $('#audit_url_type_input').val(selected);
  $('#audit-url-dropDownMenu').removeClass('show');
  $('.audit-url-type-ul li').removeClass('active');
  $(this).addClass('active');
  e.stopPropagation();
});