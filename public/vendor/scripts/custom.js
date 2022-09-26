var BASE_URL = $('.base_url').val();

$(document).on('click','#submit_new_project',function () {

    var dashboardType = [];
    var domain_name = $('.newProjectdomain_name').val();
    var domain_url = $('.newProjectdomain_url').val();
    var domain_db = $('#regional_db').val();

    $('input:checkbox.dashboardType').each(function () {
        if(this.checked){
            dashboardType.push($(this).val());
        }
    });

    document.getElementById('domain_name_error').innerHTML = '';
    if (domain_name == '') {
        document.getElementById('domain_name_error').innerHTML = 'Domain Name is required.';
    }
    document.getElementById('domain_url_error').innerHTML = '';
    if (domain_url == '') {
        document.getElementById('domain_url_error').innerHTML = 'Domain Url is required.';
    } else if (!is_url(domain_url)) {
        document.getElementById('domain_url_error').innerHTML = 'Not a Valid url.';
        return false;
    }
    document.getElementById('dashboardType_error').innerHTML = '';
    if (dashboardType == '') {
        document.getElementById('dashboardType_error').innerHTML = 'Select atleast one dashboard type.';
    }


    if (domain_name != '' && domain_url !== '' && domain_db != '' && dashboardType!='') {
        $("body").css("opacity", 0.5);
        $(".loader").fadeIn();
        submitForm();
        return false;
    }
});
//});

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
function submitForm() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: BASE_URL +"/add_new_project",
        cache: false,
        data: $('form#addProject').serialize(),
        dataType: 'json',
        success: function (response) {
            $(".loader").fadeOut();
            $("body").css("opacity", 1);
            $("body").css("pointer", "none");
            if (response['status'] == 'success') {
                $('.successMsg').css('display', 'block');
                $('.successMsg').html(response['message']);
                setTimeout(function () {
                    $('.successMsg').fadeOut('fast');
                }, 3500);
                location.reload();
            } else if (response['status'] == 'error') {
                $('.errorMsg').css('display', 'block');
                $('.errorMsg').html(response['message']);
                setTimeout(function () {
                    $('.errorMsg').fadeOut('slow');
                }, 3500);
            }
        },
        error: function () {
            Command: toastr["warning"]('Error');
        }
    });
}

$('document').ready(function () {
    $('.regEmail').on('keyup', function () {
        var email = $('#email').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: 'check_email_exists',
            type: 'post',
            data: {
                'email': email,
            },
            success: function (response) {
                if (response == 'taken') {
                    var lblError = document.getElementById("lblError");
                    lblError.innerHTML = "";
                    lblError.innerHTML = "Email id available";
                }
            }
        });
    });
    $('#company_name').on('keyup', function () {
        var aa = $('#company_name').val();
        var company_name = aa.split(' ').join('');


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: 'check_company_name_exists',
            type: 'post',
            data: {
                'company_name': company_name,
            },
            success: function (response) {
                if (response == 'taken') {
                    var lblError = document.getElementById("lblErrorCompany");
                    lblError.innerHTML = "";
                    lblError.innerHTML = "Company Name already taken";
                }
                else{
                    var lblError = document.getElementById("lblErrorCompany");
                    lblError.innerHTML = "";
                }
            }
        });
    });

    $("#reg_btn").on('click', function () {
        $(".loader").fadeIn();
        var company_name = $('#company_name').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var package_id = $('.package_id').val();
        var state_value = $('.state_value').val();
//        alert(package_id);
if (company_name == '' || email == '' || password == '') {
    $('#error_msg').text('Fix the errors in the form first');
} else {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: 'doRegister',
        type: 'post',
        data: {
            'email': email,
            'password': password,
            'company_name': company_name,
            'package_id': package_id,
            'state_value': state_value
        },
        dataType:'json',
        success: function (response) {
            console.log(response);
            if (response['status'] == 'stripe_subscription') {
                window.location.href = 'stripe_subscription';
            } else if (response['status'] == 'pricing') {
// window.location.href = 'pricing';
window.location.href = 'price';
}
}
});
}

});
});


