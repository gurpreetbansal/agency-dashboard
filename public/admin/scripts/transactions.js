var BASE_URL = $('.baseUrl').val();
$('.transactions').DataTable({
    'serverSide': true,
    "deferRender": true,
    "pageLength": 10,
    'ajax': {
        'url': BASE_URL + '/admin/ajaxTransactions',
        'type':"GET",
        'data':{_token:$('meta[name="csrf-token"]').attr('content')}
    }
});

$(document).on('click','.refund_subscription',function(e){
    e.preventDefault();
    $('.subscription_id').val($(this).attr('subscription-id'));
    $.ajax({
        type:'POST',
        url:BASE_URL + '/admin/ajax_refund_data',
        dataType:'json',
        data:{refund_type:'full',subscription_id:$(this).attr('subscription-id'),user_id: $(this).attr('user-id'),_token:$('meta[name="csrf-token"]').attr('content')},
        success:function(response){
            if(response['status'] == 1){
                $('.refundAmt').val(response['amount']);
                $('.refundTxt').html(response['msg']);
            }else if(response['status'] == 0){
                $('.refundAmt').val(response['amount']);
                $('.refundTxt').html('');
            }else if(response['status'] == 2){
                $('.refundTxt').html('');
            }
        }
    });
});

$(document).on('change','#refund_type',function(e){
e.preventDefault();
var subscription_id = $('.subscription_id').val();
$('.refund_type').val($(this).val());
$.ajax({
        type:'POST',
        url:BASE_URL + '/admin/ajax_refund_data',
        dataType:'json',
        data:{refund_type:$(this).val(),subscription_id:subscription_id,_token:$('meta[name="csrf-token"]').attr('content')},
        success:function(response){
            if(response['status'] == 1){
                $('.refundAmt').val(response['amount']); 
                $('.refundTxt').html(response['msg']);
            }else if(response['status'] == 2){
                document.getElementById('amount_to_refund').value = '';
                document.getElementById("amount_to_refund").placeholder = "Enter your amount..";
                $('.refundTxt').html('');
            }            
        }
    });
});

$(document).on('click','#refundTransaction',function(e){
    e.preventDefault();
    var refundType = $('.refund_type').val();
    var subscription_id = $('.subscription_id').val();
    var amount = $('.refundAmt').val();

    if(amount > 0){
        $.ajax({
            type:'POST',
            url:BASE_URL + '/admin/ajax_refund_payment',
            dataType:'json',
            data:{subscription_id:subscription_id,refundType:refundType,amount:amount,_token:$('meta[name="csrf-token"]').attr('content')},
            success:function(response){
                if(response['status'] == 1){
                     Command: toastr["success"](response['message']);
                     location.reload();
                     return false;
                 }else  if(response['status'] == 2){
                     Command: toastr["warning"](response['message']);
                     return false;
                 }else{
                     Command: toastr["error"](response['message']);
                     return false;
                 }
            }
        });
    }else{
        Command: toastr["error"]('Please enter amount to refund!');
        return false;
    }
});