var BASE_URL = $('.base_url').val();
var APP_URL = $('.app_url').val();
var color = Chart.helpers.color;
$(document).ready(function(e){
	
	var campaign_id = $('.campaign_id').val();

	if($('body').find('.audit-summery').length > 0){

		siteAuditStatus(campaign_id);
		var urlpdf =  $('.generate-pdf').attr('href');
		var finalurlpdf = urlpdf.substring(0, urlpdf.lastIndexOf('/'));
		$('.audit-pdf').attr('href',finalurlpdf+'/seo');
	}else if($('#SideAudit').find('.audit-summary').length > 0){
		
		siteAuditChartData();
		
	}else if($('#SideAuditDetail').find('.audit-summary').length > 0){
		
		siteAuditChartData();
		dInsightsAuditChartData();
		mInsightsAuditChartData();
	
	}else if($('body').find('.audit-page-list').length > 0){
		siteAuditPageLabel(campaign_id);
		siteAuditPages(campaign_id);
		siteAuditPagesSidebar(campaign_id);
		// $('.audit-content-inner').find('.ajax-loader').removeClass('ajax-loader');

	}else if($('body').find('.audit-details-page').length > 0){
		
		var page = $('.page_no').val();

		ajaxAuditDetailRightside(campaign_id);
		siteDetailOverview(campaign_id,page);
		siteDetailIssues(campaign_id,page);
		siteDetailLinks(campaign_id,page);
		siteDesktopInsights(campaign_id,page);
		siteMobileInsights(campaign_id,page);
		// $('.audit-content-inner').find('.ajax-loader').removeClass('ajax-loader');

	}
});


$(document).on("click",".show-more-audit-img", function () {
	$(".show-more-audit-imgmore .table-audit-collapseed").slideToggle();
	$(".show-more-audit-img").toggleClass("open");
	var Text = $(this).find("span.t")
	if (Text.text() == "Show More") {
		Text.text("Show Less");
	} else {
		Text.text("Show More");
	}
})

$(document).on("click",".show-more-audit-extlinks", function () {
	$(".show-more-audit-extlinksexpand .table-audit-collapseed").slideToggle();
	$(".show-more-audit-img").toggleClass("open");
	var Text = $(this).find("span.t")
	if (Text.text() == "Show More") {
		Text.text("Show Less");
	} else {
		Text.text("Show More");
	}
})

$(document).on("click",".show-more-audit-intlinks", function () {
	$(".show-more-audit-intlinksexpand .table-audit-collapseed").slideToggle();
	$(".show-more-audit-issues").toggleClass("open");
	var Text = $(this).find("span.t")
	if (Text.text() == "Show More") {
		Text.text("Show Less");
	} else {
		Text.text("Show More");
	}
})




function siteAuditStatus(campaign_id){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax-auditstatus/"+campaign_id,
		dataType:'json',
		success:function(result){
			
			if(result['summaryTask']['crawl_progress'] == 'in_progress' && (result['summaryTask']['domain_info'] == null || result['summaryTask']['page_metrics'] == null)){
				setTimeout(function () {
					siteAuditStatus(campaign_id);
				},2000);
			}else if(result['summaryTask']['crawl_progress'] == 'in_progress' && (result['summaryTask']['page_metrics'] !== null && result['summaryTask']['domain_info'] !== null)){
				setTimeout(function () {
					siteAuditSummary(campaign_id);
				},2000);
				siteAuditProgress(campaign_id);
				$('.progress-loader').show();
				$('#site-audit-renew').removeClass('refresh-gif');
			}else{
				if(result['summaryTask']['crawl_progress'] == 'finished' && (result['summaryTask']['domain_info']['total_pages'] == 0 && result['summaryTask']['domain_info']['crawl_stop_reason'] !== '')){
					siteAuditSummaryError(campaign_id);
				}else{
					siteAuditSummary(campaign_id);
					$('#site-audit-renew').removeClass('refresh-gif');
				}
			}
		}
	});
}	

