$(window).scroll(function() {
  if($(window).scrollTop() + $(window).height() >= $(document).height()){
     var selected = $("a",this).attr('href');
     
     // if($('#SEO_tab').hasClass('uk-active') == true && $('#seo_sidebase').hasClass('active') == true){
     //    if ($('#SEO').find('.main-data-view').length >= 1) { 
     //        if ($('#seoDashboard').find('#seoDashboardMore').length == 0) { 
     //           $('#seoDashboard').append('<div id="seoDashboardMore"></div>');
     //           $('#seoDashboardMore').load('/campaign_seo_content_viewmore/' + $('.campaign_id').val()+'/'+$('#encriptkey').val(), function(responseTxt, statusTxt, xhr){
     //              if(statusTxt == "success")
     //                 var value ='';
     //                 var currentUrl = window.location.pathname;
     //                 seo_Scripts_viewmore($('.campaignID').val(),currentUrl,value,null);      

     //              if(statusTxt == "error")
     //                 console.log("Error: " + xhr.status + ": " + xhr.statusText);
     //           });
                 

     //        }
     //    }
     // }
     
     if($('#PPC_tab').hasClass('uk-active') == true && $('#ppc_sidebase').hasClass('active') == true){
        if ($('#PPC').find('.main-data-view').length >= 1) { 
          if ($('#ppcDashboard').find('#ppcDashboardMore').length == 0) { 
             $('#ppcDashboard').append('<div id="ppcDashboardMore"></div>');
             $('#ppcDashboardMore').load('/campaign_ppc_content_viewmore/' + $('.campaign_id').val());
             var value ='';
             var currentUrl = window.location.pathname;
            setTimeout(function () {
                 ppc_Scripts_viewmore($('.account_id').val());  
            },500);
          }
        }
     }
		 
     
  }
});