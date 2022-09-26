/*Site audit click to copy*/
var BASE_URL = $('.base_url').val();
var APP_URL = $('.app_url').val();

$(document).on("click", '#ShareKey', function (e) {
  e.preventDefault();
  $(".share-key-popup").addClass("open");
  if($(this).attr('data-share-key') !== ''){
    var getValue = APP_URL+'project-detail/'+$(this).attr('data-share-key');
    $('.copy_share_key_value').val(getValue);
    $('.project-id').val($(this).attr('data-id'));
  }else{
     var project_id = $(this).attr('data-id');
       reset_share_key(project_id);
  }
});

$(document).on("click", '.close-share-key', function () {
  $('.share-key-btn').val("Click to copy");
  $(".share-key-popup").removeClass("open");
});

new ClipboardJS('.btn.share-key-btn');

$(document).on("click", ".share-key-btn", function () {
  $(this).val("Copied!");
});




new ClipboardJS('.copy-page-url');
$(document).on('click','.copy-page-url',function(){
  $('.copy-page-url').attr('uk-tooltip','Click to copy').show();
  $(this).attr('uk-tooltip','Copied').show();
});



function overviewChartSection(){
  var ctx = document.getElementById('myChart').getContext('2d');
  var gradient1 = ctx.createLinearGradient(0, 0, 0, 450);
    gradient1.addColorStop(0, 'rgba(250, 161,155, 1)'); //pink
    gradient1.addColorStop(0.3, 'rgba(253 ,198, 128, 1)'); //yellow
    gradient1.addColorStop(0.6, 'rgba(107 ,255 ,133, 1)');//green
    var myChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        datasets: [{
          label: '# of Votes',
          data: [],
          backgroundColor:[gradient1,'#eeeeee'],
          borderColor:[gradient1,'#eeeeee'],
          borderWidth: 1
        }]
      },
      options: {
        cutoutPercentage: 85,
        maintainAspectRatio: this.maintainAspectRatio,
        scales: {
          y: {
            beginAtZero: true
          }
        },
        tooltips: {
         enabled: false
       }
     }
   });
    var page_score = ($('.summary-chart-data').val()).toString();
    var left = (100 - page_score).toFixed(2);
    myChart.data.datasets[0].data = [page_score,left];
    myChart.update();
  }


  function siteAuditChartData(){
    console.log("Audit");
    var ctx = document.getElementById('siteAudit-chart-data').getContext('2d');
    var gradient1 = ctx.createLinearGradient(0, 0, 0, 450);
    gradient1.addColorStop(0, 'rgba(250, 161,155, 1)'); //pink
    gradient1.addColorStop(0.3, 'rgba(253 ,198, 128, 1)'); //yellow
    gradient1.addColorStop(0.6, 'rgba(107 ,255 ,133, 1)');//green
    var siteAuditmyChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        datasets: [{
          label: '# of Votes',
          data: [],
          backgroundColor:[gradient1,'#eeeeee'],
          borderColor:[gradient1,'#eeeeee'],
          borderWidth: 1
        }]
      },
      options: {
        cutoutPercentage: 85,
        maintainAspectRatio: this.maintainAspectRatio,
        scales: {
          y: {
            beginAtZero: true
          }
        },
        tooltips: {
         enabled: false
       }
     }
   });
    var page_score = ($('.siteAudit-chart-data').val()).toString();
    var left = (100 - page_score).toFixed(2);
    siteAuditmyChart.data.datasets[0].data = [page_score,left];
    siteAuditmyChart.update();
  }

  function dInsightsAuditChartData(){
    var ctx = document.getElementById('dinsights-audit-chart').getContext('2d');
    var gradient1 = ctx.createLinearGradient(0, 0, 0, 450);
    gradient1.addColorStop(0, 'rgba(250, 161,155, 1)'); //pink
    gradient1.addColorStop(0.3, 'rgba(253 ,198, 128, 1)'); //yellow
    gradient1.addColorStop(0.6, 'rgba(107 ,255 ,133, 1)');//green
    var desktopsiteAuditmyChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        datasets: [{
          label: '# of Votes',
          data: [],
          backgroundColor:[gradient1,'#eeeeee'],
          borderColor:[gradient1,'#eeeeee'],
          borderWidth: 1
        }]
      },
      options: {
        cutoutPercentage: 85,
        maintainAspectRatio: this.maintainAspectRatio,
        scales: {
          y: {
            beginAtZero: true
          }
        },
        tooltips: {
         enabled: false
       }
     }
   });
    var page_score = ($('.dinsightsAuditChart').val()).toString();
    var left = (100 - page_score).toFixed(2);
    desktopsiteAuditmyChart.data.datasets[0].data = [page_score,left];
    desktopsiteAuditmyChart.update();
  }


  function mInsightsAuditChartData(){
    var ctx = document.getElementById('minsights-audit-chart').getContext('2d');
    var gradient1 = ctx.createLinearGradient(0, 0, 0, 450);
    gradient1.addColorStop(0, 'rgba(250, 161,155, 1)'); //pink
    gradient1.addColorStop(0.3, 'rgba(253 ,198, 128, 1)'); //yellow
    gradient1.addColorStop(0.6, 'rgba(107 ,255 ,133, 1)');//green
    var mobilesiteAuditmyChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        datasets: [{
          label: '# of Votes',
          data: [],
          backgroundColor:[gradient1,'#eeeeee'],
          borderColor:[gradient1,'#eeeeee'],
          borderWidth: 1
        }]
      },
      options: {
        cutoutPercentage: 85,
        maintainAspectRatio: this.maintainAspectRatio,
        scales: {
          y: {
            beginAtZero: true
          }
        },
        tooltips: {
         enabled: false
       }
     }
   });
    var page_score = ($('.minsightsAuditChart').val()).toString();
    var left = (100 - page_score).toFixed(2);
    mobilesiteAuditmyChart.data.datasets[0].data = [page_score,left];
    mobilesiteAuditmyChart.update();
  }

  $('.right-sidebar ul li a').on('click', function(event) {
    event.preventDefault();
    $(this).parent().find('a').removeClass('active');
    $(this).addClass('active');
});