function siteAuditProgress(campaign_id){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax-auditstatus/"+campaign_id,
		dataType:'json',
		success:function(result){
			if(result['summaryTask']['crawl_progress'] == 'in_progress'){
				
				$('.crawled-pages').html(result['summaryTask']['crawl_status']['pages_crawled']+'/'+result['summaryTask']['crawl_status']['max_crawl_pages']); 
				setTimeout(function () {
					siteAuditPageIssues(campaign_id);
					siteAuditProgress(campaign_id);
				}, 2000);
				$('.site-audit-renew').removeClass('refresh-gif');
				$('.progress-loader').show();
			}else{
				$('.progress-loader').hide();
				$('.site-audit-renew').removeClass('in-progress');
				siteAuditSummary(campaign_id);
			}
		}
	});
}

function siteAuditSummary(campaign_id){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax-auditsummary/"+campaign_id,
		
		success:function(result){
			$('.audit-summery').html(result);
			$('.audit_crawl_pages').selectpicker('refresh');
			siteAuditChartData();
			

		}
	});
}

function siteAuditPageIssues(campaign_id){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax-auditpageissues/"+campaign_id,
		
		success:function(result){
			$('#PageLevelIssues').html(result);
			// $('.audit_crawl_pages').selectpicker('refresh');
			// siteAuditChartData();
			

		}
	});
}

function siteAuditPageLabel(campaign_id){
	var filter = $('.filter').val();
	var label = '';
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax-auditpages-label/"+campaign_id+"?filter="+filter,
		dataType:'json',
		success:function(result){
			if(result['pages'] == 1){
				label = ' page';
			}else{
				label = ' pages';
			}

			$('.errors-types').html(result['filter']+' ('+ result['pages'] + label +')');
			$('.errors-description').html('All Urls with '+ result['filter'] +' errors.');
			$('.site-audit .heading').removeClass('ajax-loader');
			
		}
	});
}

function siteAuditPages(campaign_id){
	var filter = $('.filter').val();
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax-auditpages/"+campaign_id+"?filter="+filter,
		
		success:function(result){
			$('.page-list-bar').html(result);
		}
	});
}

function siteAuditPagesSidebar(campaign_id){
	var filter = $('.filter').val();
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax-auditpagesidebar/"+campaign_id+"?filter="+filter,
		
		success:function(result){
			$('.page-right-bar').html(result);
		}
	});
}


function ajaxAuditDetailRightside(campaign_id){
	$.ajax({
		type:"get",
		url:BASE_URL+"/ajax-auditdetail-rightside/"+campaign_id,
		success:function(result){
			$('.right-sidebar').html(result);
		}
	});
}

function siteDetailOverview(campaign_id,page){
	$.ajax({
		type:"get",
		url:BASE_URL+"/ajax-auditdetail-overview/"+campaign_id+"?page="+page,
		success:function(result){
			$('.details-overview').html(result);
			overviewChartSection();
		}
	});
}

function siteDetailIssues(campaign_id,page){
	$.ajax({
		type:"get",
		url:BASE_URL+"/ajax-auditdetail-issues/"+campaign_id+"?page="+page,
		success:function(result){
			$('.auditdetail-issues').html(result);
		}
	});
}

function siteDetailLinks(campaign_id,page){
	$.ajax({
		type:"get",
		url:BASE_URL+"/ajax-auditdetail-links/"+campaign_id+"?page="+page,
		success:function(result){
			$('.auditdetail-links').html(result);
		}
	});
}

function siteMobileInsights(campaign_id,page){
	$.ajax({
		type:"POST",
		url:BASE_URL+"/ajax-minsights/"+campaign_id,
		data:{_token:$('meta[name="csrf-token"]').attr('content'),'page':page},
		success:function(result){
			$('#insights-mobile').html(result);
			mInsightsAuditChartData();
		}
	});
}

