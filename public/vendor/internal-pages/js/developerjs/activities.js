var BASE_URL = $('.base_url').val();
$(document).on("click",'#add-activities', function (e) {
	e.preventDefault();
	if($('#SEO').find(".activities").length == 0){
		$('#SEO').append('<div class="activities"></div>');
	}

	$('.activities').load('/activities-layout/'+$('.campaign_id').val(), function(responseTxt, statusTxt, xhr){
		if(statusTxt == "success")
			$("#seodash").hide();

		if(statusTxt == "error")
			console.log("Error: " + xhr.status + ": " + xhr.statusText);
	});
});

$(document).ready(function(){
	$('.project_domain_register').datepicker({format: 'yyyy-mm-dd',endDate: new Date(),autoHide:true});
});

$(document).on("click",'#close-activity', function (e) {
	e.preventDefault();
	$("#seodash").show();
	$(".activities").remove();

});


$(document).on("click",'.activity-cancel', function (e) {
	
	var categoryId = $(this).attr('data-id');
	var categoryName = $(this).attr('data-name');
	var htmldata = '<div class="uk-text-center mt-30"> <a class="btn btn-sm blue-btn"  href="javascript:;" data-id="'+categoryId+'" data-name="'+categoryName+'" id="addMoreActivity"><span uk-icon="plus-circle"></span> Add New Activity</a> </div>';
	$('#'+categoryName).html(htmldata);

});


$(document).on("click",'#addMoreActivity', function (e) {

	var categoryId = $(this).attr('data-id');
	var categoryName = $(this).attr('data-name');
	var campaignId = $('.campaign_id').val();
	$.ajax({
		type:"GET",
		url:BASE_URL+"/activity/addmore",
		data:{campaignId:campaignId,categoryId:categoryId,categoryName:categoryName},
		success:function(response){
			$('#'+categoryName).html(response);
			/*$('.activityTabContent .uk-active').append(response);*/
		}
	});
});

$(document).on("submit",".addNewLoad", function(e){
	
	e.preventDefault();
	// var dataString = $(this).serialize();
	var dataString = $(this).serializeArray();

	len = dataString.length,
	dataObj = {};
    for (i=0; i<len; i++) { // acceesing data array
    	dataObj[dataString[i].name] = dataString[i].value;
    }


    var categoryId = dataObj['category_id'];
    var categoryName = dataObj['category_name'];

    var errors = 0;        

    if ( !dataObj['activity_name'].trim() ) { //cheking if empty field
    	//Command: toastr["error"]('Please enter Activity name');
    	$(this).find('.activity_name').addClass('error');
    	errors++;         
    }else{
    	$(this).find('.activity_name').removeClass('error');
    }
    if(errors > 0){
    	return false;
    }

    $.ajaxSetup({
    	headers: {
    		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    	}
    });

   $.ajax({
        url:BASE_URL + '/add-activitylist',
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        data: new FormData(this),
        dataType:'json',
        success: function (response) {
            if(response.status == false){
                Command: toastr["error"](response.message);
            }else{

                var newtemp = '';
                newtemp += '<form method="get" class="activitiesLoad" id="activitiesLoad">';
                newtemp += '<div class="act-ques">';
                newtemp += '<input type="hidden" name="campaign_id" value="'+ $('.campaign_id').val() +'" >';
                newtemp += '<input type="hidden" name="category_id" value="'+ categoryId +'" >';
                newtemp += '<input type="hidden" name="activity_id" value=" '+ response.taskData.id +' " >';
                newtemp += response.taskData.name;

                newtemp += '</div>';
                newtemp += '<div class="parent-activity"><div class="single-activity">';
                newtemp += '<div class="activity-actions"><a href="javascript:;" data-id="'+response.taskData.id+'" class="btn icon-btn color-red addMoreList"><i class="fa fa-plus" aria-hidden="true"></i></a><a href="javascript:;" data-id="'+response.taskData.id+'" class="btn icon-btn color-red deleteActivityList"><i class="fa fa-trash" aria-hidden="true"></i></a></div>';
                newtemp += '<div class="act-status">';
                newtemp += '<select class="select form-control" id="activity_status" name="status[]" >';
                newtemp += '<option value="1" >Working</option>';
                newtemp += '<option value="2" >Completed</option>';
                newtemp += '<option value="3" >Already Set</option>';
                newtemp += '<option value="4" >Suggested</option>';
                newtemp += '</select>';
                newtemp += '</div>';

                newtemp += '<div class="act-file-link" >';
                newtemp += '<button type="button"  placeholder="File Link:" class="form-control file_link" id="file_link" name="file_link[]" data-pd-popup-open="addProgressPopup">';
                newtemp += '<i class="fa fa-paperclip"></i>Add Progress </button>';
                newtemp += '<input type="hidden" name="activityfilelinked" value="blank" id="activityfilelinked" />';
                newtemp += '<input type="hidden" name="activityfilelink[]" id="activityfilelink" /></div>';

                newtemp +='<div class="act-hours dates" id="datepicker">';
                newtemp +='<input type="text" class="form-control project_domain_register activity_date" id="activity_date" name="activity_date[]"   placeholder="YYYY-MM-DD"  autocomplete="off" readonly/ >';
                newtemp +='</div>';

                newtemp += '<div class="act-hours time" id="timepicker">';
                newtemp += '<input type="number" class="form-control activity_time" id="activity_hours" name="activity_hours[]" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "2"  placeholder="HH"/ >';
                newtemp += '<input type="number" class="form-control activity_time" id="activity_seconds" name="activity_seconds[]" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" ';
                newtemp += 'maxlength = "2" placeholder="MM"/ >';
                newtemp += '</div>';

                newtemp += '<div class="act-note">';
                newtemp += '<textarea class="form-control activity_note" id="activity_note" name="notes[]" placeholder="Type something:" ></textarea>';
                newtemp += '</div>';
                newtemp += '</div></div>';
                newtemp += '<div class="act-submit">';
                newtemp += '<button class="btn icon-btn color-blue"><i class="fa fa-paper-plane-o"></i></button>';
                newtemp += '</div>';
                newtemp += '</form>';

                $('#'+categoryName).html(newtemp);

                $('#'+categoryName).removeAttr('id');

                var htmldata = '<article id="'+categoryName+'"><div class="uk-text-center mt-30"><a class="btn btn-sm blue-btn"  href="javascript:;" data-id="'
                +categoryId+'" data-name="'
                +categoryName+'" id="addMoreActivity"><span uk-icon="plus-circle"></span> Add New Activity</a></div></article>';
                $('.activityTabContent .uk-active').append(htmldata);

                $('.project_domain_register').datepicker({format: 'yyyy-mm-dd',endDate: new Date(),autoHide:true});

                Command: toastr["success"](response.message);

            }
        }
    });

});

