(function ($) {
  'use strict';

  var pricingTab = $("body").find(".pricing-tab"),
    pricingTabYearly = pricingTab.find("#yearly"),
    pricingTabMonthly = pricingTab.find("#monthly");

  var header = $("header");
  $(window).scroll(function () {
    var scroll = $(window).scrollTop();

    if (scroll >= 50) {
      header.addClass("fixed");
    } else {
      header.removeClass("fixed");
    }
  });

  $("body").find(".yearly-price").hide();

  pricingTab.find("button").on("click", function (event) {
    $(this).addClass("active");
    $(this).siblings().removeClass("active");
  })

  pricingTabYearly.on("click", function () {
    $("body").find(".yearly-price").show();
    $("body").find(".monthly-price").hide();
  })

  pricingTabMonthly.on("click", function () {
    $("body").find(".yearly-price").hide();
    $("body").find(".monthly-price").show();
  })

  AOS.init();

  var rellax = new Rellax('.rellax');

  $('.filters ul li').click(function () {
    $('.filters ul li').removeClass('active');
    $(this).addClass('active');

    var data = $(this).attr('data-filter');
    $grid.isotope({
      filter: data
    })
  });

  if ($('.filters-content .grid').length > 0) {

    var $grid = $(".grid").isotope({
      itemSelector: ".all",
      percentPosition: true,
      masonry: {
        columnWidth: ".all"
      }
    })
  }

  $('[data-toggle="tooltip"]').tooltip();

  // Cache selectors
  var lastId,
    topMenu = $("#termSidebar"),
    topMenuHeight = topMenu.outerHeight(),
    menuItems = topMenu.find("a"),
    scrollItems = menuItems.map(function () {
      var item = $($(this).attr("href"));
      if (item.length) {
        return item;
      }
    });

  menuItems.click(function (e) {
    var href = $(this).attr("href"),
      offsetTop = href === "#" ? 0 : $(href).offset().top - topMenuHeight / 2;
    $('html, body').stop().animate({
      scrollTop: offsetTop
    }, 300);
    e.preventDefault();
  });

  $(window).scroll(function () {
    var fromTop = $(this).scrollTop() + topMenuHeight;
    var cur = scrollItems.map(function () {
      if ($(this).offset().top < fromTop)
        return this;
    });
    cur = cur[cur.length - 1];
    var id = cur && cur.length ? cur[0].id : "";

    if (lastId !== id) {
      lastId = id;
      menuItems
        .parent().removeClass("active")
        .end().filter("[href='#" + id + "']").parent().addClass("active");
    }
  });

  //Avoid pinch zoom on iOS
  document.addEventListener('touchmove', function (event) {
    if (event.scale !== 1) {
      event.preventDefault();
    }
  }, false);
})(jQuery);

// $(document).on('click','#chat_with_us',function(e){
// e.preventDefault();
// window.$crisp=[];
// window.CRISP_WEBSITE_ID="6408702a-1c16-4e85-835f-3a67d2ed706f";
// (function(){d=document;s=d.createElement("script");
//   s.src="https://client.crisp.chat/l.js";
//   s.async=1;d.getElementsByTagName("head")[0].appendChild(s);
// })();
// });