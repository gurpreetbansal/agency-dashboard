<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript">
	$(function(){

		$(".logo img").each(function(){
			var url = this.src.replace('agencydashboard.io', "www.google.com");
			this.src = url;
		});

	    var branding = '<p style="background-color: #ffffff;color: #8495b1;line-height: 22px;text-align:  center;padding: 25px;border: 1px dashed #ccc;">';
	    branding += '<img src="https://agencydashboard.io/public/front/img/logo.png" alt="Logo">';
	    branding += '<br><br>This is the page we found on the day of the ranking update. Please note that the current live page may differ, depending on which datacenter you are connected to.<br>';
	    branding += '<br><a target="_blank" href="{{ $data->result_se_check_url }}">';
	    branding += '<small><u>Check URL</u></small></a><br><br>';
	    branding += '<svg height="22px" version="1.1" viewBox="0 0 22 22" width="22px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><title></title><desc></desc><defs></defs><g fill="none" fill-rule="evenodd" id="action" stroke="none" stroke-width="1"><g id="alert-notification-info-attention"><path d="M11,22 C17.0751322,22 22,17.0751322 22,11 C22,4.92486775 17.0751322,0 11,0 C4.92486775,0 0,4.92486775 0,11 C0,17.0751322 4.92486775,22 11,22 Z M11,20 C15.9705627,20 20,15.9705627 20,11 C20,6.02943725 15.9705627,2 11,2 C6.02943725,2 2,6.02943725 2,11 C2,15.9705627 6.02943725,20 11,20 Z" fill="#F2C500"></path><path d="M10,6 L12,6 L12,13 L10,13 L10,6 Z M10,14 L12,14 L12,16 L10,16 L10,14 Z" fill="#F59D00"></path></g></g></svg> </p>';

	     $('#tvcap').html(branding);

	   $("#search a").each(function(){
	        var anchorText = $(this).attr("href");
	        var url = '{{ $data->host_url }}';
	        /*var url = '7pandas.com';*/
	        if(anchorText !== undefined){
                if (anchorText.includes(url) == true){
                    $(this).addClass("anchorSelected");
                    $( this ).closest( ".g" ).addClass( "selectedHighlight" );
                }
	        }
	   });
	   // var counter =1;
	   // $("#search").find('.g').each(function(index ){
	   //      var current = $(this);
	   //      if($(this).find('.g').length == 0 ){
	   //          if($(this).closest('.g-blk').length == 1){
	   //              return;
	   //          }
	   //          $( this ).closest( ".g" ).closest( ".g" ).prepend( '<span class="numbering">#'+ counter +'</span>');
	   //          counter = parseInt(counter)+ parseInt(1);
	   //      }
	   // });

	   setTimeout(function() {
	   		$('html,body').animate({ scrollTop: $(".selectedHighlight").offset().top},'slow');
	   },1000);	

	   // $(".selectedHighlight").animate({ scrollTop: 0 }, "slow");

	});
</script>
<?php echo $dom; ?>
<link rel="stylesheet" href="{{URL::asset('public/vendor/internal-pages/css/spyglass.css?v='.time())}}">