$(window).on('scroll', function() {
    $('div.target-detail').each(function() {
        if($(window).scrollTop() >= $(this).offset().top) {
            var id = $(this).attr('id');
            $('.right-sidebar ul li a').parent().removeClass('active');
            $('.right-sidebar ul li a[href=#'+ id +']').parent().addClass('active');
        }
    });
});

  /*$(document).on("click", ".right-sidebar ul li a", function() {
    $(this).parent().toggleClass("active").siblings().removeClass("active");
  });*/


  //   var lastId,
  //   topMenu = $(".right-sidebar"),
  //   topMenuHeight = topMenu.outerHeight(),
  //   menuItems = topMenu.find("a"),
  //   scrollItems = menuItems.map(function () {
  //     var item = $($(this).attr("href"));
  //     if (item.length) {
  //       return item;
  //     }
  //   });

  // menuItems.click(function (e) {
  //   var href = $(this).attr("href"),
  //     offsetTop = href === "#" ? 0 : $(href).offset().top - topMenuHeight / 2;
  //   $('html, body').stop().animate({
  //     scrollTop: offsetTop
  //   }, 300);
  //   e.preventDefault();
  // });

  // $(window).scroll(function () {
  //   var fromTop = $(this).scrollTop() + topMenuHeight;
  //   var cur = scrollItems.map(function () {
  //     if ($(this).offset().top < fromTop)
  //       return this;
  //   });
  //   cur = cur[cur.length - 1];
  //   var id = cur && cur.length ? cur[0].id : "";

  //   if (lastId !== id) {
  //     lastId = id;
  //     menuItems
  //       .parent().removeClass("active")
  //       .end().filter("[href='#" + id + "']").parent().addClass("active");
  //   }
  // });


  $(".accordion-all-criticals > li > .uk-accordion-content").slideUp();
  $(document).on('click','.accordion-all-criticals-title',function(){
    $(".accordion-all-criticals > li").toggleClass("uk-open");
    $(".accordion-all-criticals > li > .uk-accordion-content").slideToggle();
    if($('.accordion-all-criticals li').hasClass('uk-open')){
      $(this).text('Hide zero criticals');
    }else{
      $(this).text('Show zero criticals');
    }
  });

  $(".accordion-all-warnings > li > .uk-accordion-content").slideUp();
  $(document).on('click','.accordion-all-warnings-title',function(){
    $(".accordion-all-warnings > li").toggleClass("uk-open");
    $(".accordion-all-warnings > li > .uk-accordion-content").slideToggle();
    if($('.accordion-all-warnings li').hasClass('uk-open')){
      $(this).text('Hide zero warnings');
    }else{
      $(this).text('Show zero warnings');
    }
  });

  $(".accordion-all-notices > li > .uk-accordion-content").slideUp();
  $(document).on('click','.accordion-all-notices-title',function(){
    $(".accordion-all-notices > li").toggleClass("uk-open");
    $(".accordion-all-notices > li > .uk-accordion-content").slideToggle();
    if($('.accordion-all-notices li').hasClass('uk-open')){
      $(this).text('Hide zero notices');
    }else{
      $(this).text('Show zero notices');
    }
  });

  $(".tags-accordion > li > .uk-accordion-content").slideUp();
  $(document).on('click','.tags-accordion-action',function(){
    $(".tags-accordion > li").toggleClass("uk-open");
    $(".tags-accordion > li > .uk-accordion-content").slideToggle();
    if($('.tags-accordion li').hasClass('uk-open')){
      $(this).find('font').text('Show less');
    }else{
      $(this).find('font').text('Show all');
    }
  });


  $(document).on('change','#audit_crawl_pages',function(){
    $.ajax({
      type:'POST',
      url:BASE_URL+'/ajax_store_max_crawl',
      data:{value:$(this).val(),_token:$('meta[name="csrf-token"]').attr('content'),campaign_id:$('.campaign_id').val()},
      success:function(response){
        if(response == 1){
          refreshTask($('.campaign_id').val());  
        }
      }
    });
  });

  function siteAuditSummaryError(campaign_id){
   $(".audit-summery").load('/no_audit_content/' + campaign_id, function(responseTxt, statusTxt, xhr){
    if(statusTxt == "success")
      $('#site-audit-renew').css('display','none');
  });
 }


 $(function() {
  var editorSelector = '#code';
  var editor = createEditor({
    target: $(editorSelector).get(0),
    data: [],
    theme: 'paraiso-dark',
    readOnly: true,
    lineNumbers: true,
    width: 340,
    height: 460
  });

  new ClipboardJS('#copy-textarea-content', {
    text: function(trigger) {
      return getCodeMirrorJQuery(editorSelector + ' + .CodeMirror').getDoc().getValue();
    }
  });
});