$(document).ready(function(){

    $('.jsExistingAccounts').select2({width:'100%'});

//$('.selectAnalyticID').on('click',function(){
    $(document).on('change','#existing_accounts',function(){
        var account_id = $('#existing_accounts').val();
        var campaignID = $('.campaignId').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url:  BASE_URL + '/ajax_google_view_account_analytics/'+account_id+'/'+campaignID,
            type: 'get',
            success: function (response) {
                $('#analytic_account').html(response);
                var li 		=	'<option value=""><--Select Property --></option>';
                $('#analytic_property').html(li);
                var li 		=	'<option value=""><--Select View ID  --></option>';
                $('#analytic_view_id').html(li);
            }
        });
    });
    $('.jsanalytic_account').select2({width:'100%'});

    $(document).on("change", "#analytic_account", function (e) {
        var property_id = $(this).val();
        if(property_id != '') {
            $.ajax({
                type:    "get",
                url:     BASE_URL+ "/ajax_google_property_data/"+property_id,
                success: function(result) {
                    $('#analytic_property').html(result);
                    

                }
            });
        }
    });
    $('.jsanalytic_property').select2({width:'100%'});


    $(document).on("change", "#analytic_property", function (e) {
        var property_id = $(this).val();
        $.ajax({
            type:    "get",
            url:     BASE_URL+ "/ajax_google_viewId_data/"+property_id,
            success: function(result) {
                $('#analytic_view_id').html(result);
               
            }
        });
    });
    $('.jsanalytic_view_id').select2({width:'100%'});


    $(document).on("click", ".saveData", function (e) {
        e.preventDefault();
        var analytic_view_id		= $('#analytic_view_id').val();
        var analytic_property_id	= $('#analytic_property').val();
        var analytic_account_id 	= $('#analytic_account').val();
        var google_account_id	 	= $('#existing_accounts').val();
        var campaignID = $('.campaignId').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:    "POST",
            url:     BASE_URL+ "/ajax_save_analytics_data",
            data:    {analytic_view_id: analytic_view_id, google_account_id: google_account_id, analytic_property_id: analytic_property_id, analytic_account_id: analytic_account_id, request_id: campaignID},
            dataType: 'json',
            success: function(result) {
                var status	=	result['status'];
                if (status == 'success') {
                    Command: toastr["success"]('Your detail saved successfully');

                    $('.box.analytics').addClass('active');
                    $(".modal-backdrop").remove();
                    $('body').removeClass('modal-open');
                    $('body').css('padding-right', '');
                    $("#ConnectGoogleAnalyticsModal").hide();


                } else if(status == 'error') {
                    Command: toastr["error"]('Please try again getting error');
                } else if(status == 'analytics-error'){
                   Command: toastr["error"](result['message']);
               } else{
                   Command: toastr["error"]('Please try again getting error');
               }
           }
       });
    });


    $('.selectAdsID').on('click',function(){
        var ads_account_id = $('#existing_ads_accounts').val();
        var campaignID = $('.campaignId').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url:  BASE_URL + '/ajax_google_ads_campaigns/'+ads_account_id+'/'+campaignID,
            type: 'get',
            success: function (response) {

                $('#ads_accounts').html(response);
            }
        });
    });


    $(document).on("click", ".saveAdsData", function (e) {
        e.preventDefault();
        var ads_accounts = $('#ads_accounts').val();
        var existing_ads_accounts = $('#existing_ads_accounts').val();
        var campaignID = $('.campaignId').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type:    "POST",
            url:     BASE_URL+ "/ajax_save_google_ads_data",
            data:    {existing_ads_accounts: existing_ads_accounts,  ads_accounts: ads_accounts, request_id: campaignID},
            dataType: 'json',
            success: function(result) {
                var status	=	result['status'];
                if (status == 'success') {

                    Command: toastr["success"]('Your detail saved successfully');

                    $('.box.adwords').addClass('active');
                    $(".modal-backdrop").remove();
                    $('body').removeClass('modal-open');
                    $('body').css('padding-right', '');
                    $("#ConnectGoogleAdsModal").hide();
                    $('a[href="#PPC"]').trigger('click');
                } else {
                    Command: toastr["warning"]('Please try again getting error');
                }
            }
        });
    });


$(document).ready(function(){
    $('.jsExistingConsoleAccount').select2({width:'100%'});
});

    /*search console*/
    $(document).on('change','#existing_console_accounts',function(e){
        e.preventDefault();
        var console_id = $(this).val();
        var campaignID = $('.campaignId').val();

        $.ajax({
            url:  BASE_URL + '/ajax_google_view_account/'+console_id+'/'+campaignID,
            type: 'GET',
            success: function (response) {
                $('#console_account').html(response);
                var li 		=	'<option value=""><--Select Analytic Account --></option>';
                $('#console_property').html(li);
                var li 		=	'<option value=""><--Select Console Account  --></option>';
                $('#console_view_id').html(li);
            }
        });
    });

    $('.jsConsoleAccount').select2({width:'100%'});


    $(document).on('change','#console_account',function(e){
        e.preventDefault();
        var property_id = $(this).val();
        if(property_id != '') {
            $.ajax({
                type:    "get",
                url:     BASE_URL+ "/ajax_google_property_data/"+property_id,
                success: function(result) {
                    $('#console_property').html(result);
                  

                }
            });
        }
    });

    $(document).on('change','#console_property',function(e){
        e.preventDefault();
        var property_id = $(this).val();
        $.ajax({
            type:    "get",
            url:     BASE_URL+ "/ajax_google_viewId_data/"+property_id,
            success: function(result) {
                $('#console_view_id').html(result);
                
            }
        });
    });


    $(document).on('click','.saveConsoleData',function(e){
        e.preventDefault();
        var campaignID = $('.campaignId').val();
        var existing_console_accounts = $('#existing_console_accounts').val();
        var console_account = $('#console_account').val();
        var console_property = $('#console_property').val();
        var console_view_id = $('#console_view_id').val();


        if(existing_console_accounts == ''){
            Command: toastr["error"]('Select existing account');
            $(this).prop('disabled',false);
            return false;
        }

        if(console_account == ''){
            Command: toastr["error"]('Select URL to link account.');
            $(this).prop('disabled',false);
            return false;
        }


        $(this).prop('disabled',true);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type:'POST',
            dataType:'json',
            url: BASE_URL+ "/ajax_save_console_data",
            data:{campaignID:campaignID,existing_console_accounts:existing_console_accounts,console_account:console_account,console_property:console_property,console_view_id:console_view_id},
            success:function(result){
                if (result['status'] == 'success') {
                    Command: toastr["success"]('Your detail saved successfully');
                    window.location.href = BASE_URL+ '/new-dashboard/'+campaignID;
                } 
                else if(result['status'] == 'google-error'){
                    Command: toastr["warning"](result['message']);
                    $('.saveConsoleData').prop('disabled',false);
                    return false;
                }
                else {
                    Command: toastr["warning"]('Please try again, getting error');
                    $('.saveConsoleData').prop('disabled',false);
                    return false;
                }
            },
            error:function(err){
                console.log('err: '+err);
            }
        });
    });
});

$(document).ready(function() {
    $('#dashboardType').multiselect({
        buttonClass: 'btn btn-outline-light',
        buttonWidth: '460px',
        textAlign:'left'
    });
});


$(function() {
    $('.dfs_locations').select2({
        minimumInputLength: 2,
        width:'100%',
        ajax:{
            url: BASE_URL + "/ajax_dfs_locations",
            dataType: 'json',
            type: "GET",
            data: function (query) {
                return {
                    query: query
                };
            },
            processResults: function (data) {
//console.log(data);
return {
    results: $.map(data, function (item) {
        return {
            text: item.name,
            id: item.name
        }
    })
};			
}

}
});

    $('.regions').select2({width:'100%'});

});

