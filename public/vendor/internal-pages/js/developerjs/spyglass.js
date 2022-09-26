$(document).ready(function(){

    var keyenc = $('#keyenc').val();
    var baseurl = $('#baseurl').val();
    $.ajax({
        type : 'GET',
        url : baseurl+'ajax-spyglass/'+keyenc, 
        success : function(data){
             /*$('html').html(data);*/
            var newHTML = document.open("text/html", "replace");
            newHTML.write(data);
            newHTML.close();
        }
    });
});
