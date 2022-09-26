<script type="text/javascript">
  var BASE_URL = '<?php echo url("/"); ?>';

	$(document).ready(function() {
   $('.multiselect', this.el).multiselect({
    numberDisplayed: 1,
    includeSelectAllOption: true,
    placeholder: 'Select restrictions'
  });

   $('body').delegate('#del_row', 'click', function(){
     $(this).parent().parent().remove();
   });
 });

  $('#addnew').click(function(){
   addnewrow();
   multiselect();
 });
  function addnewrow(){
    var count = ($('#tbl_body tr').length - 0) - 1;
    var row =  '<tr>' +
    '<td><input type = "text" placeholder = "Name" class="form-control settings_name" autocomplete="off" name="name[]" required /></td>' +
    '<td><input type = "email" id="email" placeholder = "email@example.com" class="form-control sharedEmail" autocomplete="off" name="email[]" required /><span id="sharedlblError" class="red"></span></td>' +
    '<td><input type = "password" placeholder = "Password" class="form-control" autocomplete="off" name="password[]" required /></td>' +
    '<td><div class="row col-md-12"><input type="file" name="image[]"  class="image"></div></td>'+
    '<td><select class="form-control multiselect1" multiple name="restrictions['+count+'][]" required ><?php if(!empty($projects) && isset($projects)){ foreach($projects as $project){?><option value="{{$project->id}}">{{$project->domain_name}}</option><?php } } ?></select></td>' +
    '<td><select class="form-control" name="access[]" required ><option value="4">View Only(Client)</option><option value="3" selected>Addon User (Manager)</option></select></td>' +
    '<td><a href="javascript:;" id="del_row" title="Click to Delete this Row"><i class="fa fa-minus"></i></a></td>' +
    '</tr>';

    $('#tbl_body').append(row);
  }

  function multiselect(){
   $('.multiselect1', this.el).multiselect({
    numberDisplayed: 1,
    includeSelectAllOption: true,
    placeholder: 'Select Restrictions',
    minHeight:200
  });
 }


 $(document).on('keyup','.sharedEmail', function () {
  var email = $(this).val();
  var k =[];
  var input = document.getElementsByName('email[]'); 
   $(input).each(function () {
        k.push($(this).val());
    });

   k.splice(-1,1)

  
  var found = k.indexOf(email);
  console.log('emails' +found);
    if(found != '-1'){
      $('.errorCount').val('1');
    Command: toastr["error"]('Email already exists');
     return false;
    }
});

 $("#frm_data").on("submit", function(event){
 	event.preventDefault();

  if($('.errorCount').val() == '1'){
     Command: toastr["error"]('Please validate email(s).');
     return false;
  }

 	var data = new FormData(this);
   jQuery.each($('input[name^="image"]')[0].files, function(i, file) {
     data.append(i, file);
   });

   $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
   $.ajax({
    type: "POST",
    url: BASE_URL + "/save_settings",
    cache: false,
    contentType: false,
    processData: false,
    data: data,
    dataType: 'json',
    success: function (response) {
     if(response['status'] == 1){
      Command: toastr["success"](response['message']);
      setTimeout(function(){ location.reload(); }, 3000);          
    }
    if(response['status'] == 0){
      Command: toastr["warning"](response['message']);
    }
  },
  error: function () {
   Command: toastr["warning"]('Error');
 }
});
 });
</script>