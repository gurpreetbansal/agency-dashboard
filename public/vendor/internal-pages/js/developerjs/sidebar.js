var BASE_URL = $('.base_url').val();

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
    searchByColumn($('.projects_autocomplete').val());
    $('#refresh-sidebar-search').css('display','none'); 
  }
});

$(document).ready(function(){
  var campaign_id = $('.campaign_id').val();
  sidebar_section(campaign_id);
});

function sidebar_section(campaign_id){
  $.ajax({
    type:'GET',
    //dataType:'json',
    url:BASE_URL +'/all_campaigns?id='+campaign_id,
    success:function(result){
      $(document).find('#defaultCampaignList').html(result);
      scrollbar();
    }
  });
}

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


/*hide share key on outside click*/
$(document).click((event) => {
  if (!$(event.target).closest('#ShareKey').length && !$(event.target).closest('.share-key-inner').length) {
    $('.share-key-btn').val("Click to copy");
    $(".share-key-popup").removeClass("open");
  }     
});