function siteDesktopInsights(campaign_id,page){
	$.ajax({
		type:"POST",
		url:BASE_URL+"/ajax-dinsights/"+campaign_id,
		data:{_token:$('meta[name="csrf-token"]').attr('content'),'page':page},
		success:function(result){
			$('#insights-desktop').html(result);
			dInsightsAuditChartData();
		}
	});
}


$(document).on("click",".sidedrawererror", function () {

	$('#offcanvas-flip .progress-loader').show();
	$('.gbox .sidedrawer-label').html("");
	$('.sidedrawer-short-description p').html("");
	$('.sidedrawer-description').html("");

	var errorcat =  $(this).attr('data-type');
	var errortype =  $(this).attr('data-value');
	
	$('.gbox').removeClass('blue-gradient');
	$('.gbox').removeClass('red-gradient');
	$('.gbox').removeClass('yellow-gradient');
	
	if(errorcat == 'critical'){
		$('.gbox').addClass('red-gradient');
		$('.gbox h3 small').html('Critical');
		
	}else if(errorcat == 'warning'){
		$('.gbox').addClass('yellow-gradient');
		$('.gbox h3 small').html('Warnings');
		
	}else if(errorcat == 'notices'){
		$('.gbox').addClass('blue-gradient');
		$('.gbox h3 small').html('Notices');
	}

	$.ajax({
		type:"POST",
		url:BASE_URL+"/ajax-auditpageserrors",
		data:{_token:$('meta[name="csrf-token"]').attr('content'),errorcat:errorcat,errortype:errortype},
		dataType:'json',
		success:function(result){
			
			$('.gbox .sidedrawer-label').html(result['label']);
			if(result['status'] == true){
				$('.sidedrawer-short-description p').html(result['short_description']);
				$('.sidedrawer-description').html(result['description']);
			}
			$('#offcanvas-flip .progress-loader').hide();
		}
	});
})



$(document).on("click",".duplicates-entities", function () {

	$('#offcanvas-duplicates .progress-loader').show();
	var url =  $(this).attr('data-url');
	var urltitle =  $(this).attr('data-title');
	var description =  $(this).attr('data-description');
	var campaign_id = $('.campaign_id').val();
	var filter = $('.filter').val();
	$('#offcanvas-duplicates .duplicate-links').html('');

	$('#offcanvas-duplicates .duplicate-top #url a').html(url);
	if(filter ==  'duplicate_description'){
		$('#offcanvas-duplicates .source-label h3 #top-title').html('Description duplicates: ');
		$('#offcanvas-duplicates h5').html('Description duplicates ');
		$('#offcanvas-duplicates .duplicate-top #title strong').html('Description: ');
		$('#offcanvas-duplicates .duplicate-top #title span').html(description);
	}else{
		$('#offcanvas-duplicates .source-label h3 #top-title').html('Title duplicates: ');
		$('#offcanvas-duplicates h5').html('Title duplicates ');
		$('#offcanvas-duplicates .duplicate-top #title strong').html('Title: ');
		$('#offcanvas-duplicates .duplicate-top #title span').html(urltitle);
	}
	
	$.ajax({
		type:"POST",
		url:BASE_URL+"/ajax-duplicatetages",
		data:{_token:$('meta[name="csrf-token"]').attr('content'),url:url,urltitle:urltitle,description:description,campaign_id:campaign_id,filter:filter},
		dataType:'json',
		success:function(result){
			result.forEach((entry) => {
				$('#offcanvas-duplicates .source-label h3 #top-count').html(entry.total_count);

				entry.pages.forEach((pages) => {
					if(pages.url !== url){
						$('#offcanvas-duplicates .duplicate-links').append('<p><a href="'+ pages.url + '">'+ pages.url +'</a></p>');
					}
				});
			});
			$('#offcanvas-duplicates .progress-loader').hide();
		}
	});
})