$('#add_new_keyword').on('click', function(){
// getUpdateRow();
// return false;

var domain_url = $('.domain_url').val();
var keyword_ranking = $('.keyword_ranking').val();
var regions = $('.regions').val();
var tracking_options = $('.tracking_options').val();
var language = $('.language').val();
var dfs_locations = $('.dfs_locations').val();
var lines  = $('.keyword_ranking').val().split(/\n/);


/*error section start */
document.getElementById('domain_url_error').innerHTML = '';
document.getElementById('keyword_ranking_error').innerHTML = '';
document.getElementById('regions_error').innerHTML = '';
document.getElementById('tracking_options_error').innerHTML = '';
document.getElementById('language_error').innerHTML = '';
document.getElementById('dfs_locations_error').innerHTML = '';


if (domain_url == '') {
    document.getElementById('domain_url_error').innerHTML = 'Field is required.';
} 
// else if (!is_url(domain_url)) {
// document.getElementById('domain_url_error').innerHTML = 'Not a Valid url.';
// return false;
// }

if (keyword_ranking == '') {
    document.getElementById('keyword_ranking_error').innerHTML = 'Field is required.';
}
if (regions == '') {
    document.getElementById('regions_error').innerHTML = 'Field is required.';
}
if (tracking_options == '') {
    document.getElementById('tracking_options_error').innerHTML = 'Field is required.';
}

if (language == '') {
    document.getElementById('language_error').innerHTML = 'Field is required.';
}

if (dfs_locations == '' || dfs_locations == null) {
    document.getElementById('dfs_locations_error').innerHTML = 'Field is required.';
}

/*error section end */

if (domain_url != '' && keyword_ranking !== '' && regions != '' && tracking_options!='' && language!='' && dfs_locations!='') {	



    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var form_data  = $('form#addNewKeyword').serializeArray();	

    $('#liveKeywordTracking').addClass('hide');
    $("#yeskws_txt").hide();
    $('#multipleUpdate').addClass('active');

    $.ajax({
        type: "POST",
        url: BASE_URL + '/ajax_dfs_keyword_tracking',
        data: form_data,
        dataType: 'json',
        success: function(result) {
            var keyword = 0;

            $.each(lines, function(i, line){
                keyword++;
            });

            if (result['status'] == '2') {
                $('#multipleUpdate').removeClass('active');
                Command: toastr["warning"](result['message']);
                return false;
            }

            $('#keywords_update').show();
            $('.keyword-progress-bar').show();
            $('.progress-bar').attr('style','width:0%');
            $('.progress-bar').html('0%');

            $('#strating').html('0/');
            $('#total_keywords').html(keyword);


            if (result['status'] == '1') {


//$('#LiveKeywordTrackingTable').DataTable().ajax.reload();
setTimeout(function(){ 
    getUpdateRow();
    updateTimeAgo(); 
}, 1000);

Command: toastr["success"](result['message']);
} else {
    Command: toastr["warning"](result['message']);
}
}
});
}
});

function updateTimeAgo(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url:  BASE_URL + '/ajaxUpdateTimeAgo',
        data: {request_id:$('.campaignID').val()},
        dataType: 'json',
        success: function(result) {
            if (result['status'] == '1') {
                $('#yeskws_txt').html(result['time']);
                $("#yeskws_txt").show();
            }
        }
    });
}

function getUpdateRow(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: BASE_URL + '/ajaxgetLatestKeyword',
        data: {request_id: $('.campaignID').val()},
        dataType: ' json',
        success: function(result) {
            if (result['status'] == '1') {
//   if(result['html'] !='' ){
    if(result['sync'] == 0){

        var pPos = parseInt($('#total_keywords').text());

        var pEarned = parseInt(result['sync']);

        var perc=0;
        if(isNaN(pPos) || isNaN(pEarned)){
            perc=0;
        }else{
            perc = (((pPos-pEarned)/pPos) * 100).toFixed(0);
        }

        $('#strating').html(pPos - pEarned+'/');

        $('.progress-bar').attr('style','width:'+perc+'%');
        $('.progress-bar').html(perc+'%');


        setTimeout(function(){
            $('#LiveKeywordTrackingTable').DataTable().ajax.reload();
            $("#yeskws_txt").hide();
            $('#multipleUpdate').removeClass('active');
            $('.keyword-progress-bar').hide();
            $('#keywords_update').hide();

        }, 1000);


    }else{

        var pPos = parseInt($('#total_keywords').text());
        var pEarned = parseInt(result['sync']);
        var perc=0;
        if(isNaN(pPos) || isNaN(pEarned)){
            perc=0;
        }else{
            perc = (((pPos-pEarned)/pPos) * 100).toFixed(0);
        }

        $('#strating').html(pPos - pEarned+'/');

        $('.progress-bar').attr('style','width:'+perc+'%');
        $('.progress-bar').html(perc+'%');

        setTimeout(function(){
            getUpdateRow();
        },2000);
    }
// }
} else{
    $("#yeskws_txt").hide();
    $("#LiveKeywordTrackingTable tbody").append(result['html']);
}
}
});

}




$("#LiveKeywordTrackingTable").on("click", ".chart-icon-star", function(){

    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: BASE_URL + "/ajax_mark_keyword_favorite",
        data: {type: 'favorite', keyword_id:id, request_id: $('.campaignID').val()},
        dataType: 'json',
        success: function(result) {
// console.log(result);
if (result['status'] == '1') {
    $('#LiveKeywordTrackingTable').DataTable().ajax.reload();
    Command: toastr["success"](result['message']);
} else {				 
    Command: toastr["warning"](result['message']);
}
}
});

});


$('#LiveKeywordTrackingTable .selectall').click(function() {
    if ($("#LiveKeywordTrackingTable .selectall").is(':checked')) {
        $('#LiveKeywordTrackingTable input[type=checkbox]').prop('checked', true);
    } else {
        $('#LiveKeywordTrackingTable input[type=checkbox]').prop('checked', false);
    }
});


$(document).on('click keyup','.serpTd',function(e){
    if(e.which == 13){
        $(this).attr("contentEditable",false);
        var updated_val     =   $(this).text();
        var old_value       =   $(this).attr('data-value');
        if(updated_val != old_value ) {
            updateStartRankPosition(updated_val,$(this).attr('data-id'));
        }

        return e.which != 13;

    }else{
        $('.serpId').prop("contentEditable", false);

        if($(this).attr("contentEditable") == true){
            $(this).attr("contentEditable",false);
        } else {
            $(this).attr("contentEditable",true);
        }
    }
});