$(document).click(function(event) {


	if ($(event.target).closest("#activityprogress").length) {

		/* $("body").find(".modal").removeClass("visible");*/
	}
   /*$("#activityprogress").trigger("click");
   $("body").removeClass("popup-open");*/

});


$(document).on("click",'.file_link', function (e) {
    e.preventDefault();
    var attr_val = $(this).attr('data-attr');
    $('.custom-file-label input').attr("name",'activity_image['+attr_val+'][]');
    
    $('.imgFileSubmit').removeAttr('disabled',"disabled");
    $('#img_filess').closest().removeClass('selected error');   

    $(".file_link").removeClass('selected');
    $('.current-section').removeClass('current-section');

    var row = $(this).closest(".act-file-link"); 
    row.addClass('current-section'); 

    $('.single-activity').removeClass('appendData');
    var parent_row = $(this).closest(".act-file-link").parent(); 
    parent_row.addClass('appendData'); 

    $('#progress_preview_container').hide();
    $('#progress_preview_container').attr('src', '#');
    $('.custom-file').removeClass('selected');

    $("#imgFile #img_filess").val('');
    $('.current-section #activityfile').val('');
    $('.current-section #activityfilelink').val('');
    $('.current-section #activityfilelinked').val('blank');
    $(".file_link").html('<i class="fa fa-paperclip"></i>'+'Add Progress'); 
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

$(document).on("submit",".imgLink", function(e){

	e.preventDefault();

	var dataString = $(this).serializeArray();

	len = dataString.length,
	dataObj = {};
    for (i=0; i<len; i++) { // acceesing data array
    	dataObj[dataString[i].name] = dataString[i].value;
    }


    if (!is_url(dataObj['links'])) {
    	$(this).find('#links').addClass('error');
    	$('#link_error').parent().css('display','block');
    	document.getElementById('link_error').innerHTML = 'Not a valid link';
    	return false;
    } 

    $('#links').removeClass('error');
    $('#link_error').parent().css('display','none');
    document.getElementById('link_error').innerHTML = '';

    $(".current-section input[name='activityfilelink[]']").val(dataObj['links']);
    $(".current-section").find('button').html('<i class="fa fa-paperclip"></i>'+'Link Added');
    $(".current-section #file_link").addClass('selected');
    $(".current-section").find('#activityfilelinked').val('attached');

    $("#activity-popup").trigger("click");
    $("body").removeClass("popup-open");

});


$(document).on("submit",".activitiesLoad", function(e){
    $('.activityTabContent').find('article').removeClass('activeArticle');
    $(this).parent().addClass('activeArticle');

    $('.activitiesLoad').find('.form-control').removeClass('error');

    var botton = $(this).find('button').last();
    botton.prop('disabled', true);  
    e.preventDefault();
    var dataString = $(this).serializeArray();

    len = dataString.length,
    dataObj = {};
    for (i=0; i<len; i++) { // acceesing data array
        dataObj[dataString[i].name] = dataString[i].value;
    }
   
    $.ajaxSetup({
    	headers: {
    		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    	}
    });

   $.ajax({
        url:BASE_URL + '/add-activities',
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        data: new FormData(this),
        dataType:'json',
        success: function (response) {
            $('.create-activity-loader').css('display','block');
            if(response.status == true){
                $('.activeArticle').find('a').attr('data-value',"1");

                $('#activitypage').val(1);
                $('#activityList tbody').html('');
                loadActivities();
                Command: toastr["success"](response.message);
                botton.prop('disabled', false);

                $(".activitiesLoad").trigger("reset");
                $("#imgFile").trigger("reset");
                $("#imgLink").trigger("reset");
                $("#imgLink #links").val('');

                appendImgInput();

                $('.img-append-section').html('');
                $('.uploaded-file').html('');
                $(".current-section").find('button').html('<i class="fa fa-paperclip"></i>'+'Add Progress');
                $(".current-section #activityfilelinked").val('');
                $(".file-group .form-control").removeClass("selected-img");
                $(".current-section #file_link").removeClass('selected');
                $('.activityTabContent').find('.activeArticle').find('.removeMoreList').trigger('click');
                setTimeout(function(){
                    $('.create-activity-loader').addClass('complete');
                    setTimeout(function(){
                       $('.create-activity-loader').css('display','none');
                       $('.create-activity-loader').removeClass('complete');
                   }, 200);
                },100);

            }else{
                $('.create-activity-loader').css('display','none');
                var messages = response.message;
                var length = messages.length;

                for (i=0; i <= response.array_count; i++) {
                    var get_article = $('.activityTabContent').find('.activeArticle');
                    var single_activity = get_article.find('.single-activity').eq(i);

                    if(messages[i] !== undefined && (messages[i]["activityfilelink"] !== undefined && messages[i]["activityfilelink"] !== '')){
                        single_activity.find('.file_link').addClass('error');
                    }else{
                        single_activity.find('.file_link').removeClass('error');
                    }

                    if(messages[i] !== undefined && (messages[i]["activity_date"] !== undefined && messages[i]["activity_date"] !== '')){
                        single_activity.find('.activity_date').addClass('error');
                    }else{
                        single_activity.find('.activity_date').removeClass('error');
                    }

                    if(messages[i] !== undefined && (messages[i]["activity_time"] !== undefined && messages[i]["activity_time"] !== '')){
                        single_activity.find('.activity_time').addClass('error');
                    }else{
                        single_activity.find('.activity_time').removeClass('error');
                    }

                    if(messages[i] !== undefined && (messages[i]["notes"] !== undefined && messages[i]["notes"] !== '')){
                        single_activity.find('.activity_note').addClass('error');
                    }else{
                        single_activity.find('.activity_note').removeClass('error');
                    }

                }
                botton.prop('disabled', false); 
            }
        }
    });

});

$(document).on("click",'.deleteActivityList', function (e) {
	e.preventDefault();

	var activityCount = $(this).attr('data-value');
	if(activityCount > 0){
		/*if (!confirm("All activites will also delete assocaite with this list")) {*/
			if (!confirm("Please delete all activities before delete category!")) {
				return false;
			}
			var url = BASE_URL+'/activity/categories/'+$(this).attr('data-id');
			window.open(url, '_blank');

			/*window.location.href = url;*/

			return false;
		}else{
			if (!confirm("Are you sure you want to delete this activity list?")) {
				return false;
			}
		}


		var activityId = $(this).attr('data-id');
		var campaignId = $('.campaign_id').val();
		var userid = $('#user_id').val();
		var row = $(this).closest("article"); 

		$.ajax({
			type:"GET",
			url:BASE_URL+"/delete-activitylist",
			data:{campaignId:campaignId,activityId:activityId,userid:userid},
			dataType:'json',
			success:function(response){
				if(response.status == true){
					row.remove();
					Command: toastr["success"](response.message);
				}else{
					Command: toastr["error"](response.message);
				}
			}
		});
	});


$(document).on("click",'.delete_activities', function (e) {
	e.preventDefault();

	if (!confirm("Are you sure you want to delete this activity?")) {
		return false;
	}

	var activityId = $(this).attr('data-id');
	var campaignId = $('.campaign_id').val();
	var userid = $('#user_id').val();
	var row = $(this).closest("tr"); 
	$.ajax({
		type:"GET",
		url:BASE_URL+"/delete-activities",
		data:{campaignId:campaignId,activityId:activityId,userid:userid},
		dataType:'json',
		success:function(response){
			if(response.status == true){
				row.remove();
				var dates = $('#activitydate').val();
				var data =  {page:1,column_name:'',order_type:'',campaignId:campaignId,limit:'',query:'',dates:dates,loadtime:'first'};
				actitiviesTotal(data);
                if($('#activityList tbody tr').length == 0){
                   $('#activityList tbody').append('<tr><td colspan="8"><center>No activity found</center></td></tr>');
                }
				Command: toastr["success"](response.message);
			}else{
				Command: toastr["error"](response.message);
			}
		}
	});

});

$(document).on("change",'.activitiesRanges', function (e) {
	
	e.preventDefault();
	$('.activitiesRange').removeClass('active');
	
	var value = $(this).val();
	
	var campaignId = $('.campaign_id').val();

	var userid = $('#user_id').val();
	
	$('.searchConsole').removeClass('active');
	$(this).addClass('active');
	
	var limit = $('#activitylimit').val();
	
	$('#activitydate').val(value);
	$('#activitypage').val(1);
	$('#activityList tbody').html('');	
	var data =  {page:1,column_name:'',order_type:'',campaignId:campaignId,limit:limit,query:'',dates:value,loadtime:'first'};
	actitiviesList(data);
	
});

$(document).on("click",'.activitiesRange', function (e) {
	
	e.preventDefault();
	var value = $(this).attr('data-value');
	
	var campaignId = $('.campaign_id').val();
	var userid = $('#user_id').val();
	
	var loadsection =  $('#viewactivityload').val();

	if(loadsection == 'sidebaractivity'){
		$('#view-activity-side .activitiesRange').removeClass('active');
		$(this).addClass('active');

		$('#viewActivitydate').val(value);
		$('#viewActivitypage').val(1);
		$('#view-activity-side #activityList tbody').html('');

		var limit = $('#viewActivitylimit').val();
		var data =  {page:1,column_name:'',order_type:'',campaignId:campaignId,limit:limit,query:'',dates:value,loadtime:'first'};
		sideActitiviesList(data);	
		actitiviesTotal(data);

	}else if(loadsection == 'viewload'){
		$('#view-activity-section .activitiesRange').removeClass('active');
		$(this).addClass('active');
		
		$('#activitydate').val(value);
		$('#activitypage').val(1);
		$('#view-activity-section #activityList tbody').html('');

		var limit = $('#activitylimit').val();
		var data =  {page:1,column_name:'',order_type:'',campaignId:campaignId,limit:limit,query:'',dates:value,loadtime:'first'};
		actitiviesList(data);
		actitiviesTotal(data);

	}else{
		$('.activitiesRange').removeClass('active');
		$(this).addClass('active');

        $('.calendar-month').removeClass('active');
        $('#calendar-label').removeClass('btn-active');
        $('#calendar-label').html('Select Month <span uk-icon="icon:triangle-down"></span>');

		$('#activitydate').val(value);
		$('#activitypage').val(1);
		$('#activityList tbody').html('');	

		var limit = $('#activitylimit').val();
		var data =  {page:1,column_name:'',order_type:'',campaignId:campaignId,limit:limit,query:'',dates:value,loadtime:'first'};
		actitiviesList(data);
		actitiviesTotal(data);
	}
	
});


/*$(document).on("click",'.activitiesRange', function (e) {
	e.preventDefault();
	$('.activitiesRange').removeClass('active');
	$(this).addClass('active');

	var value = $(this).attr('data-value');
	
	var campaignId = $('.campaign_id').val();

	var userid = $('#user_id').val();
	
	$('.searchConsole').removeClass('active');
	$(this).addClass('active');
	
	var limit = $('#activitylimit').val();
	
	$('#activitydate').val(value);
	$('#activitypage').val(1);
	$('#activityList tbody').html('');	
	var data =  {page:1,column_name:'',order_type:'',campaignId:campaignId,limit:limit,query:'',dates:value,loadtime:'first'};
	actitiviesTotal(data);
	actitiviesList(data);
	
});*/

$(document).on("click",'.check_progress', function (e) {
	$("#progressPopup").addClass('ajax-loader');
	e.preventDefault();
	var activityId = $(this).attr('data-id');
	
	var campaignId = $('.campaign_id').val();

	var userid = $('#user_id').val();
	$.ajax({
		type:"GET",
		url:BASE_URL+"/activity/process/"+activityId,
		success:function(response){
			$('#activityprogress').html(response);	
			$("#progressPopup").removeClass('ajax-loader');
		}
	});	
});

function actitiviesList(data){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax-activities",
		data:data,
		success:function(response){
			var activitypage = parseInt(data.page) + 1;
			$('#activitypage').val(activitypage);
			/*$('#viewActivitypage').val(activitypage);*/

			if($('#activity-section #activityList tbody').length > 0){
				$('#activity-section #activityList tbody').append(response);	
			}else if($('#view-activity-section #activityList tbody').length > 0){
				$('#view-activity-section #activityList tbody').append(response);	
			}else{
				$('#activity-section #activityList tbody').append(response);	
			}
			$('#view-activity-section #activityList .action').remove();
		}
	});
}