$(document).on("click",".links-tabing", function () {

	$('#offcanvas-links .progress-loader').show();
	$('#anchor-links tbody').html('');
	$('#external-links tbody').html('');
	$('#internal-links tbody').html('');
	var url =  $(this).attr('data-url');
	var campaign_id = $('.campaign_id').val();
	var urltitle =  $(this).attr('data-title');
	$('#offcanvas-links .source-label h3 small').html(urltitle);
	$('#offcanvas-links .source-label h3 span').html(url);
	$.ajax({
		type:"POST",
		url:BASE_URL+"/ajax-linktypes",
		data:{_token:$('meta[name="csrf-token"]').attr('content'),url:url,campaign_id:campaign_id},
		dataType:'json',
		success:function(result){

			if(result !==  null){
				result.anchors.items.forEach((entry) => {
					var anchorlink = '<tr>'; 
					anchorlink += '<td><a href="'+ entry.link_to +'" target="_blank" >'+ entry.link_to +'</a></td>'; 

					if(entry.text == null || entry.text == 'undefined'){
						anchorlink += '<td> -- </td>'; 
					}else{
						anchorlink += '<td>'+ entry.text +'</td>'; 
					}
					// anchorlink += '<td>'+ entry.text +'</td>'; 
					anchorlink += '</tr>'; 
					$('#anchor-links tbody').append(anchorlink);
				});

				result.externals.items.forEach((entry) => {
					var anchorlink = '<tr>'; 
					anchorlink += '<td><a href="'+ entry.link_to +'" target="_blank" >'+ entry.link_to +'</a></td>'; 

					if(entry.text == null || entry.text == 'undefined'){
						anchorlink += '<td> -- </td>'; 
					}else{
						anchorlink += '<td>'+ entry.text +'</td>'; 
					}
					
					anchorlink += '</tr>'; 
					$('#external-links tbody').append(anchorlink);
				});

				result.internals.items.forEach((entry) => {
					var anchorlink = '<tr>'; 
					anchorlink += '<td><a href="'+ entry.link_to +'" target="_blank" >'+ entry.link_to +'</a></td>'; 
					//anchorlink += '<td>'+ entry.text +'</td>'; 
					if(entry.text == null || entry.text == 'undefined'){
						anchorlink += '<td> -- </td>'; 
					}else{
						anchorlink += '<td>'+ entry.text +'</td>'; 
					}
					anchorlink += '</tr>'; 
					$('#internal-links tbody').append(anchorlink);
				});
			}
			
			$('#offcanvas-links .progress-loader').hide();
		}
	});
})