$(document).on('focusout paste', ".serpTd", function(e){
    e.preventDefault();
    $(this).attr("contentEditable",false);
    var updated_val     =   $(this).text();
    var old_value       =   $(this).attr('data-value');
    if(updated_val != old_value ) {
        updateStartRankPosition(updated_val,$(this).attr('data-id'));
    }
});

function updateStartRankPosition(updated_val,request_id){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: BASE_URL + "/ajax_update_keyword_startRank",
        data: {start_ranking: updated_val, request_id: request_id },
        success: function(result) {

            $('#LiveKeywordTrackingTable').DataTable().ajax.reload();
            if(result['status'] == '1'){
                Command: toastr["success"](result['message']);
            }else{
                Command: toastr["warning"](result['message']);
            }
        }
    });
}
$('#multipleUpdate').on('click', function(e){
    e.preventDefault();	
    var checked = []
    var keyword = 0;
    $("input[name='check_list[]']:checked").each(function () {
        checked.push(parseInt($(this).val()));
        keyword++;
    });



    $('#multipleUpdate').addClass('active');
    $('#keywords_update').show();
    $('.keyword-progress-bar').show();
    $('.progress-bar').attr('style','width:0%');
    $('.progress-bar').html('0%');

    $('#strating').html('0/');
    $('#total_keywords').html(keyword);


    if(checked.length > 0){
        $.ajax({
            type: "POST",
            url: BASE_URL + "/ajax_update_tracking",
            data: {selected_ids:checked, request_id: $('.campaignID').val()},
            dataType: 'json',
            success: function(result) {
                getUpdateRow();
                if(result['status'] == '1'){
                    Command: toastr["success"](result['message']);
                } else if(result['status'] == '2'){
                    Command: toastr["warning"]('Please try again!');
                } else{
                    Command: toastr["warning"](result['message']);
                }
            }
        });
    }else{
        $('#multipleUpdate').removeClass('active');
        $('.keyword-progress-bar').hide();
    }
});


$('#multipleDelete').on('click', function(e){
    e.preventDefault();

    if(!confirm("Are you sure you want to delete this?")){
        return false;
    }

    var checked = [];
    $("input[name='check_list[]']:checked").each(function () {
        checked.push(parseInt($(this).val()));
        $('#tablerow'+$(this).val()).remove();

    });

    $("#multipleUpdate").addClass('active');

    if(checked.length > 0 ){
        $.ajax({
            type: "POST",
            url: BASE_URL+ "/ajax_delete_multiple_keywords",
            data: {selected_ids:checked, request_id: $('.campaignID').val()},
            dataType: 'json',
            success: function(result) {
//console.log(result);
if (result['status'] == '1') {
    $('#LiveKeywordTrackingTable').DataTable().ajax.reload();
    $("#multipleUpdate").removeClass('active');

    Command: toastr["warning"](result['message']);
} else {
    Command: toastr["warning"](result['message']);
}
}
});
    } else{
        Command: toastr["warning"]('Select Keyword(s) to delete!');
    }

});




$("#LiveKeywordTrackingTable").on("click", ".chart-icon", function(){
    var keyword_id =  $(this).data('id');
    var request_id =  $(this).data('index');
    var duration =  '-30 day';
    $('#liveKeywordTrackingChart').removeClass('hide');
    $('html, body').animate({
        scrollTop: $("#keywordchartConatiner").offset().top
    }, 100);

    localStorage.setItem("keywordId", keyword_id);
    drawChart(keyword_id,request_id, duration);
});


function drawChart(keyword_id, request_id, duration ) {
    $.ajax({
        type: "POST",
        url: BASE_URL + "/ajax_live_keyword_chart", 
        data: { keyword_id: keyword_id,request_id: request_id,duration: duration},
        success: function(result) {
            var series =  [{
                name: 'google',
                data: result['rank']
            },
            ];
            createAnalyticsCharts(series, result['month'], result['keyword']);
        }
    });
}


/*highchart data manupulation */
function createAnalyticsCharts(seriesData, month, keyword) {
    Highcharts.chart('keywordchartConatiner', {
        chart: {
            type: 'line'
        },
        title: {text: keyword},
        yAxis: {
            reversed: true,
            title: {
                text: 'Rank'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]  
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        xAxis: {categories:month},
        tooltip: {
            valueSuffix: ''
        },
        series: seriesData,
    });
}


$('#close-graph').on('click',function(){
    $('#liveKeywordTrackingChart').addClass('hide');
});

function  drawChartGraph(requestId, days) {
    var keywordId = localStorage.getItem("keywordId");		
    drawChart(keywordId, requestId, days)

}



/*ajax calls for campaign detail page*/
$(document).ready(function(){
    var currentUrl = window.location.pathname;
// console.log('currentUrl '+currentUrl);
if(currentUrl.search('campaigndetail/*')!=-1){
    updateTimeAgo();
    extraOrganicKeywords($('.campaignID').val());
    ajaxSerpStatData($('.campaignID').val());
    ajaxReferringDomains($('.campaignID').val());
    ajaxOrganicKeywordRanking($('.campaignID').val());
    ajaxGoogleAnalyticsGoal($('.campaignID').val());
    ajaxGoogleTrafficGrowth($('.campaignID').val());
    ajaxGoogleTrafficGrowth_data($('.campaignID').val());
    ajaxGoogleSearchConsole($('.campaignID').val());
} 
});

function extraOrganicKeywords(campaignId){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: BASE_URL + "/ajax_dfs_extra_organic_keywords", 
        data: {request_id: campaignId},
        success: function(result) {
// console.log(result);
}
});
}

function ajaxSerpStatData(campaignId){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type:"POST",
        url:$('.base_url').val()+"/ajax_serp_stat",
        data:{campaign_id: campaignId},
        dataType:'json',
        success:function(result){
            console.log(result);
        }
    });
}

