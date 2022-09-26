<script type="text/javascript">
$('#auth_campaigns').DataTable({
    "serverSide" : true,
    "pageLength": 25,
    "ajax" : {
     url:BASE_URL + "/ajax_auth_campaigns",
     type:"GET"
   }
 });

</script>