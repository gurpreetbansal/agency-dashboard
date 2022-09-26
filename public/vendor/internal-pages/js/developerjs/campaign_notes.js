var BASE_URL = $('.base_url').val();
$(function() {
  $('.campaign_notes_date').datepicker({endDate: new Date(),autoHide: true,date :new Date()});
  ExistingCampaignNotes($('.campaign_id').val());
  $(".notes-list").find("article").each(function(){
    var Close = $(this).find(".note-close");
    var This = $(this);
    Close.on('click',function(){
      $(this).parent().remove();
    });
  });
});


function ExistingCampaignNotes(campaign_id){
  $.ajax({
    type:'GET',
    dataType:'json',
    url:BASE_URL+'/ajax_get_campaign_notes',
    data:{campaign_id},
    success:function(response){
      if(response['status'] == 1){
        setTimeout(function(){
          // $('.notes-list #mCSB_1_container').html(response['html']);
          $('.notes-list').html(response['html']);
          //if no note(article) is created
          if($('.notes-list').find('article').length == 0){
            $("body").find(".new-note").slideToggle();
            $('#cancelNote').hide();
            $('#clearNote').show();
            $('#newNote').hide();
            $("body").find('.notes-popup-inner').css('height','auto');
            $("body").find('.notes-list').css('height','auto');
          }
        },100);
      }
    }
  });
}

$(document).on("click", '#notesPopup, #NotesBtnClose', function() {
  $("body").find(".notes-popup").toggleClass("open");
  if($('.notes-list').find('article').length == 0){
    $(".new-note").show();
    $('#cancelNote').hide();
    $("body").find('.notes-popup-inner').css('height','auto');
    $("body").find('.notes-list').css('height','auto');
    $('#newNote').hide();
  }else{
    $('#clearNote').hide();
    $('#cancelNote').show();
    $('#newNote').show();
  }
});

$(document).on("click", '#newNote', function() {
  $(this).parent().css('display','none');
  $("body").find(".notes-list").toggleClass("small");
  $("body").find(".new-note").slideToggle();
});

$(document).on("click", '#cancelNote', function() {
  $('#newNote').parent().css('display','block');
  $("body").find(".notes-list").removeClass("small");
  $("body").find(".new-note").slideToggle();
  $("body").find('.notes-popup-inner').css('overflow','hidden');
});


$(document).on("keyup select", '#CampaignNoteForm input, #CampaignNoteForm textarea', function(e) {
   if ($('.campaign_notes_date').val() == '') {
    $('.campaign_notes_date').addClass('error');
  }else{
    $('.campaign_notes_date').removeClass('error');
  }

  if ($('.campaign_notes').val() == '') {
    $('.campaign_notes').addClass('error');
  }else{
    $('.campaign_notes').removeClass('error');
  }

  if($('.campaign_notes_date').val() !='' && $('.campaign_notes').val() !=''){
    $('#addNote').removeAttr('disabled','disabled');
  }else{
    $('#addNote').attr('disabled','disabled');
  }  
});

$(document).on("click", '#clearNote', function() {
    $('#addNote').attr('disabled','disabled');
    $('.campaign_notes_date,.campaign_notes').removeClass('error');
});


$(document).on('click','#addNote',function(e){
  e.preventDefault();
  var date = $('.campaign_notes_date').val();
  var note = $('.campaign_notes').val();
  var campaign_id = $('.campaign_id').val();
  if(date !='' && note !=''){
    $('.notes-progress-loader').css('display','block');
    $('#addNote').attr('disabled','disabled');
    $.ajax({
      type:'POST',
      data:{date,note,campaign_id,_token:$('meta[name="csrf-token"]').attr('content')},
      dataType:'json',
      url:BASE_URL +'/ajax_create_campaign_notes',
      success:function(response){
        if(response['status'] == 1){
            $('#newNote').parent().css('display','block');
            $("body").find(".notes-list").removeClass("small");
            $("body").find('.notes-list').removeAttr('style');
            $("body").find(".new-note").slideToggle();
            $("body").find('.notes-popup-inner').removeAttr('style');
            $("body").find('.notes-popup-inner').css('overflow','hidden');
            $('.notes-list #mCSB_1_container').html('');
            $('#cancelNote').show();
            $('#clearNote').hide();
            $('#newNote').show();
            ExistingCampaignNotes(campaign_id);
            $('form#CampaignNoteForm')[0].reset();
            Command: toastr["success"]('Note added successfully.');
        }

        if(response['status'] == 0){
          Command: toastr["success"]('Error, adding note.');
        }

        $('.notes-progress-loader').css('display','none');
      }
    });
  }
});

$(document).on('click','.articleRemove',function(){
  var note_id = $(this).attr('data-id');
  var campaign_id = $('.campaign_id').val();
  if(!confirm("Do you want to delete this note")){
    return false;
  }
  $.ajax({
    type:'GET',
    data:{note_id,campaign_id},
    dataType:'json',
    url:BASE_URL +'/ajax_remove_campaign_note',
    success:function(response){
      if(response['status'] == 1){
            ExistingCampaignNotes(campaign_id);
            Command: toastr["success"]('Note removed successfully.');
            
        }

        if(response['status'] == 0){
          Command: toastr["success"]('Error, removing note.');
        }
    }
  });
 
});