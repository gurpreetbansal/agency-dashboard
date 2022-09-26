(function ($) {
  'use strict';
  $(window).on("load", function () {
    $('.ajax-loader').removeClass('ajax-loader');
  });


  $(document).ready(function () {

    $(".project-table-body").mCustomScrollbar({
      axis: "x",
      setLeft: "-100px",
      mouseWheel: {
        enable: false
      },
      contentTouchScroll: true,
      advanced: {
        releaseDraggableSelectors: "table tr td"
      }
    });

    setTimeout(function () {
      $(".project-table-body .mCSB_container").css("left", 0);
    }, 2000);

  });

  $(".file-group input[type=file]").change(function () {
    var names = [];
    for (var i = 0; i < $(this).get(0).files.length; ++i) {
      names.push($(this).get(0).files[i].name);
    }

    if ($(".file-group input[type=file]").val()) {
      $(".file-group .form-control").addClass("selected");
      $(".file-group .form-control span.fileName").html(names);
    } else {
      $(".file-group .form-control").removeClass("selected");
      $(".file-group .form-control span.fileName").html("Profile Image");
    }
  });

  setTimeout(function () {
    var PageHeight = $("main").innerHeight();
    $("main").css("min-height", PageHeight);
  }, 3000);

  $(document).ready(function () {
    var lastScrollTop = 200;
    $(window).scroll(function () {
      var st = $(this).scrollTop();
      if (st < lastScrollTop) {
        var totalHeight = 0;
        $("main").children().each(function () {
          totalHeight = totalHeight + $(this).outerHeight(true);
        });
        $("main").css("min-height", totalHeight);
      }
    })

  })

  $(".circle_percent").each(function () {
    var $this = $(this),
    $dataV = $this.data("percent"),
    $dataDeg = $dataV * 3.6,
    $round = $this.find(".round_per");
    $round.css("transform", "rotate(" + parseInt($dataDeg + 180) + "deg)");
    $this.append('<div class="circle_inbox"><span class="percent_text"></span>of 100</div>');
    $this.prop('Counter', 0).animate({
      Counter: $dataV
    }, {
      duration: 2000,
      easing: 'swing',
      step: function (now) {
        $this.find(".percent_text").text(Math.ceil(now) + "");
      }
    });
    if ($dataV >= 51) {
      $round.css("transform", "rotate(" + 360 + "deg)");
      setTimeout(function () {
        $this.addClass("percent_more");
      }, 1000);
      setTimeout(function () {
        $round.css("transform", "rotate(" + parseInt($dataDeg + 180) + "deg)");
      }, 1000);
    }
  });


  $(".audit-box-body tbody.table-collapseed, .audit-box-body tbody.table-audit-collapseed").hide();

  $(".show-more-issues").on("click", function () {
    $(".audit-box-body tbody.table-collapseed").slideToggle();
    $(".show-more-issues").toggleClass("open");

    var Text = $(this).find("span.t")

    if (Text.text() == "Show More") {
      Text.text("Show Less");
    } else {
      Text.text("Show More");
    }

  })

  $(".show-more-audit-issues").on("click", function () {
    $(".audit-box-body tbody.table-audit-collapseed").slideToggle();
    $(".show-more-audit-issues").toggleClass("open");

    var Text = $(this).find("span.t")

    if (Text.text() == "Show More") {
      Text.text("Show Less");
    } else {
      Text.text("Show More");
    }
  })

  $(".toggleMenuBtn").on("click", function () {
    $("aside.sidebar").toggleClass("close");
    $("body").toggleClass("fullWidth");
    $(this).toggleClass("active");
  })

  $(window).on("load resize", function (e) {
    checkScreenSize();
    checkMobileScreenSize();
  });

  checkScreenSize();
  checkMobileScreenSize();

  function checkScreenSize() {
    var newWindowWidth = $(window).width();
    if (newWindowWidth < 1199) {
      $('body').addClass('fullWidth');
      $('aside').addClass('close');

    } else {
      $('body').removeClass('fullWidth');
      $('aside').removeClass('close');
    }
  }

  function checkMobileScreenSize() {
    var newWindowWidth = $(window).width();
    if (newWindowWidth < 991) {
      $('body').find('.main-data').removeAttr("uk-sortable");

    } else {
      $('body').find('.main-data').attr("uk-sortable", "handle:.white-box-handle");
    }
  }


  $(document).on("click", "[data-pd-popup-open]", function (e) {
    var targeted_popup_class = $(this).attr("data-pd-popup-open");
    $('[data-pd-popup="' + targeted_popup_class + '"]').fadeIn(100);
    $("body").addClass("popup-open");
    e.preventDefault();
  });

  $(document).on("click", "[data-pd-popup-close]", function (e) {
    $("body").removeClass("popup-open");
    var targeted_popup_class = $(this).attr("data-pd-popup-close");
    $('[data-pd-popup="' + targeted_popup_class + '"]').fadeOut(10);
    e.preventDefault();
  });

  //Avoid pinch zoom on iOS
  document.addEventListener('touchmove', function (event) {
    if (event.scale !== 1) {
      event.preventDefault();
    }
  }, false);
})(jQuery);

$(".form-group").each(function () {
  var Label = $(this).find("label");
  if (Label.length >= 1) {
    Label.parent().addClass("hasLabel");
  }
});