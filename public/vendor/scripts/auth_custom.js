$(document).on('click','.consoleAuth',function(e){
  e.preventDefault();
  $('.consolecampaignId').val($(this).attr('data-id'));
});

$(document).on('click','#addNewGoogleSearchConsoleAUth',function(){
  var campaignId = $('.consolecampaignId').val();
  var currentRoute = $('.currentRoute').val();
  // window.location.href = BASE_URL +'/connect_google_analytics?campaignId='+campaignId+'&provider=search_console&redirectPage='+currentRoute;
  window.location.href = BASE_URL +'/connect_search_console?campaignId='+campaignId+'&redirectPage='+currentRoute;

});

$(document).on('click','.analyticAuth',function(e){
  e.preventDefault();
  $('.analyticcampaignId').val($(this).attr('data-id'));
});

$(document).on('click','#AuthaddNewGoogleAnalytics',function(){
  var campaignId = $('.analyticcampaignId').val();
  var currentRoute = $('.analyticcurrentRoute').val();

  window.location.href = BASE_URL +'/connect_google_analytics?campaignId='+campaignId+'&provider=google&redirectPage='+currentRoute;
});


$(document).on('click','.adwordsAuth',function(e){
  e.preventDefault();
  $('.adscampaignId').val($(this).attr('data-id'));
});

$(document).on('click','#AuthaddNewGoogleAds',function(){
  var campaignId = $('.adscampaignId').val();
  var currentRoute = $('.adscurrentRoute').val();
  window.location.href = BASE_URL +'/connect_google_ads?campaignId='+campaignId+'&redirectPage='+currentRoute;
});

$(document).ready(function(){
  $('#auth_existing_console_accounts').select2({width:'100%'});
});

$(document).on('change','#auth_existing_console_accounts',function(e){
  e.preventDefault();
  var console_id = $(this).val();
  var campaignID = $('.consolecampaignId').val();

  $.ajax({
      url:  BASE_URL + '/ajax_google_view_account/'+console_id+'/'+campaignID,
      type: 'GET',
      success: function (response) {
      $('#auth_console_account').html(response);


      }
  });
});


$(document).on('change','#auth_existing_gmb_accounts',function(e){
  e.preventDefault();
  var console_id = $(this).val();
  var campaignID = $('.gmbcampaignId').val();

  $.ajax({
      url:  BASE_URL + '/ajax-gmb-connect-account/'+console_id+'/'+campaignID,
      type: 'GET',
      success: function (response) {
      $('#auth_gmb_account').html(response);


      }
  });
});


$(document).ready(function(){
 $('.jsauth_console_account').select2({width:'100%'});
});






$(document).on('click','.auth_saveConsoleData',function(e){
  e.preventDefault();
  var campaignID = $('.consolecampaignId').val();
  var existing_console_accounts = $('#auth_existing_console_accounts').val();
  var console_account = $('#auth_console_account').val();
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
    data:{campaignID:campaignID,existing_console_accounts:existing_console_accounts,console_account:console_account},
    success:function(result){
      if (result['status'] == 'success') {
        Command: toastr["success"]('Your detail saved successfully');
        window.location.href = BASE_URL+ '/authorization';
      } 
      else if(result['status'] == 'google-error'){
       Command: toastr["warning"](result['message']);
       $('.auth_saveConsoleData').prop('disabled',false);
       return false;
     }
     else {
       Command: toastr["warning"]('Please try again, getting error');
       $('.auth_saveConsoleData').prop('disabled',false);
       return false;
     }
   }, 
   error:function(err){
    console.log('err: '+err);
  }
});
});


$('.jsAuthExistingAccounts').select2({width:'100%'});

$(document).on('change','#auth_existing_accounts',function(){
  var account_id = $('#auth_existing_accounts').val();
  var campaignID = $('.analyticcampaignId').val();

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $.ajax({
    // url:  BASE_URL + '/ajax_google_view_account/'+account_id+'/'+campaignID,
    url:  BASE_URL + '/ajax_google_view_account_analytics/'+account_id+'/'+campaignID,
    type: 'GET',
    success: function (response) {
      $('#auth_analytic_account').html(response);
      var li    = '<option value=""><--Select Property --></option>';
      $('#auth_analytic_property').html(li);
      var li    = '<option value=""><--Select View ID  --></option>';
      $('#auth_analytic_view_id').html(li);
    }
  });
});