function actitiviesListPage(data){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax-activities",
		data:data,
		success:function(response){
			var activitypage = parseInt(data.page) + 1;
			$('#activitypage').val(activitypage);
			/*$('#viewActivitypage').val(activitypage);*/

			if($('#page-activity-section #activityList tbody').length > 0){
				$('#page-activity-section #activityList tbody').append(response);	
			}
		}
	});
}

function sideActitiviesList(data){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax-activities",
		data:data,
		success:function(response){
			
			$('#view-activity-side #activityList tbody').append(response);	
			$('#view-activity-side #activityList .action').remove();
		}
	});

}

function actitiviesTotal(data){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax-activitytotal",
		data:data,
		dataType:'json',
		success:function(response){
			$('.totalActivity p').html('Total Time: '+response+' hours');			
		}
	});

}

function loadActivities(){
	var loadsection =  $('#viewactivityload').val();
	if(loadsection == 'viewload'){
		$('#view-activity-section .activity-tab-section').load('/activities/'+$('.campaign_id').val(), function(responseTxt, statusTxt, xhr){
			if(statusTxt == "success")
				var rowCount = $('#view-activity-section #activityList tbody tr').length;
			var activitypage = $('#activitypage').val();
			if(rowCount > 0){
				var page = parseInt(activitypage) + 1;
				$('#activitypage').val(page);
			}else{
				var page = 1;
			}

			var campaignId = $('.campaign_id').val();
			var limit = $('#activitylimit').val();
			var page = 1;
			$('.calendar-dropdown').show();
			var data =  {page:page,column_name:'',order_type:'',campaignId:campaignId,limit:limit,query:'',value:'',loadtime:'first'};
			actitiviesList(data);
			actitiviesTotal(data);

			if(statusTxt == "error")
				console.log("Error: " + xhr.status + ": " + xhr.statusText);
		});
	}else if(loadsection == 'sidebaractivity'){
		$('#view-activity-side .activity-tab-section').load('/activities/'+$('.campaign_id').val(), function(responseTxt, statusTxt, xhr){
			if(statusTxt == "success")

				var rowCount = $('#view-activity-side #activityList tbody tr').length;
			var activitypage = $('#viewActivitypage').val();
			if(rowCount > 0){
				var page = parseInt(activitypage) + 1;
				$('#viewActivitypage').val(page);
			}else{
				var page = 1;
			}
			var campaignId = $('.campaign_id').val();

			var limit = $('#activitylimit').val();

			$('#viewActivitypage').val(page);
			$('.calendar-dropdown').show();

			var data =  {page:page,column_name:'',order_type:'',campaignId:campaignId,limit:limit,query:'',value:'',loadtime:'first'};
			sideActitiviesList(data);
			actitiviesTotal(data);

			if(statusTxt == "error")
				console.log("Error: " + xhr.status + ": " + xhr.statusText);
		});
	}else{
		$('#activity-section .activity-tab-section').load('/activities/'+$('.campaign_id').val(), function(responseTxt, statusTxt, xhr){
			if(statusTxt == "success")
				var campaignId = $('.campaign_id').val();

			var limit = $('#activitylimit').val();
			var page = 1;
				/*var activitypage = parseInt($('#activitypage').val(activitypage)) + 1;
				$('#activitypage').val(activitypage);*/
				$('.calendar-dropdown').show();
				
				var data =  {page:page,column_name:'',order_type:'',campaignId:campaignId,limit:limit,query:'',value:'',loadtime:'first'};
				actitiviesList(data);
				actitiviesTotal(data);

				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});

	}
}