function ajaxReferringDomains(campaignId){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type:"GET",
        url:$('.base_url').val()+"/ajax_referring_domains",
        data:{campaign_id: campaignId},
        dataType:'json',
        success:function(result){
            if(result != ''){
                $('.backlinks_total').text(result['total']);
                $('.backlinks_avg').text(result['avg']);

                var avg = document.getElementsByClassName("backlinks_avg");
                if(result['avg'] > 0 ){
                    $(avg).parent().find('i').addClass("fa-angle-up");	
                    $(avg).parent().addClass("text-success");
                }else{
                    $(avg).parent().find('i').addClass("fa-angle-down");
                    $(avg).parent().addClass("text-danger");
                }
            }
        }
    });
}

function ajaxOrganicKeywordRanking(campaignId){
    $.ajax({
        type:"GET",
        url:$('.base_url').val()+"/ajax_organicKeywordRanking",
        data:{campaignId: campaignId},
        dataType:'json',
        success:function(result){
            var position = document.getElementsByClassName("googleRankPosition");
            $('.GoogleRanking').text(result['totalCount']);
            $(position).text(result['organic_keywords']+'%');

            if(result['organic_keywords'] > 0 ){
                $(position).parent().find('i').addClass("fa-angle-up");	
                $(position).parent().addClass("text-success");
            }else{
                $(position).parent().find('i').addClass("fa-angle-down");
                $(position).parent().addClass("text-danger");
            }

        }
    });
}


function ajaxGoogleAnalyticsGoal(campaignId){
    $.ajax({
        type:"GET",
        url:$('.base_url').val()+"/ajax_googleAnalyticsGoal",
        data:{campaignId: campaignId},
        dataType:'json',
        success:function(result){


            if(result != ''){
                $('.analyticsTotalGoal').text(result['total']);
                $('.analyticsgoalResult').text(result['goal_result']);

                var goal_result = document.getElementsByClassName("analyticsgoalResult");
                if(result['goal_result'] > 0 ){
                    $(goal_result).parent().find('i').addClass("fa-angle-up");	
                    $(goal_result).parent().addClass("text-success");
                }else{
                    $(goal_result).parent().find('i').addClass("fa-angle-down");
                    $(goal_result).parent().addClass("text-danger");
                }
            }					
        }
    });
}

function ajaxGoogleTrafficGrowth(campaignId){
    $.ajax({
        type:"GET",
        url:$('.base_url').val()+"/ajax_get_traffic_data",
        data:{campaignId: campaignId},
        dataType:'json',
        success:function(result){
            if(result['status'] == 0){
                $('#analatic_add').css('display','block');
                return false;
            }
            highChartMapload(result);
            $('#traffic_loader').hide();
            $('#analatic_add').css('display','none');

        }
    });
}

function ajaxGoogleTrafficGrowth_data(campaignId){
    $.ajax({
        type:"GET",
        url:$('.base_url').val()+"/ajax_get_traffic_metrics",
        data:{campaignId: campaignId},
        dataType:'json',
        success:function(result){
            if(result['status'] == 0){
                $('#analatic_add').css('display','block');
            }
            $('.TrafficGrowth').text(result['total_sessions']);
            $('.comparedTrafficGrowth').text(result['final_session']);
            var trafficcGrowth = document.getElementsByClassName("TrafficGrowth");
            if(result['total_sessions'] > 0){
                $(trafficcGrowth).parent().find('i').addClass("fa-angle-up");    
                $(trafficcGrowth).parent().find('i').addClass("text-success");
            }else{
                $(trafficcGrowth).parent().find('i').addClass("fa-angle-down");
                $(trafficcGrowth).parent().find('i').addClass("text-danger");
            }



            $('.TotalSessions').text(result['total_users']);
            $('.comparedUsers').text(result['final_users']);

            var sess = document.getElementsByClassName("TotalSessions");
            if(result['total_users'] > 0 ){
                $(sess).parent().find('i').addClass("fa-angle-up");   
                $(sess).parent().find('i').addClass("text-success");
            }else{
                $(sess).parent().find('i').addClass("fa-angle-down");
                $(sess).parent().find('i').addClass("text-danger");
            }


            $('.TotalPageViews').text(result['total_pageviews']);
            $('.comparedPageViews').text(result['final_pageviews']);

            var pagesview = document.getElementsByClassName("TotalPageViews");
            if(result['total_pageviews'] > 0 ){
                $(pagesview).parent().find('i').addClass("fa-angle-up");  
                $(pagesview).parent().find('i').addClass("text-success");
            }else{
                $(pagesview).parent().find('i').addClass("fa-angle-down");
                $(pagesview).parent().find('i').addClass("text-danger");
            }

            var traffic_growth = document.getElementsByClassName("GoogleTraffic_growth");
            if(result['traffic_growth'] > 0 ){
                $(traffic_growth).parent().find('i').addClass("fa-angle-up");  
                $(traffic_growth).parent().find('i').addClass("text-success");
            }else{
                $(traffic_growth).parent().find('i').addClass("fa-angle-down");
                $(traffic_growth).parent().find('i').addClass("text-danger");
            }

            $('.GoogleTraffic_growth').text(result['traffic_growth']);
            $('.GoogleOrganicVisitors').text(result['current_session']);
        }
    });
}




var color = Chart.helpers.color;
var configTrafficGrowth = {
    type: 'line',
    data: {
        labels: [],
        datasets: [{
            label: "Current Period: ",
            fill: true,
            backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
            borderColor: color(window.chartColors.blue).alpha(1.0).rgbString(),
            data: [],
        }			 
        ]
    },
    options: {
        maintainAspectRatio: false,
        title: {
            display: false,
            text: 'Chart.js Line Chart'
        },
        tooltips: {
            mode: 'index',
            intersect: false,
        },
        hover: {
            mode: 'nearest',
            intersect: true
        },
        scales: {
            xAxes: [{
                display: true,
                scaleLabel: {
                    display: false,
                    labelString: 'Month'
                }
            }],
            yAxes: [{
                display: true,
                scaleLabel: {
                    display: true,
                    labelString: 'Value'
                }
                , ticks: {
                    min: 0,
                }
            }]
        }
    }
};