function refreshTask(campaign_id){

	if($('.site-audit-renew').hasClass('in-progress') == true){
		Command: toastr["warning"]('Request Already in progress!');
		return false;
	}

	var htmlLoader = '<div class="audit-white-box mb-40"> <div class="elem-flex"> <div class="elem-start"> <div class="circle_percent ajax-loader"><div class="circle_inbox"><span class="percent_text"></span>of 100</div></div><div class="score-for"> <h2 class="ajax-loader h-50"></h2> <p class="ajax-loader h-20"></p><ul class="ajax-loader h-20"></ul> <a class="btn ajax-loader h-39" style="width: 140px;"></a> <a class="btn ajax-loader h-39" style="width: 140px;"></a> </div></div><div class="elem-end"> <article> <ul> <li class="ajax-loader h-20"></li><li class="ajax-loader h-20"></li><li class="ajax-loader h-20"></li></ul> <ul> <li class="ajax-loader h-20"></li><li class="ajax-loader h-20"></li></ul> </article> </div></div></div><div class="audit-white-box mb-40 pa-0"> <div class="audit-box-head"> <h2 class="ajax-loader h-33" style="max-width: 400px;"></h2> </div><div class="audit-box-body"> <table> <tbody> <tr> <td><div class="ajax-loader h-20" style="max-width: 200px;"></div></td><td><div class="ajax-loader h-20" style="max-width: 200px;"></div></td></tr><tr> <td><div class="ajax-loader h-20" style="max-width: 200px;"></div></td><td><div class="ajax-loader h-20" style="max-width: 200px;"></div></td></tr></tbody> </table> </div><div class="audit-box-foot"> <a class="ajax-loader h-20" style="display: inline-block; width: 90px;"></a> </div></div><div class="audit-white-box pa-0" id="PageLevelIssues"> <div class="audit-box-head"> <h2 class="ajax-loader h-33" style="max-width: 400px;"></h2> </div><div class="audit-box-body"> <div class="audit-stats"> <a class="ajax-loader h-107"></a> <a class="ajax-loader h-107"></a> <a class="ajax-loader h-107"></a> </div><div class="audit-issues"> <ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: .auditIssuesContainer"> <li class="uk-active"><a href="#" aria-expanded="true" class="ajax-loader h-39"></a></li><li><a href="#" aria-expanded="false" class="ajax-loader h-39"></a></li><li><a href="#" aria-expanded="false" class="ajax-loader h-39"></a></li><li><a href="#" aria-expanded="false" class="ajax-loader h-39"></a></li><li><a href="#" aria-expanded="false" class="ajax-loader h-39"></a></li></ul> <div class="content"> <p class="ajax-loader h-20"></p></div><div class="tab-content"> <div class="uk-switcher auditIssuesContainer" style="touch-action: pan-y pinch-zoom;"> <div class="uk-active"> <table> <tbody> <tr> <td><div class="ajax-loader h-33"></div></td></tr><tr> <td><div class="ajax-loader h-33"></div></td></tr><tr> <td><div class="ajax-loader h-33"></div></td></tr><tr> <td><div class="ajax-loader h-33"></div></td></tr><tr> <td><div class="ajax-loader h-33"></div></td></tr><tr> <td><div class="ajax-loader h-33"></div></td></tr><tr> <td><div class="ajax-loader h-33"></div></td></tr><tr> <td><div class="ajax-loader h-33"></div></td></tr><tr> <td><div class="ajax-loader h-33"></div></td></tr><tr> <td><div class="ajax-loader h-33"></div></td></tr></tbody> </table> </div></div></div></div></div></div>';

	$('.audit-summery').html(htmlLoader);

	$('.site-audit-renew').addClass('in-progress');
	$('.site-audit-renew').addClass('refresh-gif');

	$.ajax({
		type:"get",
		url:BASE_URL+"/audit-page-task-refresh/"+campaign_id,
		dataType:'json',
		success:function(result){
			Command: toastr["success"]('Request sent successfully!');
			setTimeout(function () {
				siteAuditStatus(campaign_id);
			},2000);
		}
	});
}

$(document).on("click",".site-audit-renew", function () {

	var campaign_id = $('.campaign_id').val();
	
	refreshTask(campaign_id);

});