$(window).scroll(function() {
	if($(window).scrollTop() + $(window).height() >= $(document).height()){
		var loadsection =  $('#viewactivityload').val();
		if(loadsection == 'viewload'){
			if($('#view-activity-section').find('#activityList').length > 0){
				if($('#view-activity-section #activityList').find('#endlist').length > 0){

				}else{
					var rowCount = $('#view-activity-section #activityList tbody').length;

					if(rowCount > 0){
						var campaignId = $('.campaign_id').val();
						var limit = $('#activitylimit').val();
						var page = $('#activitypage').val();
						var categoryId = $("#activityList tr:last input:first").val();
						var dates = $('#activitydate').val();
						var data =  {page:page,column_name:'',order_type:'',campaignId:campaignId,limit:limit,query:'',dates:dates,categoryId:categoryId};
						actitiviesList(data);
					}
				}	
			}

		}else if(loadsection == 'sidebaractivity'){
			if($('#view-activity-side').find('#activityList').length > 0){
				if($('#view-activity-side #activityList').find('#endlist').length > 0){

				}else{
					var rowCount = $('#view-activity-side #activityList tbody tr').length;
					if(rowCount > 0){
						var campaignId = $('.campaign_id').val();
						var limit = $('#viewActivitylimit').val();

						var activitypage = $('#viewActivitypage').val();
						var page = parseInt(activitypage) + 1;
						$('#viewActivitypage').val(page);

						var categoryId = $("#activityList tr:last input:first").val();
						var dates = $('#viewActivitydate').val();
						var data =  {page:page,column_name:'',order_type:'',campaignId:campaignId,limit:limit,query:'',dates:dates,categoryId:categoryId};
						sideActitiviesList(data);
					}
				}	
			}
		}else{
			if($('#activity-section').find('#activityList').length > 0){
				if($('#activity-section #activityList').find('#endlist').length > 0){

				}else{
					var campaignId = $('.campaign_id').val();
					var limit = $('#activitylimit').val();
					var page = $('#activitypage').val();
					var categoryId = $("#activityList tr:last input:first").val();
					var dates = $('#activitydate').val();
					var data =  {page:page,column_name:'',order_type:'',campaignId:campaignId,limit:limit,query:'',dates:dates,categoryId:categoryId};
					actitiviesList(data);
				}	
			}

			if($('#page-activity-section').find('#activityList').length > 0){
				if($('#page-activity-section #activityList').find('#endlist').length > 0){

				}else{
					var tasklistId = $('.tasklist_id').val();
					var campaignId = $('.campaign_id').val();
					var limit = $('#activitylimit').val();
					var page = $('#activitypage').val();
					var categoryId = $("#activityList tr:last input:first").val();
					var dates = $('#activitydate').val();
					var data =  {page:page,column_name:'',order_type:'',campaignId:campaignId,tasklistId:tasklistId,limit:limit,query:'',dates:dates,categoryId:categoryId};
					actitiviesListPage(data);
				}	
			}
		}
	}
});

