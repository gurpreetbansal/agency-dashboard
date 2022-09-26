//  $(document).on('click','#yearly', function(){
//   $('.renewal-plan').attr('data-state','year');
// });

//  $(document).on('click','#monthly', function(){
//   $('.renewal-plan').attr('data-state','month');
// });

$(document).on('click','#plan_button', function(e){
  $('.back-to-select-plan').trigger('click');
  $('.show-stripe-card,.message-box, .changePlan-progress-loader').css('display','none');
});

function getCookie(name) {
let matches = document.cookie.match(new RegExp(
  "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
));
return matches ? decodeURIComponent(matches[1]) : undefined;
}

function eraseCookie(name) {   
    document.cookie = name+'=; Max-Age=-99999999; domain=agencydashboard.io';  
}

$(document).ready(function() {
  if(getCookie('previous_url') == 'pricing'){
    eraseCookie('previous_url');
    var switcherEl = document.querySelector('#profile-section-list');
    var anchors = switcherEl.querySelectorAll('li > a');
    var switcher = UIkit.switcher(switcherEl);

    setTimeout(function() {
      switcher.show(2);   
      setTimeout(function(){
        $('#plan_button')[0].click();
      },10);
    },10);
  }
});

 $(window).on("ready load resize", function () {
  var pricingTabYearly = $(".plan-tabs").find("#yearly");
  var pricingTabMonthly = $(".plan-tabs").find("#monthly");

  pricingTabYearly.on("click", function () {
   $("body").find(".yearly-price,.yearly-price-button").show();
   $("body").find(".monthly-price,.monthly-price-button").hide();
 });

  pricingTabMonthly.on("click", function () {
   $("body").find(".yearly-price,.yearly-price-button").hide();
   $("body").find(".monthly-price,.monthly-price-button").show();
 }); 

  $(".plan-tabs button").on("click", function (event) {
   $(this).addClass("active").siblings().removeClass("active");
 });

  $(".renew-plan .custom-scroll").scroll(function () {
    var renewPlanScroll = $(this).scrollTop();
    if (renewPlanScroll >= 10) {
      $(this).find(".plan-head").addClass("scrolled");
    } else {
      $(this).find(".plan-head").removeClass("scrolled");
    }

    var boxes_offset = $(".select-package .pricing-boxes");
    if (boxes_offset.offset().top - $(window).scrollTop() <= 0) {
     $(".plan-head .right").fadeIn();
     $(".toggle-switch").fadeIn();
   } else {
     $(".plan-head .right").fadeOut();
     $(".toggle-switch").fadeOut();
   }
 });
});

$(document).on('click','.back-to-select-plan',function(){
  $('.show-stripe-card').css('display','none');
  $('.select-package').css('display','block');
  if($('.plan-head').hasClass('scrolled')){
    $('.plan-head .right').css('display','flex');
  }else{
    $('.plan-head .right').css('display','none');
  }
  $('#renew-plan .uk-modal-body').addClass('uk-modal-dialog-large');
  renew_card.clear();
});

$(document).on('click','.renewal-plan',function(){
var package_id = $(this).attr('data-id');
var package_type = $(this).attr('data-state');
var user_id = $('.user_id').val();
var purchase_mode = $('.purchase-mode').val();
var country_data = $('.country-data').val();

$('.selected_package').val(package_id);
$('.package_type').val(package_type);
$('.user_subscription_id').val($(this).attr('data-subscription-id'));
$('.plan_type').val($(this).attr('data-plan-type'));


if(country_data !== 99 && country_data !== '99'){
  $('.show-stripe-card').css('display','block');
  $('.select-package').css('display','none');
  $('#renew-plan .uk-modal-body').removeClass('uk-modal-dialog-large');
 }else{
    $('.select-package').css('display','block');
    $('.overlay-loader').css('display','block');
    renewSubscription();
    $('#renew-plan .uk-modal-body').removeClass('uk-modal-dialog-large');
}
});

 var renew_style = {
  base: {
    color: '#32325d',
    lineHeight: '18px',
    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
    fontSmoothing: 'antialiased',
    fontSize: '16px',
    '::placeholder': {
      color: '#aab7c4'
    }
  },
  invalid: {
    color: '#fa755a',
    iconColor: '#fa755a'
  }
};

var renew_stripe = Stripe($('.stripe_key').val(), {locale: 'en'});
var renew_elements = renew_stripe.elements();
var renew_card = renew_elements.create('card', {style: renew_style, hidePostalCode: true});
renew_card.mount('#renew-card-element');
renew_card.on('change', function (event) {
  var displayError = document.getElementById('card-errors');
  if (event.error) {
    displayError.textContent = event.error.message;
  } else {
    displayError.textContent = '';
  }
});

