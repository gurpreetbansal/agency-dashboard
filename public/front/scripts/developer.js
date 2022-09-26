$(document).on("click", "[data-pd-popup-open]", function (e) {
    var targeted_popup_class = $(this).attr("data-pd-popup-open");
    $('[data-pd-popup="' + targeted_popup_class + '"]').fadeIn(100);
    $("body").addClass("popup-open");
    e.preventDefault();
  });

  $(document).on("click", "[data-pd-popup-close]", function (e) {
    var targeted_popup_class = $(this).attr("data-pd-popup-close");
    $('[data-pd-popup="' + targeted_popup_class + '"]').fadeOut(200);
    $("body").removeClass("popup-open");
    e.preventDefault();
  });

$(document).on('click','.pricing-action',function () {
    $('#price').val($(this).attr('data-amount'));
    $('#state').val($(this).attr('data-state'));
    
if($('.user_id').val()!=''){
    $.ajax({
        url:'ajax_user_pricing_action',
        type:'GET',
        dataType:'json',
        data:{id:$(this).attr('data-amount'),state:$(this).attr('data-state'),user_id:$('.user_id').val()},
        success:function(response){
            window.location.href = 'subscription?id='+response['string'];
        }
    });
}else{
    $.ajax({
    	url:'ajax_pricing_action',
    	type:'GET',
    	dataType:'json',
    	data:{id:$(this).attr('data-amount'),state:$(this).attr('data-state'),user_id:$('.user_id').val()},
    	success:function(response){
			window.location.href = 'register?id='+response['string'];
    	}
    });
}
});
$(document).on('click','#yearly',function(){
    $('.pricing-btn').attr('data-state','year');
    $('.freeForever').attr('data-state','free');
    // if($('.free-price .pricing-btn').attr('data-amount') == 5){
    //     $('.pricing-btn').attr('data-state','free');
    // }
    if($(this).attr('data-attr') == 'yearly'){
        $('#CurrentPlan').css('display','none');
        $('#SelectPlan').css('display','block');
    }
});

$(document).on('click','#monthly',function(){
    $('.pricing-btn').attr('data-state','month');
    $('.freeForever').attr('data-state','free');
    // if($('.free-price .pricing-btn').attr('data-amount') == 5){
    //     $('.pricing-btn').attr('data-state','free');
    // }
     if($(this).attr('data-attr') == 'monthly'){
        $('#CurrentPlan').css('display','block');
        $('#SelectPlan').css('display','none');
    }
});


$(document).on('click','.pricing-downgrade',function(e){
    e.preventDefault();

    $.ajax({
        url:'ajax_check_pricing_downgrade',
        type:'GET',
        dataType:'json',
        data:{id:$(this).attr('data-amount'),state:$(this).attr('data-state'),user_id:$('.user_id').val()},
        success:function(response){
            if(response['status'] == 0){
                $('#downgrade-popup').trigger('click');
                $('#downgrade-popup').css('display', 'block');
                $('body').addClass('popup-open');
            }

            if(response['status'] == 1 && response['package_type'] == 'paid'){
                window.location.href = 'subscription?id='+response['string'];
            }

            if(response['status'] == 1 && response['package_type'] == 'free'){
                $('#confirm-downgrade-popup').trigger('click');
                $('#confirm-downgrade-popup').css('display', 'block');
                $('body').addClass('popup-open');
                $('.redirect_url').val('subscription?id='+response['string']);
            }
        }
    });
});

$(document).on('click','#continue_downgrade',function(e){
    e.preventDefault();
    $.ajax({
        type:'GET',
        url:'ajax_continue_downgrade',
        success:function(response){
            if(response['status'] == 1){
                window.location.href = response['message'];
            }

            if(response['status'] == 0){
                Command: toastr["error"]('Error!!');
                return false;
            }
        }
    });
});

$(document).on('click','#cancel_downgrade',function(){
    $('#downgrade-popup').trigger('click');
    $('#downgrade-popup').css('display', 'none');
    $('body').removeClass('popup-open');
});

$(document).on('click','.take-to-plan',function(e){
    e.preventDefault();
    $.ajax({
        type:'GET',
        url:'ajax_take_to_profile',
        success:function(response){
            if(response['status'] == 1){
                window.location.href = response['message'];
                document.cookie = "previous_url=pricing; domain=agencydashboard.io";
            }

            if(response['status'] == 0){
                Command: toastr["error"]('Error!!');
                return false;
            }
        }
    });
});


$(document).on('click','#confirm_downgrade',function(e){
    e.preventDefault();
    window.location.href = $('.redirect_url').val();
});

$(document).on('click','#cancel_confirm_downgrade',function(){
    $('#confirm-downgrade-popup').trigger('click');
    $('#confirm-downgrade-popup').css('display', 'none');
    $('body').removeClass('popup-open');
});