$(document).on("keyup",'#activity_hours', function (e) {    
	var value = $(this).val();
	if (value !== '') {
		$(this).val(Math.max(Math.min(value, 23), 0));
	}
});

$(document).on("keyup",'#activity_seconds', function (e) {    
	var value = $(this).val();
	if (value !== '') {
		$(this).val(Math.max(Math.min(value, 59), 0));
	}
});

$(document).on("click",'.custom-calendar', function (e) {
	
	var year = $(this).attr('data-value');
	var selection = $(this).attr('data-type');
	var updown = $(this).attr('data-id');
	
	var currentyear = parseInt(year) + parseInt(updown);
	
	var defaltCurrentYear = new Date();
	defaltCurrentYear = defaltCurrentYear.getFullYear();

	var loadsection =  $('#viewactivityload').val();
	if(loadsection == 'viewload'){
		if(parseInt(currentyear) == parseInt(defaltCurrentYear)){
			$( "#view-activity-section .calendar-head button" ).last().attr( "disabled","disabled" );
			$( "#view-activity-section .calendar-head button" ).last().addClass( "disable" );
			$( "#view-activity-section .newmonth" ).addClass( "disable" );
			$( "#view-activity-section .newmonth" ).attr( "disabled","disabled" );
			$('#view-activity-section .calendar-head h5').html(currentyear);
			$('#view-activity-section .custom-calendar').attr('data-value',currentyear);
			
		}else if(parseInt(currentyear) < parseInt(defaltCurrentYear)){
			$( "#view-activity-section .calendar-head button" ).last().removeAttr( "disabled","disabled" );
			$( "#view-activity-section .calendar-head button" ).last().removeClass( "disable" );
			
			$( "#view-activity-section .calendar-month" ).removeAttr( "disabled","disabled" );
			$( "#view-activity-section .calendar-month" ).removeClass( "disable" );

			$('#view-activity-section .calendar-head h5').html(currentyear);
			$('#view-activity-section .custom-calendar').attr('data-value',currentyear);
			
		}else{

			$( "#view-activity-section .calendar-month" ).removeAttr( "disabled","disabled" );
			$( "#view-activity-section .calendar-month" ).removeClass( "disable" );

			$('#view-activity-section .calendar-head h5').html(currentyear);
			$('#view-activity-section .custom-calendar').attr('data-value',currentyear);
		}

	}else if(loadsection == 'sidebaractivity'){
		if(parseInt(currentyear) == parseInt(defaltCurrentYear)){
			$( "#view-activity-side .calendar-head button" ).last().attr( "disabled","disabled" );
			$( "#view-activity-side .calendar-head button" ).last().addClass( "disable" );
			$( "#view-activity-side .newmonth" ).addClass( "disable" );
			$( "#view-activity-side .newmonth" ).attr( "disabled","disabled" );
			$('#view-activity-side .calendar-head h5').html(currentyear);
			$('#view-activity-side .custom-calendar').attr('data-value',currentyear);
			
		}else if(parseInt(currentyear) < parseInt(defaltCurrentYear)){
			$( "#view-activity-side .calendar-head button" ).last().removeAttr( "disabled","disabled" );
			$( "#view-activity-side .calendar-head button" ).last().removeClass( "disable" );
			
			$( "#view-activity-side .calendar-month" ).removeAttr( "disabled","disabled" );
			$( "#view-activity-side .calendar-month" ).removeClass( "disable" );

			$('#view-activity-side .calendar-head h5').html(currentyear);
			$('#view-activity-side .custom-calendar').attr('data-value',currentyear);
			
		}else{

			$( "#view-activity-side .calendar-month" ).removeAttr( "disabled","disabled" );
			$( "#view-activity-side .calendar-month" ).removeClass( "disable" );

			$('#view-activity-side .calendar-head h5').html(currentyear);
			$('#view-activity-side .custom-calendar').attr('data-value',currentyear);
		}

	}else{
		if(parseInt(currentyear) == parseInt(defaltCurrentYear)){
			$( ".calendar-head button" ).last().attr( "disabled","disabled" );
			$( ".calendar-head button" ).last().addClass( "disable" );
			$( ".newmonth" ).addClass( "disable" );
			$( ".newmonth" ).attr( "disabled","disabled" );
			$('.calendar-head h5').html(currentyear);
			$('.custom-calendar').attr('data-value',currentyear);
			
		}else if(parseInt(currentyear) < parseInt(defaltCurrentYear)){
			$( ".calendar-head button" ).last().removeAttr( "disabled","disabled" );
			$( ".calendar-head button" ).last().removeClass( "disable" );
			
			$( ".calendar-month" ).removeAttr( "disabled","disabled" );
			$( ".calendar-month" ).removeClass( "disable" );

			$('.calendar-head h5').html(currentyear);
			$('.custom-calendar').attr('data-value',currentyear);
			
		}else{

			$( ".calendar-month" ).removeAttr( "disabled","disabled" );
			$( ".calendar-month" ).removeClass( "disable" );

			$('.calendar-head h5').html(currentyear);
			$('.custom-calendar').attr('data-value',currentyear);
		}
	}

});