$(document).on('click','#renew-stripe-subscription',function(){
renew_stripe.createToken(renew_card).then(function (result) {
$('#renew-stripe-subscription').attr('disabled','disabled');
if (result.error) {
  $('#renew-stripe-subscription').removeAttr('disabled');
  var errorElement = document.getElementById('renew-card-errors');
  errorElement.textContent = result.error.message;
  $('#renew-card-element').addClass('error');
  document.getElementById('renew-card-errors').innerHTML = result.error.message;
} else {
  renewStripeTokenHandler(result.token);
  $('#renew-card-element').removeClass('error');
  document.getElementById('renew-card-errors').innerHTML = '';
}
});
});

function renewStripeTokenHandler(token) {
  var form = document.getElementById('renew-payment-form');
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'renewStripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);
  $('.overlay-loader').css('display','block');
  renewSubscription();
}

function renewSubscription(){
$.ajax({
type:'POST',
url:BASE_URL+'/ajax_renew_stripe_subsciption',
dataType:'json',
data: {stripeToken:$("input[name=renewStripeToken]").val(),_token:$('meta[name="csrf-token"]').attr('content'),user_id:$('input[name=user_id]').val(),package_id:$('.selected_package').val(),package_type:$('.package_type').val(),previous_subscription_id:$('.user_subscription_id').val(),plan_type:$('.plan_type').val()},  
success:function(response){
  $('.overlay-loader,.select-package,.show-stripe-card').css('display','none');


  if(response['status'] == 'success'){
    if(response['subscription_type'] == 'manual'){
      $('#display-renew-message-manual').css('display','block');
      $('.changePlan-progress-loader').css('display','block');

      document.getElementById('add-div-class').removeAttribute('class');
      $('#add-div-class').addClass('single');
      $('#add-div-class').addClass(response['package_name'].toLowerCase());
      $('#add-div-class').addClass(response['package_name'].toLowerCase());
      $('.package-name').html(response['package_name']);
      $('.display-usd-price').html('$'+response['package_usd']);
      $('.display-inr-price').html('₹'+response['package_inr']+'/mo');
      $('.campaign-list').html(response['projects']);
      $('.keywords-list').html(response['keywords']);
      document.getElementById("pay-now-link").href = response['hosted_invoice_url'];
      
      setInterval(function(){
        checkInvoiceStatus(response['invoice_id']);
      },5000);
    }else{
      $('#display-renew-message').css('display','block');
      $('#display-renew-message').append('<h3 class="success"><span uk-icon="check"></span><br>'+response['message']+'</h3>');
      setTimeout(function(){
        location.reload();
      },3000);
    }
  }

  if(response['status'] == 'error'){
    $('#display-renew-message').append('<h3 class="error"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i><br>'+response['message']+'</h3>');
  }       
}
});
}

function checkInvoiceStatus(invoice_id){
$.ajax({
  type:'GET',
  url:BASE_URL+'/ajax_check_invoice_status',
  dataType:'json',
  data:{invoice_id},
  success:function(response){
    if(response['status'] == 'paid'){
      $('.changePlan-progress-loader').css('display','none');
      location.replace(BASE_URL+"/dashboard"); /*redirect to dashboard*/
    }


    if(response['status'] == 'void'){
      $('.changePlan-progress-loader').css('display','none');
      location.reload();
    }

  }
});
}


$(document).on('click','.pricing-downgrade',function(e){
e.preventDefault();
$.ajax({
  url:BASE_URL+'/ajax_check_pricing_downgrade',
  type:'GET',
  dataType:'json',
  data:{id:$(this).attr('data-id'),state:$(this).attr('data-state'),user_id:$('input[name="user_id"]').val()},
  success:function(response){
    if(response['status'] == 0){
      $('.page').val('profile');
      $('#downgrade-popup').trigger('click');
      $('#downgrade-popup').css('display', 'block');
      $('body').addClass('popup-open');
    }

    if(response['status'] == 1){
      window.location.href = 'subscription?id='+response['string'];
    }
  }
});
});

$(document).on('click','#continue_downgrade',function(e){
e.preventDefault();
$.ajax({
  type:'GET',
  url:BASE_URL+'/ajax_continue_downgrade',
  success:function(response){
    if(response['status'] == 1){
      window.location.href = response['message'];
    }

    if(response['status'] == 0){
      Command: toastr["error"]('Error!!');
      return false;
    }
  }
});
});

$(document).on('click','#cancel_downgrade',function(){
$('#downgrade-popup').trigger('click');
$('#downgrade-popup').css('display', 'none');
$('body').removeClass('popup-open');
});

$(document).on('change','#switch',function(e){
  e.preventDefault();  
  $('.inr-price').toggle();
  $('.common-price').toggle(); 
});
