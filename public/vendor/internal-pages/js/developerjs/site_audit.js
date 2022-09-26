$(document).on('click','.sAudit-nav a',function(){
    if($('.audit-share-key').find('#audit_id').length > 0 || $('.viewkey-output').find('.audit-id').length > 0){
        var total_offset_percent = $('.sAudit-nav').outerHeight() + 15;
    }else{
        var total_offset_percent = $('header').outerHeight() + $('.sAudit-nav').outerHeight() + 15;
    }
    
    var target = $(this).attr('href');
    if(target.length){
        $('html, body').animate({
            scrollTop: $(target).offset().top - total_offset_percent
        }, 1000);
        return false;
    }
});

function sAudit_nav_OnScroll(event){
    event.preventDefault();
    var scrollPos = $(document).scrollTop();
    $('.sAudit-nav a[href^="#"]').each(function(){
        var current_link = $(this);
        
        var target_element = $(current_link.attr("href"));
        if($('.audit-share-key').find('#audit_id').length > 0 || $('.viewkey-output').find('.audit-id').length > 0){
            var total_offset_percent = $('.sAudit-nav').outerHeight();
        }else{
           var total_offset_percent = $('header').outerHeight() + $('.sAudit-nav').outerHeight(); 
        }
        
        if(target_element.position() !== undefined){
            if (target_element.position().top - total_offset_percent <= scrollPos) {
                $(this).addClass('active').siblings().removeClass("active");
            } else {
                $(this).removeClass("active");
            }
        }
       
    });
};

$(document).ready(function () {
    $(document).on("scroll", sAudit_nav_OnScroll);
});

function sticksAudit_nav() {
    var window_top = $(window).scrollTop();
    var sAudit_nav = $('.sAudit-nav');
    if($('.audit-page-details').css('display') === 'block' || $('.sa-audit-details').css('display') === 'block'){
        if(sAudit_nav.offset() !== undefined){
            var sAudit_nav_offset_top = sAudit_nav.offset().top;
            var siteHeader_height = $('header').outerHeight();
            if($('.audit-share-key').find('#audit_id').length > 0 || $('.viewkey-output').find('.audit-id').length > 0){
                var sAudit_nav_offset_percent = sAudit_nav_offset_top;
            }else{
                var sAudit_nav_offset_percent = sAudit_nav_offset_top - siteHeader_height;
            }
            

            if (window_top >= sAudit_nav_offset_percent) {
                sAudit_nav.addClass('active');
            } else {
                sAudit_nav.removeClass('active');
            }
            if($('.audit-share-key').find('#audit_id').length > 0 || $('.viewkey-output').find('.audit-id').length > 0){
                sAudit_nav.css('top', '0px')
            }else{
                sAudit_nav.css('top', ''+siteHeader_height+'px') 
            }
            
        }
    }
}

$(function() {
    $(window).scroll(sticksAudit_nav);
    sticksAudit_nav();
});