$(document).on("click",'.calendar-month', function (e) {
    $('.calendar-month').removeClass('active');
    $('.activitiesRange').removeClass('active');
	$(this).addClass('active');
    $('#calendar-label').addClass('btn-active');
	var month = $(this).attr('data-month');

	var loadsection =  $('#viewactivityload').val();
	if(loadsection == 'viewload'){
		var year = $('#view-activity-section .custom-calendar').attr('data-value');  		
	}else if(loadsection == 'sidebaractivity'){
		var year = $('#view-activity-side .custom-calendar').attr('data-value');
	}else{
		var year = $('.custom-calendar').attr('data-value');
	}	

	var campaignId = $('.campaign_id').val();

	var userid = $('#user_id').val();
	
	$(this).addClass('active');
	var monthyear = month+', '+year;
	var qryDate = year+'-'+$(this).attr('data-value')+'-01';
	var value = year+'-'+$(this).attr('data-value');
	
	if(loadsection == 'sidebaractivity'){
		var limit = $('#activitylimit').val();
		$('#calendar-label-side').html(monthyear+' <span uk-icon="icon:triangle-down"></span>');
		$('#viewActivitydate').val(value);
		$('#viewActivitypage').val(1);
		$('#view-activity-side #activityList tbody').html('');	
		var data =  {page:1,column_name:'',order_type:'',campaignId:campaignId,limit:limit,query:'',dates:qryDate,loadtime:'first'};
		actitiviesTotal(data);
		sideActitiviesList(data);	

	}else if(loadsection == 'viewload'){	
		var limit = $('#activitylimit').val();
		$('#calendar-label-view').html(monthyear+' <span uk-icon="icon:triangle-down"></span>');
		$('#activitydate').val(value);
		$('#activitypage').val(1);
		$('#view-activity-section #activityList tbody').html('');	
		var data =  {page:1,column_name:'',order_type:'',campaignId:campaignId,limit:limit,query:'',dates:qryDate,loadtime:'first'};
		actitiviesTotal(data);
		actitiviesList(data);

	}else {
		var limit = $('#activitylimit').val();
		$('#calendar-label').html(monthyear+' <span uk-icon="icon:triangle-down"></span>');
		$('#activitydate').val(value);
		$('#activitypage').val(1);
		$('#activityList tbody').html('');	
		var data =  {page:1,column_name:'',order_type:'',campaignId:campaignId,limit:limit,query:'',dates:qryDate,loadtime:'first'};
		actitiviesTotal(data);
		actitiviesList(data);
	}	

    $('#calendar-label').trigger('click');
	
});