$(document).ready(function(){
  $('.jsAuthAnalyticAccount').select2({width:'100%'});
});

$(document).on("change", "#auth_analytic_account", function (e) {
  var property_id = $(this).val();
  if(property_id != '') {
    $.ajax({
      type:    "GET",
      url:     BASE_URL+ "/ajax_google_property_data/"+property_id,
      success: function(result) {
        $('#auth_analytic_property').html(result);
      }
    });
  }
});

$(document).ready(function(){
  $('.jsAuthAnalyticProperty').select2({width:'100%'});
});


$(document).on("change", "#auth_analytic_property", function (e) {
  var property_id = $(this).val();
  $.ajax({
    type:    "GET",
    url:     BASE_URL+ "/ajax_google_viewId_data/"+property_id,
    success: function(result) {
      $('#auth_analytic_view_id').html(result);
    }
  });
});

$(document).ready(function(){
  $('.jsAuthAnalyticView').select2({width:'100%'});
});

$(document).on("click", ".auth_saveData", function (e) {
  e.preventDefault();
  var analytic_view_id    = $('#auth_analytic_view_id').val();
  var analytic_property_id  = $('#auth_analytic_property').val();
  var analytic_account_id   = $('#auth_analytic_account').val();
  var google_account_id   = $('#auth_existing_accounts').val();
  var campaignID = $('.analyticcampaignId').val();
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
      var status = result['status'];
      if (status == 'success') {
        Command: toastr["success"]('Your detail saved successfully');
        window.location.href = BASE_URL+ '/authorization';
      } else {
        Command: toastr["warning"]('Please try again getting error');
      }
    }
  });
});
$(document).on('change','#auth_existing_ads_accounts',function(e){
  e.preventDefault();
  var ads_account_id = $(this).val();
  var campaignID = $('.adscampaignId').val();

  $.ajax({
   url:  BASE_URL + '/ajax_google_ads_campaigns/'+ads_account_id+'/'+campaignID,
   type: 'GET',
   success: function (response) {
    $('#auth_ads_accounts').html(response);
  }
});
});

$(document).on("click", ".auth_saveAdsData", function (e) {
  e.preventDefault();
  var ads_accounts = $('#auth_ads_accounts').val();
  var existing_ads_accounts = $('#auth_existing_ads_accounts').val();
  var campaignID = $('.adscampaignId').val();
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
     var status = result['status'];
     if (status == 'success') {
      Command: toastr["success"]('Your detail saved successfully');
      window.location.href = BASE_URL+ '/authorization';
    } else {
      Command: toastr["warning"]('Please try again getting error');
    }
  }
});
});

$(document).on('click','.Authtags',function(e){
  e.preventDefault();
  $('.campID').val($(this).attr('data-id'));
  $('.tags_auth').tagsinput('removeAll');
  $.ajax({
    type:    "GET",
    url:     BASE_URL+ "/ajax_get_tags",
    data:    {campId: $(this).attr('data-id')},
    dataType: 'json',
    success: function(result) {
      $('.tags_auth').tagsinput('add',result);
    }
  });
});


$(document).on('click','#saveTagsAuth',function(e){
  var campId = $('.campID').val();
  var tags = $('.tags_auth').val();
  if(tags == ''){
    Command: toastr["error"]('Add tag(s) to save !');
    return false;
  }

  $.ajax({
    type:    "POST",
    url:     BASE_URL+ "/ajax_save_tags",
    data:    {campId: campId,  tags: tags , _token:$('meta[name="csrf-token"]').attr('content')},
    dataType: 'json',
    success: function(response) {
      if(response['status'] == 'success'){
        Command: toastr["success"]('Tags saved successfully!');
      }else if(response['status'] == 'error'){
        Command: toastr["error"]('Error updating tags !');
      }
      setTimeout(function(){ window.location.href = BASE_URL+ '/authorization'; }, 5000);


    }
  });
});

