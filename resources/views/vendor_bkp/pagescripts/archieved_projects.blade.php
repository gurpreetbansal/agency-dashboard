<script type="text/javascript">
	var BASE_URL = $('.base_url').val();
	$(document).ready(function(){
		fill_datatable();
	});

	function fill_datatable(){
		$('#archievedcampaigns').DataTable({
			"serverSide" : true,
			"pageLength": 25,
			"ajax" : {
				url:BASE_URL + "/ajax_list_archived_projects",
				type:"GET",
			}
		});
	}

	$(document).on('click','.restore_project',function(e){
		e.preventDefault();
		if (!confirm("Are you sure you want to restore?")) {
			return false;
		} 
		var request_id = $(this).attr('data-id');

		if(request_id !=''){
			$.ajax({
				type:'POST',
				data:{request_id:request_id,_token:$('meta[name="csrf-token"]').attr('content')},
				url: BASE_URL +'/ajax_restore_project',
				dataType:'json',
				success:function(response){
					if(response['status'] == 1){
						Command: toastr["success"](response['message']);
						$('#archievedcampaigns').DataTable().destroy();
						fill_datatable();
						return false;
					}else if(response['status'] == 0){
						Command: toastr["error"](response['message']);
						return false;
					}else{
						Command: toastr["error"]('Error!! Please try again.');
						return false;
					}
				}
			});
		}
	});




	$(document).on('click','.delete_project',function(e){
		e.preventDefault();
		if (!confirm("Are you sure you want to delete?")) {
			return false;
		} 

		var request_id = $(this).attr('data-id');
		if(request_id !=''){
			$.ajax({
				type:'POST',
				url:BASE_URL +'/ajax_delete_project',
				data:{request_id:request_id,_token:$('meta[name="csrf-token"]').attr('content')},
				dataType:'json',
				success:function(response){
					if(response['status'] == 1){
						Command: toastr["success"](response['message']);
						$('#archievedcampaigns').DataTable().destroy();
						fill_datatable();
						return false;
					}else if(response['status'] == 0){
						Command: toastr["error"](response['message']);
						return false;
					}else{
						Command: toastr["error"]('Error!! Please try again.');
						return false;
					}
				}
			});
		}
	});
</script>