$(document).on('change','.activity_image_1',function(){
    var reader = new FileReader();
    if(this.files.length == 1){
        if(this.files[0].type.match('image.*')){
            $(".file-group .form-control").removeClass("selected");
            reader.onload = (e) => { 
                $('#activity_add_preview_1').attr('src', e.target.result); 
            };
            $('#custom-div-activityImg-1').addClass('selected-img');
            reader.readAsDataURL(this.files[0]); 
            $('#custom-div-activityImg-1').removeAttr("style");
            enableImgBtn();           

            $('.appendData').find('.img-append-section').append($(this).clone());
            
            appendImgPreview(1);

        }else{
            $('#custom-div-activityImg-1').removeClass('selected-img');
            $('#custom-div-activityImg-1').css('border-color','red');
            $('#activity_add_preview_1').removeAttr('src'); 


        }
    }
});

$(document).on('change','.activity_image_2',function(){
    var reader = new FileReader();
    if(this.files.length == 1){
        if(this.files[0].type.match('image.*')){
            $(".file-group .form-control").removeClass("selected");
            reader.onload = (e) => { 
                $('#activity_add_preview_2').attr('src', e.target.result); 
            };
            $('#custom-div-activityImg-2').addClass('selected-img');
            reader.readAsDataURL(this.files[0]); 
            $('#custom-div-activityImg-2').removeAttr("style");
            enableImgBtn();
            $('.appendData').find('.img-append-section').append($(this).clone());
            appendImgPreview(2);
        }else{
            $('#custom-div-activityImg-2').removeClass('selected-img');
            $('#custom-div-activityImg-2').css('border-color','red');
            $('#activity_add_preview_2').removeAttr('src'); 
        }
    }
});

$(document).on('change','.activity_image_3',function(){
    var reader = new FileReader();
    if(this.files.length == 1){
        if(this.files[0].type.match('image.*')){
            $(".file-group .form-control").removeClass("selected");
            reader.onload = (e) => { 
                $('#activity_add_preview_3').attr('src', e.target.result); 
            };
            $('#custom-div-activityImg-3').addClass('selected-img');
            reader.readAsDataURL(this.files[0]); 
            $('#custom-div-activityImg-3').removeAttr("style");
            enableImgBtn();
            $('.appendData').find('.img-append-section').append($(this).clone());
            appendImgPreview(3);
        }else{
            $('#custom-div-activityImg-3').removeClass('selected-img');
            $('#custom-div-activityImg-3').css('border-color','red');
            $('#activity_add_preview_3').removeAttr('src'); 
        }
    }
});

$(document).on('change','.activity_image_4',function(){
    var reader = new FileReader();
    if(this.files.length == 1){
        if(this.files[0].type.match('image.*')){
            $(".file-group .form-control").removeClass("selected");
            reader.onload = (e) => { 
                $('#activity_add_preview_4').attr('src', e.target.result); 
            };
            $('#custom-div-activityImg-4').addClass('selected-img');
            reader.readAsDataURL(this.files[0]); 
            $('#custom-div-activityImg-2').removeAttr("style");
            enableImgBtn();
            $('.appendData').find('.img-append-section').append($(this).clone());
            appendImgPreview(4);
        }else{
            $('#custom-div-activityImg-4').removeClass('selected-img');
            $('#custom-div-activityImg-4').css('border-color','red');
            $('#activity_add_preview_4').removeAttr('src'); 
        }
    }
});