$(document).on('click','.auth_analytic_refresh',function(e){
  e.preventDefault();


  $(this).prop('disabled',true );
  var analytic_id =  $('#auth_existing_accounts').val();
  var campaignID = $('.analyticcampaignId').val();
  if(analytic_id == ''){
    Command: toastr["error"]('Select id to refresh data.');
    $(this).prop('disabled',false);
    return false;
  }
  else{
    $('.auth_analytic_refresh i').css('display','none');
    $('.update_google_loader').show();
    $.ajax({
      url:BASE_URL + '/ajax_update_analytics',
      data:{analytic_id:analytic_id,_token:$('meta[name="csrf-token"]').attr('content'),request_id:campaignID},
      type:'POST',
      dataType:'json',
      success:function(response){
        $('.auth_analytic_refresh i').css('display','block');
        $('.update_google_loader').hide();
        $('.auth_analytic_refresh').prop('disabled',false);

        if(response['status']==1){
          Command: toastr["success"](response['message']);
          return false;
        }else{
         Command: toastr["error"](response['message']);
         return false;
       }

     }
   });
  }

});

$(document).on('click','.auth_search_refresh',function(e){
  e.preventDefault();

  $(this).prop('disabled',true);
  var sc_id =  $('#auth_existing_console_accounts').val();
  var campaignID = $('.consolecampaignId').val();
  if(sc_id == ''){
    Command: toastr["error"]('Select id to refresh data.');
    $(this).prop('disabled',false);
    return false;
  }
  else{
    $('.auth_search_refresh i').css('display','none');
    $('.update_google_loader').show();
    $.ajax({
      url:BASE_URL + '/ajax_update_search_console',
      data:{sc_id:sc_id,_token:$('meta[name="csrf-token"]').attr('content'),request_id:campaignID},
      type:'POST',
      dataType:'json',
      success:function(response){
        $('.auth_search_refresh i').css('display','block');
        $('.update_google_loader').hide();
        $('.auth_search_refresh').prop('disabled',false);

        if(response['status']==1){
          Command: toastr["success"](response['message']);
          return false;
        }else{
         Command: toastr["error"](response['message']);
         return false;
       }

     }
   });
  }

});

$(document).on('click','.AuthGmb',function(e){
  e.preventDefault();
  $('.gmbcampaignId').val($(this).attr('data-id'));
});

$(document).on('click','#addNewGMBAuth',function(){
  var campaignId = $('.gmbcampaignId').val();
  var currentRoute = $('.gmbcurrentRoute').val();
  window.location.href = BASE_URL +'/connect_gmb?campaignId='+campaignId+'&redirectPage='+currentRoute;

});

$(document).on('click','.auth_saveGmbData',function(e){
  e.preventDefault();

  var campaignID = $('.gmbcampaignId').val();
  var existing_console_accounts = $('#auth_existing_gmb_accounts').val();
  var console_account = $('#auth_gmb_account').val();
  if(existing_console_accounts == ''){
    Command: toastr["error"]('Select existing account');
    $(this).prop('disabled',false);
    return false;
  }

  if(console_account == ''){
    Command: toastr["error"]('Select GMB location.');
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
    url: BASE_URL+ "/ajax_save_gmb_location",
    data:{campaignID:campaignID,existing_console_accounts:existing_console_accounts,console_account:console_account},
    success:function(result){
      if (result['status'] == 'success') {
        Command: toastr["success"]('Your detail saved successfully');
        window.location.href = BASE_URL+ '/authorization';
      } 
      else if(result['status'] == 'google-error'){
       Command: toastr["warning"](result['message']);
       $('.auth_saveConsoleData').prop('disabled',false);
       return false;
     }
     else {
       Command: toastr["warning"]('Please try again, getting error');
       $('.auth_saveConsoleData').prop('disabled',false);
       return false;
     }
   }, 
   error:function(err){
    console.log('err: '+err);
  }
});
});