var configSearchConsole = {
    type: 'line',
    data: {
        datasets: [{
            label: 'Clicks',
            yAxisID: 'lineId',
            backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
            borderColor: window.chartColors.blue,
            data:[],
            pointRadius: 0,
            fill: false,
            lineTension: 0,
            borderWidth: 2
        },{
            label: 'Impressions',
            yAxisID: 'barId',
            backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
            borderColor: window.chartColors.red,
            data: [],
            pointRadius: 0,
            fill: true,
            lineTension: 0,
            borderWidth: 2
        }

        ]
    },
    options: {
        maintainAspectRatio:false,
        scales: {
            xAxes: [{
                type: 'time',
                distribution: 'series',
                offset: true,
                ticks: {
                    major: {
                        enabled: true
                    },
                    source: 'data',
                    autoSkip: true,
                    autoSkipPadding: 30,
                    maxRotation: 0,
                    sampleSize: 30
                }

            }],
            yAxes: [
            {
                id: 'lineId',
                gridLines: {
                    drawBorder: false
                },
                scaleLabel: {
                    display: true,
                    labelString: 'Clicks'
                },
                ticks: {
                    beginAtZero: true
                },
                position:'left'
            },
            {
                id: 'barId',
                ticks: {
                    beginAtZero: true
                },
                gridLines: {
                    drawBorder: false
                },
                scaleLabel: {
                    display: true,
                    labelString: 'Impression'
                },
                position:'right'
            }
            ]
        },
        tooltips: {
            intersect: false,
            mode: 'index',
            callbacks: {
                label: function(tooltipItem, myData) {
                    var label = myData.datasets[tooltipItem.datasetIndex].label || '';
                    if (label) {
                        label += ': ';
                    }
                    label += parseFloat(tooltipItem.value).toFixed(2);
                    return label;
                }
            }
        }
    }
};


function highChartMapload(result) {   
    if (window.myLineTraffic) {
        window.myLineTraffic.destroy();
    }
    var ctxTrafficGrowth = document.getElementById('canvas-traffic-growth').getContext('2d');
    window.myLineTraffic = new Chart(ctxTrafficGrowth, configTrafficGrowth);

    configTrafficGrowth.data.labels =  result['from_datelabel'];
    configTrafficGrowth.data.datasets[0].label = 'Current Period: '+result['current_period'];
    configTrafficGrowth.data.datasets[0].data = result['count_session'];

    if(result['compare_status'] == 1){
        var newDataset = {
            label: 'Previous Period: '+result['previous_period'],
            borderColor: color(window.chartColors.red).alpha(1.0).rgbString(),
            backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
            data: result['combine_session']
        };

        configTrafficGrowth.data.datasets.push(newDataset);

    } else{
        configTrafficGrowth.data.datasets.splice(1, 1);

    }

    window.myLineTraffic.update();
}


function consoleChart(clicks,impressions){
    if(window.myLineSearchConsole){
        window.myLineSearchConsole.destroy();
    }

    var ctxSearchConsole = document.getElementById('canvas-search-console').getContext('2d');
    window.myLineSearchConsole = new Chart(ctxSearchConsole, configSearchConsole);

    configSearchConsole.data.datasets[0].data = clicks;
    configSearchConsole.data.datasets[1].data = impressions;
    window.myLineSearchConsole.update();
}