// Retrieve a CodeMirror Instance via jQuery.
function getCodeMirrorJQuery(target) {
  var $target = target instanceof jQuery ? target : $(target);
  if ($target.length === 0) {
    throw new Error('Element does not reference a CodeMirror instance.');
  }

  if (!$target.hasClass('CodeMirror')) {
    if ($target.is('textarea')) {
      $target = $target.next('.CodeMirror');
    }
  }
  Command: toastr["success"]('Content copied!');
  return $target.get(0).CodeMirror;
};


function createEditor(options) {
  var editor = CodeMirror.fromTextArea(options.target, {
    mode: 'javascript',
    theme: options.theme || 'default',
    readOnly: options.readOnly,
    lineNumbers: options.lineNumbers
  });
  editor.setSize(options.width || '100%', options.height || options.target.style.height);
  editor.getDoc().setValue(JSON.stringify(options.data, null, 4));

  return editor;
}


$(document).on("click",".viewsource", function () {

  $('#offcanvas-pagecode .progress-loader').show();
  var url =  $(this).attr('data-url');
  var urltitle =  $(this).attr('data-title');
  var campaign_id = $('.campaign_id').val();
  $('.source-label h3 small').html(urltitle);
  $('.source-label h3 span').html(url);

  var x = document.getElementById('code');
  x.value = '';
  $('.CodeMirror').remove();
  var htmlEditor = CodeMirror.fromTextArea(document.getElementById("code"), {
    lineNumbers: true,
    lineWrapping: true,
    readOnly: true,
    mode: 'htmlmixed',
    theme: 'default',
  });

  $.ajax({
    type:"POST",
    url:BASE_URL+"/ajax-viewsourcehtml",
    data:{_token:$('meta[name="csrf-token"]').attr('content'),url:url,campaign_id:campaign_id},
    dataType:'json',
    success:function(result){
      var x = document.getElementById('code');
      x.value = result['html'];
      var normStr = result['html'];
      if(normStr !== undefined && normStr !== ''){
        var textArea = htmlEditor.getValue();
        htmlEditor.setValue(normStr);
      }
      $('#offcanvas-pagecode .progress-loader').hide();
    }
  });





  $(document).on('keyup','.search-in-content',function(event){
    event.preventDefault();
    $('.auditDrawer-search-clear').css('display','flex');
    $('#openSearch').css('display','none');
    var html = htmlEditor.getValue();
    htmlEditor.setValue(html);
    var markerClassName = 'highlightClass';
    var value = $('.search-in-content').val();
    var cursor = htmlEditor.getSearchCursor(value);
    var first, from, to;

    while (cursor.findNext()) {
      from = cursor.from();
      to = cursor.to();   
      htmlEditor.markText(from, to, {
        className: markerClassName
      });

      if (first === undefined) {
        first = from;
      }
    }

    if (first) {
      htmlEditor.scrollIntoView(first);
    }

  });


  function delay(callback, ms) {
    var timer = 0;
    return function() {
      var context = this, args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function () {
        callback.apply(context, args);
      }, ms || 0);
    };
  }


  $(document).on('click','.auditDrawer-search-clear',function(){
    $('.search-in-content').val('');
    $('.auditDrawer-search-clear').css('display','none');
    $('#openSearch').css('display','flex');
    var html = htmlEditor.getValue();
    htmlEditor.setValue(html);
  });

});



$(document).on("click", "#openSearch", function (e) {
  $(".searchBox").toggleClass("uk-open");
  if($('.searchBox').hasClass('uk-open')){
    $('.search-in-content').val('');
    $('.auditDrawer-search-clear').css('display','none');
    setTimeout (function(){
      $('.search-in-content').focus();
    }, 10);
  }
});


/*search functionality*/
$(document).on('keyup','.anchor-search',function(e){
  searchByKey($(this).val(),'#anchor-links');
});

$(document).on('keyup','.internal-search',function(e){
  searchByKey($(this).val(),'#internal-links');
});

$(document).on('keyup','.external-search',function(e){
  searchByKey($(this).val(),'#external-links');
}); 

function searchByKey(searchVal,tableID) {
  var table = $(tableID);
    table.find('tr').each(function(index, row) {
        var allDataPerRow = $(row);
        if (allDataPerRow.length > 0) {
            var found = false;
            allDataPerRow.each(function(index, td) {
                var regExp = new RegExp(searchVal, "i");

                if (regExp.test($(td).text())) {
                    found = true
                    return false;
                }
            });
            if (found === true) {
                $(row).show();
            } else {
                $(row).hide();
            }
        }
    });
}