$(document).on("click",".issueincode", function () {

  $('#offcanvas-issueincode .progress-loader').show();
  var url =  $(this).attr('data-url');
  var urltitle =  $(this).attr('data-title');
  var campaign_id = $('.campaign_id').val();
  $('.source-label h3 small').html(urltitle);
  $('.source-label h3 span').html(url);

  var x = document.getElementById('codeissue');
  x.value = '';
  $('.CodeMirror').remove();
  var htmlEditor = CodeMirror.fromTextArea(document.getElementById("codeissue"), {
    lineNumbers: true,
    // lineWrapping: true,
    readOnly: true,
    mode: "text/html",
    // styleActiveLine: true,
    theme: 'dracula',
    matchBrackets: true,
    enableSearchTools: true,
  });

  $.ajax({
    type:"POST",
    url:BASE_URL+"/ajax-viewsourcehtml",
    data:{_token:$('meta[name="csrf-token"]').attr('content'),url:url,campaign_id:campaign_id},
    dataType:'json',
    success:function(result){
		var x = document.getElementById('codeissue');

		var resultData = result['html']
		x.value = result['html'];
		var normStr = result['html'];
		var textArea = htmlEditor.getValue();
		htmlEditor.setValue(normStr);
		//htmlEditor.markText({line: 8,ch: 0}, {line: 9, ch: 0}, {className: "highlightClass"});
      	
		 var value = $('.issuetags').val();
		// var value = '<img(?!.\s+alt\s=).+$/gmi';
		var cursor = htmlEditor.getSearchCursor(value);
		var first, from, to;

		while (cursor.findNext()) {
		  from = cursor.from();
		  to = cursor.to(); 
		 
		 console.log(cursor.findNext());
		  var fromline = parseInt(from.line);
		  var nextline = parseInt(from.line)+1;
		  htmlEditor.markText({line: fromline,ch: 0}, {line: nextline, ch: 0}, {className: "highlightIssue"});
		  if (first === undefined) {
		    first = from;
		  }
		}

		if (first) {
		  htmlEditor.scrollIntoView(first);
		}

      $('#offcanvas-issueincode .progress-loader').hide();
    }
  });
});

$(document).on("click",'#ShareKey', function (e) {
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

/* View Key js */


$(document).on("click",".viewPages", function () {

	var selectfiter = $(this).data('filter');
	var campaign_id = $('.campaign_id').val();

	if(selectfiter !== 'all'){
		var filter = $('.filter').val();
	}else{
		var filter = '';
	}
	
	var urlpdf =  $('.viewkeypdf').attr('href');
	var finalurlpdf = urlpdf.substring(0, urlpdf.lastIndexOf('/'));
	$('.viewkeypdf').attr('href',finalurlpdf+'/audit');
	
	$.ajax({
		type:"get",
		url:BASE_URL+"/audit-pages/"+campaign_id,
		success:function(result){
			$('#audit').html(result);
			$('.filter').val(selectfiter);
			siteAuditPageLabel(campaign_id);
			siteAuditPages(campaign_id);
			siteAuditPagesSidebar(campaign_id);
			
		}
	});
})

$(document).on("click",".seoHome", function () {
	sidebar('seo');
	$('.main-data-view').hide();
	$('#seoDashboard').show();
	var urlpdf =  $('.viewkeypdf').attr('href');
	var finalurlpdf = urlpdf.substring(0, urlpdf.lastIndexOf('/'));
	$('.viewkeypdf').attr('href',finalurlpdf+'/seo');

});

$(document).on("click",".auditHome", function () {
		
		var campaign_id = $('.campaign_id').val();
		var urlpdf =  $('.viewkeypdf').attr('href');
		var finalurlpdf = urlpdf.substring(0, urlpdf.lastIndexOf('/'));
		$('.viewkeypdf').attr('href',finalurlpdf+'/audit');

		$.ajax({
			type:"get",
			url:BASE_URL+"/audit-overview/"+campaign_id,
			success:function(result){
				$('#audit').html(result);
				siteAuditSummary(campaign_id);
				
			}
		});
});

$(document).on("click",".pagesAudit", function () {

	var page = $(this).data('key');
	var campaign_id = $('.campaign_id').val();

	var urlpdf =  $('.viewkeypdf').attr('href');
		var finalurlpdf = urlpdf.substring(0, urlpdf.lastIndexOf('/'));
		$('.viewkeypdf').attr('href',finalurlpdf+'/audit-detail?index='+page);
	$.ajax({
		type:"get",
		url:BASE_URL+"/audit-details/"+campaign_id+"/"+page,
		success:function(result){
			 $('#audit').html(result);
			 	ajaxAuditDetailRightside(campaign_id);
				siteDetailOverview(campaign_id,page);
				siteDetailIssues(campaign_id,page);
				siteDetailLinks(campaign_id,page);
				siteDesktopInsights(campaign_id,page);
				siteMobileInsights(campaign_id,page);
		}
	});
})