$('document').ready(function () {
    $(document).on('click','.graph_range',function(){
        var value = $(this).attr('data-value');
        var module = $(this).attr('data-module');
        var campaignId = $('.campaignID').val();

        $('.trafficSection').removeClass('active');
        $(this).addClass('active');
        $('.graph-loader.organic_traffic').css('display','block');
        $('#traffic_loader').show();
        $.ajax({
            type:"GET",
            url:$('.base_url').val()+"/ajax_get_traffic_date_range",
            data:{value: value,module:module,campaignId:campaignId},
            dataType:'json',
            success:function(result){
                if(result['compare_status'] == '1'){
                    $("#compare_graph").prop("checked", true);
                }

                highChartMap(result);
                $('#traffic_loader').hide();        
                $('.graph-loader').css('display','none');
            }
        });

        $.ajax({
            type:"GET",
            url:$('.base_url').val()+"/ajax_get_traffic_date_range_metrics",
            data:{value: value,module:module,campaignId:campaignId},
            dataType:'json',
            success:function(result){
                if(result['status'] == 0){
                    $('#analatic_add').css('display','block');
                }
                $('.TrafficGrowth').text(result['total_sessions']);
                $('.comparedTrafficGrowth').text(result['final_session']);
                var trafficcGrowth = document.getElementsByClassName("TrafficGrowth");
                if(result['total_sessions'] > 0){
                    $(trafficcGrowth).parent().find('i').addClass("fa-angle-up");    
                    $(trafficcGrowth).parent().find('i').addClass("text-success");
                }else{
                    $(trafficcGrowth).parent().find('i').addClass("fa-angle-down");
                    $(trafficcGrowth).parent().find('i').addClass("text-danger");
                }



                $('.TotalSessions').text(result['total_users']);
                $('.comparedUsers').text(result['final_users']);

                var sess = document.getElementsByClassName("TotalSessions");
                if(result['total_users'] > 0 ){
                    $(sess).parent().find('i').addClass("fa-angle-up");   
                    $(sess).parent().find('i').addClass("text-success");
                }else{
                    $(sess).parent().find('i').addClass("fa-angle-down");
                    $(sess).parent().find('i').addClass("text-danger");
                }


                $('.TotalPageViews').text(result['total_pageviews']);
                $('.comparedPageViews').text(result['final_pageviews']);

                var pagesview = document.getElementsByClassName("TotalPageViews");
                if(result['total_pageviews'] > 0 ){
                    $(pagesview).parent().find('i').addClass("fa-angle-up");  
                    $(pagesview).parent().find('i').addClass("text-success");
                }else{
                    $(pagesview).parent().find('i').addClass("fa-angle-down");
                    $(pagesview).parent().find('i').addClass("text-danger");
                }

                var traffic_growth = document.getElementsByClassName("GoogleTraffic_growth");
                if(result['traffic_growth'] > 0 ){
                    $(traffic_growth).parent().find('i').addClass("fa-angle-up");  
                    $(traffic_growth).parent().find('i').addClass("text-success");
                }else{
                    $(traffic_growth).parent().find('i').addClass("fa-angle-down");
                    $(traffic_growth).parent().find('i').addClass("text-danger");
                }

                $('.GoogleTraffic_growth').text(result['traffic_growth']);
                $('.GoogleOrganicVisitors').text(result['current_session']);
            }
        });
    });

    $(document).on('click','.searchConsole',function(){
        var value = $(this).attr('data-value');
        var module = $(this).attr('data-module');
        var campaignId = $('.campaignID').val();

        $('.sc_section').removeClass('active');
        $(this).addClass('active');
        $('.graph-loader.searchConsole').css('display','block');
        $('#console_loader').show();
        $.ajax({
            type:"GET",
            // url:$('.base_url').val()+"/ajax_googleSearchConsole",
            url:$('.base_url').val()+"/ajax_get_search_console_graph_date_range",
            data:{value: value,module:module,campaignId:campaignId},
            dataType:'json',
            success:function(result){
                console.log(result);
                if(result['status'] == 0){
                    $('#console_add').css('display','block');
                    return false;
                } 

                if(result['status'] == 1){
                    consoleChart(result['clicks'],result['impressions']);
                    $('#console_loader').css('display','none');
                    $('.graph-loader').css('display','none');
                    $('#console_add').css('display','none');
                }


            }
        });

        $.ajax({
            type:"GET",
            url:$('.base_url').val()+"/ajax_get_search_console_queries",
            data:{campaignId:campaignId,value: value},
            dataType:'json',
            success:function(result){
                console.log(result);
                if(result['query'] != ''){
                    $('.query_table').html(result['query']);
                }
            }
        });

        $.ajax({
            type:"GET",
            url:$('.base_url').val()+"/ajax_get_search_console_devices",
            data:{campaignId: campaignId,value: value},
            dataType:'json',
            success:function(result){
                if(result['device'] != ''){
                    $('.device_table').html(result['device']);
                }
            }
        });

        $.ajax({
            type:"GET",
            url:$('.base_url').val()+"/ajax_get_search_console_pages",
            data:{campaignId: campaignId,value: value},
            dataType:'json',
            success:function(result){
                if(result['page'] != ''){
                    $('.pages_table').html(result['page']);
                }
            }
        });

        $.ajax({
            type:"GET",
            url:$('.base_url').val()+"/ajax_get_search_console_country",
            data:{campaignId: campaignId,value: value},
            dataType:'json',
            success:function(result){
                if(result['country'] != ''){
                    $('.country_table').html(result['country']);
                }
            }
        });


    });
});

function highChartMap(result){
    window.myLineTraffic.data.labels =  result['from_datelabel'];
    window.myLineTraffic.data.datasets[0].label = 'Current Period: '+result['current_period'];
    window.myLineTraffic.data.datasets[0].data = result['count_session'];

    if(result['compare_status'] == '1'){
        window.myLineTraffic.data.datasets[1].borderColor =  color(window.chartColors.red).alpha(1.0).rgbString();
        window.myLineTraffic.data.datasets[1].backgroundColor =  color(window.chartColors.red).alpha(0.5).rgbString();
        window.myLineTraffic.data.datasets[1].fill =  true;
        window.myLineTraffic.data.datasets[1].label = 'Previous Period: '+result['previous_period'];
        window.myLineTraffic.data.datasets[1].data = result['combine_session'];
    }

    window.myLineTraffic.update();

}

$(document).on('change','.analyticsGraphCompare',function(e){
    e.preventDefault();
    var request_id  = $('.campaignID').val();
    var compare_status = $(this).is(':checked');
    if(compare_status == true){
        var compare_value = 1;
    }else{
        var compare_value = 0;
    }

    $('.graph-loader').css('display','block');
    $.ajax({
        type:'GET',
        dataType:'json',
        data:{request_id:request_id,compare_value:compare_value},
        url:$('.base_url').val()+"/ajax_get_compare_traffic_data",
        success:function(result){
            highChartMapload(result);
            $('.graph-loader').css('display','none');
        },
        error:function(error){
            console.log('error: '+error);
        }
    });


});




function ajaxGoogleSearchConsole(campaignId){
    $.ajax({
        type:"GET",
// url:$('.base_url').val()+"/ajax_googleSearchConsole",
url:$('.base_url').val()+"/ajax_get_search_console_graph",
data:{campaignId: campaignId},
dataType:'json',
success:function(result){

    if(result['status'] == 0){
        $('#console_add').css('display','block');
    } 

    if(result['status'] == 1){
        consoleChart(result['clicks'],result['impressions']);
        $('#console_add').css('display','none');
    }	
    $('#console_loader').hide();		
}
});

    $.ajax({
        type:"GET",
        url:$('.base_url').val()+"/ajax_get_search_console_queries",
        data:{campaignId: campaignId},
        dataType:'json',
        success:function(result){
            if(result['query'] != ''){
                $('.query_table').html(result['query']);
            }
        }
    });


    $.ajax({
        type:"GET",
        url:$('.base_url').val()+"/ajax_get_search_console_devices",
        data:{campaignId: campaignId},
        dataType:'json',
        success:function(result){
            if(result['device'] != ''){
                $('.device_table').html(result['device']);
            }
        }
    });

    $.ajax({
        type:"GET",
        url:$('.base_url').val()+"/ajax_get_search_console_pages",
        data:{campaignId: campaignId},
        dataType:'json',
        success:function(result){
            if(result['page'] != ''){
                $('.pages_table').html(result['page']);
            }
        }
    });

    $.ajax({
        type:"GET",
        url:$('.base_url').val()+"/ajax_get_search_console_country",
        data:{campaignId: campaignId},
        dataType:'json',
        success:function(result){
            if(result['country'] != ''){
                $('.country_table').html(result['country']);
            }
        }
    });
}