$(document).on('change','.activity_image_5',function(){
    var reader = new FileReader();
    if(this.files.length == 1){
        if(this.files[0].type.match('image.*')){
            $(".file-group .form-control").removeClass("selected");
            reader.onload = (e) => { 
                $('#activity_add_preview_5').attr('src', e.target.result); 
            };
            $('#custom-div-activityImg-5').addClass('selected-img');
            reader.readAsDataURL(this.files[0]); 
            $('#custom-div-activityImg-5').removeAttr("style");
            $('.imgFileSubmit').removeAttr('disabled',"disabled");
            enableImgBtn();
            $('.appendData').find('.img-append-section').append($(this).clone());
            appendImgPreview(5);
        }else{
            $('#custom-div-activityImg-5').removeClass('selected-img');
            $('#custom-div-activityImg-5').css('border-color','red');
            $('#activity_add_preview_5').removeAttr('src'); 
        }
    }
});


$(document).on("click",".imgFileSubmit", function(e){
 e.preventDefault();
 if($('.remove-uploaded-image').length == 0){
    $('.imgFileSubmit').attr('disabled',"disabled");
    document.getElementById('image_upload_error').innerHTML = 'Minimum 1 image is required';
    $('#image_upload_error').addClass('is-invalid');
    return false;
}else{
    $(".current-section").find('button').html('<i class="fa fa-paperclip"></i>'+'Files Attached');
    $(".current-section #activityfilelinked").val('attached');
    $(".current-section #file_link").addClass('selected');
    $("#activity-popup").trigger("click");
    $("body").removeClass("popup-open");
}

});

function enableImgBtn(){
    $('.imgFileSubmit').removeAttr('disabled',"disabled");
    document.getElementById('image_upload_error').innerHTML = 'You can upload upto 5 images';
    $('#image_upload_error').removeClass('is-invalid');
}

$(document).on('click','.remove-uploaded-image',function(e){
   var count = $(this).next().attr('data-count');
   $('.activity_image_'+count).remove();
   $('#custom-div-activityImg-'+count).removeClass('selected-img');
   $('#activity_add_preview_'+ count).remove();
   $('#img-add-activityImage-'+count+' span').remove();
   setTimeout(function(){
       $('.custom_label_'+count).prepend('<input type="file" name="activity_image[]"  accept="image/png,image/jpg,image/jpeg" class="activity_image_'+count+'">');
   },500);
});

function appendImgPreview(count){
    $('#img-add-activityImage-'+count).html('<span class="remove-uploaded-image"></span><img id="activity_add_preview_'+count+'"  alt="activity-img" class="activity_add_preview" data-count="'+count+'">');
}
function appendImgInput(){
    for(i=1;i<=5;i++){
        $('.custom_label_'+i).prepend('<input type="file" name="activity_image[]"  accept="image/png,image/jpg,image/jpeg" class="activity_image_'+i+'">')
    }
}


$(document).on('click','#activity-popup',function(e){
    e.preventDefault();
    $('#links').removeClass('error');
    $('#link_error').parent().css('display','none');
    document.getElementById('link_error').innerHTML = '';

    $('#image_upload_error').removeClass('is-invalid');
    document.getElementById('image_upload_error').innerHTML = '';
});

$(document).on('click','.addMoreList',function(){
     var length = $(this).parent().parent().parent().find('.single-activity').length;
     if(length <= 4){
         $(this).parent().parent().parent().append('<div class="single-activity"><div class="activity-actions"><a href="javascript:;" data-id="{{ $listing->id }}" data-value="{{ $listing->total_count }}" class="btn icon-btn color-red removeMoreList"><i class="fa fa-minus" aria-hidden="true"></i></a></div><div class="act-status"><select class="select form-control" id="activity_status" name="status[]" ><option value="1" >Working</option><option value="2" >Completed</option><option value="3" >Already Set</option><option value="4" >Suggested</option></select></div><div class="act-file-link"><button type="button"  placeholder="File Link:" class="form-control file_link" id="file_link" name="file_link" data-pd-popup-open="addProgressPopup" data-attr="'+length+'"><i class="fa fa-paperclip"></i>Add Progress</button><input type="hidden" name="activityfilelinked" value="blank" id="activityfilelinked"><input type="hidden" name="activityfilelink[]" id="activityfilelink" /></div><div class="act-hours dates" id="datepicker"><input type="text" class="form-control activity_date" id="activity_date" name="activity_date[]" placeholder="YYYY-MM-DD" autocomplete="off" readonly /></div><div class="act-hours time" id="timepicker"><input type="number" class="form-control activity_time" id="activity_hours" name="activity_hours[]" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "2"  placeholder="HH"/ ><input type="number" class="form-control activity_time" id="activity_seconds" name="activity_seconds[]" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0,this.maxLength);" maxlength = "2" placeholder="MM"/ ></div><div class="act-note"><textarea class="form-control activity_note" id="activity_note" name="notes[]" placeholder="Type something:" maxlength="120"></textarea></div><div class="img-append-section" style="display: none;"></div></div>');
         $('.activity_date').datepicker({format: 'yyyy-mm-dd',endDate: new Date(),autoHide:true});
    }
});

$(document).on('click','.removeMoreList',function(){
    $(this).parent().parent().remove();
});