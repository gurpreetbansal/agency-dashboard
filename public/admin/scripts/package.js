var BASE_URL = $('.baseUrl').val();

$(document).on('click','#addfeature',function(e){
	addnewrow();
});


 $(document).delegate('#removefeature', 'click', function(){
     $(this).parent().parent().remove();
   });

function addnewrow(){
	var html = '<div class="appended"><div class="col-md-9 form-group"><label for="features" class="control-label">Features Included</label><input class="form-control" name="features[]" type="text" id="features"></div><div class="col-md-3 form-group"><button type="button" id="removefeature"><i class="fa fa-minus"></i></button></div>';
	$('#FeatureSection').append(html);
}

$(document).on('click','#deletefeature',function(e){
	e.preventDefault();

    if(!confirm("Are you sure you want to delete this feature?")){
        return false;
    }

	$.ajax({
		type:'POST',
		url: BASE_URL + '/admin/ajax_delete_package_feature',
		data:{id:$(this).attr('data-id'),_token:$('meta[name="csrf-token"]').attr('content')},
		dataType:'json',
		success:function(response){
			if(response['status'] == 1){
				Command: toastr["success"](response['message']);
				$('.existingFeatures').load(location.href + " #existingFeatures");
				return false;
			} else if(response['status'] == 0){
				Command: toastr["error"](response['message']);
				return false;
			}
		}
	});
});