/*Campaign settings page */
$(document).ready(function(){
    $('.settingsProjectStartDate').datepicker();

    $("#general_settings").validate({
        rules: {
            domain_register: {
                required: true
            },
            regional_db: {
                required: true
            },
            domain_name: {
                required: true
            },
            domain_url:{
                required:true
            },
            clientName:{
                required:true
            }
        },
        messages: {
            domain_register: {
                required: "Please provide domain register date",
            },
            email: {
                required: "Please provide regional database",
            },
            domain_name: {
                required: "Please provide domain name",
            },
            domain_url:{
                required: "Please provide domain url"
            },
            clientName:{
                required:"Please provide client name"
            }
        },
        submitHandler: function(form) {
            var new_data = $('#general_settings').serializeArray();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: BASE_URL + "/ajax_save_campaign_general_settings",
                data: new_data,
                dataType: 'json',
                success: function(result) {
                    if (result['status'] == 'success') {
                        Command: toastr["success"]('Your detail updated successfully');
                    } else {
                        Command: toastr["error"]('Please try again getting error');
                    }
                }
            });
        }
    });



    $("#profile_details").validate({
        rules: {
            company_name: {
                required: true
            }, 
            client_name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            mobile: {
                required: true
            }
// ,
// manager_name:{
//     required:true
// },
// manager_email:{
//     required:true,
//     email: true
// }
},
messages: {
    company_name: {
        required: "Please provide company name address",
    },
    client_name: {
        required: "Please provide agency owner name",
    },
    email: {
        required: "Please provide email address",
        email: "Email address is not valid"
    },
    mobile: {
        required: "Please provide contact number",
    }
// ,
// manager_name: {
//     required: "Please provide manager name",
// },
// manager_email: {
//     required: "Please provide email address for manager",
//     email: "Email address is not valid"
// }
},
submitHandler: function(form) {
    var new_data = $('#profile_details').serializeArray();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: BASE_URL + "/ajax_save_campaign_white_label",
        data: new_data,
        dataType: 'json',
        success: function(result) {
            if (result['status'] == 'success') {
                Command: toastr["success"](result['message']);
            } else {
                Command: toastr["warning"]('Please try again getting error');
            }
        }
    });
}
});



    $(document).on('click','#CampaignDashboardSettings',function(e){
        e.preventDefault();
        var new_data = $('#dashboard_settings').serializeArray();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: BASE_URL + "/ajax_save_dashboard_settings",
            data: new_data,
            dataType: 'json',
            success: function(result) {
                if(result['status'] == 1){
                    Command: toastr["success"](result['message']);
                }
            }
        });
    });


});

$(document).ready(function(){
    var settingTabActive = window.location.hash;
    if (settingTabActive.match('#')) {
        $('li.nav-item .active').removeClass('active');
        $('a[href="' + settingTabActive + '"]').addClass('active');


        $('.SettingsSection').removeClass("active");
        $("#"+settingTabActive.split('#')[1]).addClass('in show active');
    } 
});


$('.setting-tabss').on('click',function(){
    window.location.hash = $(this).attr('href');

    var url = document.location.toString(); 
    if (url.match('#')) {
        $('li.nav-item .active').removeClass('active');
        $(this).addClass('active');


        $('.SettingsSection').removeClass("active");
        $("#"+url.split('#')[1]).addClass('in show active');
    } 

});


$(document).on('keyup','.projects_autocomplete',function(){
    var search = $(this).val();
    if(search == ''){
        $(document).find('.result_conatainer').hide();
    } else{ 
        $.ajax({
            type:'GET',
            data:{search:search},
            dataType:'json',
            url:BASE_URL +'/project_search_autocomplete',
            success:function(result){
// console.log(result);

$(document).find('#defaultCampaignList').html(result);
// $(document).find('.result_conatainer ul').html(result);
// $(document).find('.result_conatainer ul').show();
}
});
    }
});

function openCity(evt, cityName) {

    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}


$(document).on('click', '.shareModal', function (e) {
    e.preventDefault();
    $.ajax({
        type : 'GET',
        url : BASE_URL + '/ajax_show_view_key', 
        data : {rowid: $(this).attr('data-id')}, 
        dataType: 'json',
        success : function(data){
            $('#copy_share_key_value').val(data);
        }
    });
});



$(document).on('click','.copyText', function(e) {
    var copyText = document.getElementById("copy_share_key_value");
    copyText.select();
    copyText.setSelectionRange(0, 99999)
    document.execCommand("copy");
    $('#copy_text').text('Text Copied');
    $('#copy_text').show();
    $('#copy_text').delay(5000).fadeOut('slow');
});

function fileValidation() { 
    var fileInput =  
    document.getElementById('file'); 

    var filePath = fileInput.value; 

// Allowing file type 
var allowedExtensions =  
/(\.jpg|\.jpeg|\.png)$/i; 

if (!allowedExtensions.exec(filePath)) { 
    alert('Invalid file type'); 
    fileInput.value = ''; 
    return false; 
}  
else  
{ 

// Image preview 
if (fileInput.files && fileInput.files[0]) { 
    var reader = new FileReader(); 
    console.log('image: '+reader.readAsDataURL(fileInput.files[0]));
    reader.onload = function(e) { 
        document.getElementById( 
            'imagePreview').innerHTML =  
        '<img src="' + e.target.result 
        + '"/>'; 
    }; 

    reader.readAsDataURL(fileInput.files[0]); 
} 
} 
} 

$('#input-tags').selectize({
    delimiter: ',',
    persist: false,
    createOnBlur: true,
    create: true,
    maxItems: 2
});


$('.managerList').select2({
    minimumInputLength: 2,
    width:'100%',
    ajax:{
        url: BASE_URL + "/ajax_account_managers",
        dataType: 'json',
        type: "GET",
        data: function (query) {
            return {
                query: query
            };
        },
        processResults: function (data) {
            console.log(data);
            return {
                results: $.map(data, function (item) {
                    return {
                        text: item.name,
                        id: item.name
                    }
                })
            };          
        }

    }
});