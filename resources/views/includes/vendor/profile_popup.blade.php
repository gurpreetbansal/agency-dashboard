<div id="cancel-feedback" class="uk-flex-top" uk-modal>
	<div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical px-0">
		<div class="custom-scroll">
			<button class="uk-modal-close-default" type="button" uk-close id="close-feedback-button"></button>
			<h3>Give feedback</h3>
			<p>What do  you think about Agencydashboard?</p>
			<form id="feedbackForm">	
				@csrf
				<div class="form-group face-label">
					<label uk-tooltip="Very Poor">
						<input type="radio" name="overall_rating" value="1" class="overall_rating">
						<span></span>
						<img src="{{URL::asset('public/vendor/internal-pages/images/face1.svg')}}" alt="face1">
					</label>
					<label uk-tooltip="Poor">
						<input type="radio" name="overall_rating" value="2" class="overall_rating">
						<span></span>
						<img src="{{URL::asset('public/vendor/internal-pages/images/face2.svg')}}" alt="face2">
					</label>
					<label uk-tooltip="Average">
						<input type="radio" name="overall_rating" value="3" class="overall_rating">
						<span></span>
						<img src="{{URL::asset('public/vendor/internal-pages/images/face3.svg')}}" alt="face3">
					</label>
					<label uk-tooltip="Good">
						<input type="radio" name="overall_rating" value="4" class="overall_rating">
						<span></span>
						<img src="{{URL::asset('public/vendor/internal-pages/images/face4.svg')}}" alt="face4">
					</label>
					<label uk-tooltip="Excellent">
						<input type="radio" name="overall_rating" value="5" class="overall_rating">
						<span></span>
						<img src="{{URL::asset('public/vendor/internal-pages/images/face5.svg')}}" alt="face5">
					</label>
				</div>

				<div class="form-group" id="cancel_type">
					<label class="form-label">Please share why you are canceling your subscription?</label>
					<textarea class="form-control description" name="canceling-info" placeholder="Write here"></textarea>
				</div>

				<div class="form-group">
					<label class="form-label">Would you recommend Agencydashboard to your peers</label>					
					<div class="uk-flex">
						<label><input type="radio" name="recommend" value="Yes" class="recommend"> Yes</label>
						<label><input type="radio" name="recommend" value="No" class="recommend"> No</label>
					</div>
				</div>

				<div class="text-left btn-group start">           
					<button class="btn blue-btn" type="button" id="submit-feedback-button">Submit</button>
					<input type="button" class="btn btn-border red-btn-border" value="Cancel" id="cancel-feedback-button">
				</div>
			</form>
		</div>
	</div>
</div>

<div id="renew-plan" class="renew-plan uk-flex-top" uk-modal>
	<div class="uk-modal-dialog uk-modal-dialog-large uk-modal-body uk-margin-auto-vertical px-0 ">\
		<div class="changePlan-progress-loader popup-progress-loader"></div>
		<div class="custom-scroll">
			<button class="uk-modal-close-default" type="button" uk-close id="close-feedback-button"></button>
			<form id="renew-payment-form">
				<input type="hidden" class="selected_package">
				<input type="hidden" class="package_type">
				<input type="hidden" class="user_subscription_id">
				<input type="hidden" class="plan_type">
				<input type="hidden" class="country-data" value="{{($user->UserAddress->country)? $user->UserAddress->country: ''}}">

				<div class="select-package">
					<div class="plan-head">
						<div class="left">
							<h3>
								Select Plan

								@if($country_id == 99)
								<a href="javascript:void(0)" class="toggle-switch" style="display: none;">
									<label for="switch">Switch to INR</label>
									<label class="sw">
										<input id="switch" name="switch" type="checkbox">
										<div class="sw-pan"></div>
										<div class="sw-btn"></div>
									</label>
								</a>
								@endif
							</h3>
							<div class="plan-tabs">
								<button type="button" id="monthly" class="active">Monthly</button>
								<button type="button" id="yearly">Yearly</button>
								<span class="pointer"></span>
							</div>
						</div>
						<div class="right" style="display: none;">
							@if(isset($packages) && !empty($packages))
							@foreach($packages as $key=>$value)
							<div class="single {{strtolower(str_replace(' ', '', $value->name))}}">
								<h3>{{$value->name}}</h3>
								<div class="common-price">
									<h6 class="monthly-price" style="display: block;"><big><sup>$</sup>{{$value->monthly_amount}}</big>/mo</h6>
									<h6 class="yearly-price" style="display: none;"><big><sup>$</sup>{{$value->yearly_amount}}</big>/mo</h6>
								</div>

								@if($country_id == 99)
								<div class="inr-price" style="display: none;">
									<h6 class="monthly-price" style="display: block;"><big><sup>₹</sup>{{number_format($value->inr_monthly_amount)}}</big>/mo</h6>
									<h6 class="yearly-price" style="display: none;"><big><sup>₹</sup>{{(number_format($value->inr_yearly_amount/12))}}</big>/mo</h6>
								</div>
								@endif

								<!-- <button type="button" class="btn pricing-btn renewal-plan" data-state="month" data-id="{{$value->id}}">Select Plan</button> -->
								<div class="monthly-price-button"  style="display: block;"> 
									@if(isset($user_package->package_id) &&  $user->user_type !== 1)
									@if($value->id < @$user_package->package_id)
									<button type="button" class="btn pricing-btn renewal-plan" data-state="month" data-id="{{$value->id}}" data-subscription-id="{{@$package_info->id}}">Downgrade</button>
									@elseif($value->id == @$user_package->package_id)
									@if($user->subscription_status == 1 && ($user_package->subscription_type == 'month' || $user_package->subscription_type == '1 month'))
									<button type="button" class="btn pricing-btn" disabled>Current Plan</button>
									@else
									<button type="button" class="btn pricing-btn renewal-plan" data-state="month" data-id="{{$value->id}}" data-subscription-id="{{@$package_info->id}}">Select Plan</button>
									@endif
									@else
									<button type="button" class="btn pricing-btn renewal-plan" data-state="month" data-id="{{$value->id}}" data-subscription-id="{{@$package_info->id}}">Upgrade</button>
									@endif
									@elseif(isset($user_package->package_id) &&  $user->user_type == 1)
									<button type="button" class="btn pricing-btn renewal-plan" data-state="month" data-id="{{$value->id}}" data-subscription-id="{{@$package_info->id}}">Upgrade</button>
									@else
									<button type="button" class="btn pricing-btn renewal-plan" data-state="month" data-id="{{$value->id}}" data-subscription-id="{{@$package_info->id}}">Select Plan</button>
									@endif
								</div>

								<div class="yearly-price-button"  style="display: none;"> 
									@if(isset($user_package->package_id) &&  $user->user_type !== 1)
									@if($value->id < @$user_package->package_id)
									<button type="button" class="btn pricing-btn renewal-plan" data-state="year" data-id="{{$value->id}}" data-subscription-id="{{@$package_info->id}}">Downgrade</button>
									@elseif($value->id == @$user_package->package_id)
									@if($user->subscription_status == 1 && ($user_package->subscription_type == 'year' ||  $user_package->subscription_type == '1 year'))
									<button type="button" class="btn pricing-btn" disabled>Current Plan</button>
									@else
									<button type="button" class="btn pricing-btn renewal-plan" data-state="year" data-id="{{$value->id}}" data-subscription-id="{{@$package_info->id}}">Select Plan</button>
									@endif
									@else
									<button type="button" class="btn pricing-btn renewal-plan" data-state="year" data-id="{{$value->id}}" data-subscription-id="{{@$package_info->id}}">Upgrade</button>
									@endif
									@elseif(isset($user_package->package_id) &&  $user->user_type == 1)
									<button type="button" class="btn pricing-btn renewal-plan" data-state="year" data-id="{{$value->id}}" data-subscription-id="{{@$package_info->id}}">Upgrade</button>
									@else
									<button type="button" class="btn pricing-btn renewal-plan" data-state="year" data-id="{{$value->id}}" data-subscription-id="{{@$package_info->id}}">Select Plan</button>
									@endif
								</div>								
							</div>
							@endforeach
							@endif
						</div>
					</div>
					<div class="pricing-boxes">
						@if(isset($packages) && !empty($packages))
						@foreach($packages as $key=>$value)
						<div class="single {{strtolower(str_replace(' ', '', $value->name))}}">
							<div class="pricing-box-head">
								<h3>{{$value->name}}</h3>
								<h6 class="monthly-price" style="display: block;"><big><sup>$</sup>{{number_format($value->monthly_amount)}}</big>/mo @if($country_id == 99)<small>₹{{number_format($value->inr_monthly_amount)}}/mo</small>@endif</h6>
								<h6 class="yearly-price" style="display: none;"><big><sup>$</sup>{{number_format($value->yearly_amount)}}</big>/mo  @if($country_id == 99)<small>₹{{number_format($value->inr_yearly_amount/12)}}/mo</small>@endif</h6>
							</div>
							<div class="pricing-box-main">
								<ul>
									<li data-toggle="tooltip" data-placement="left" title="" data-original-title="Each campaign represents one of your clients">
										<strong>{{$value->number_of_projects}}</strong> Campaigns
									</li>
									<li data-toggle="tooltip" data-placement="left" title="" data-original-title="One keyword counts towards all of the ranking sources your account has enabled">
										<strong>{{$value->number_of_keywords}}</strong> Keyword Rankings
									</li>
								</ul>
								<div class="monthly-price-button"  style="display: block;"> 
									@if(isset($user_package->package_id) &&  $user->user_type !== 1)
									@if($value->id < @$user_package->package_id)
									<button type="button" class="btn pricing-btn renewal-plan" data-state="month" data-id="{{$value->id}}" data-subscription-id="{{@$package_info->id}}" data-plan-type="downgrade">Downgrade</button>
									@elseif($value->id == @$user_package->package_id)
									@if($user->subscription_status == 1 && ($user_package->subscription_type == 'month' || $user_package->subscription_type == '1 month'))
									<button type="button" class="btn pricing-btn" disabled>Current Plan</button>
									@else
									<button type="button" class="btn pricing-btn renewal-plan" data-state="month" data-id="{{$value->id}}" data-subscription-id="{{@$package_info->id}}" data-plan-type="select_plan">Select Plan</button>
									@endif
									@else
									<button type="button" class="btn pricing-btn renewal-plan" data-state="month" data-id="{{$value->id}}" data-subscription-id="{@$package_info->id}}" data-plan-type="upgrade">Upgrade</button>
									@endif
									@elseif(isset($user_package->package_id) &&  $user->user_type == 1)
									<button type="button" class="btn pricing-btn renewal-plan" data-state="month" data-id="{{$value->id}}" data-subscription-id="{{@$package_info->id}}" data-plan-type="upgrade">Upgrade</button>
									@else
									<button type="button" class="btn pricing-btn renewal-plan" data-state="month" data-id="{{$value->id}}" data-subscription-id="{{@$package_info->id}}" data-plan-type="select_plan">Select Plan</button>
									@endif
								</div>

								<div class="yearly-price-button"  style="display: none;"> 
									@if(isset($user_package->package_id) &&  $user->user_type !== 1)
									@if($value->id < @$user_package->package_id)
									<button type="button" class="btn pricing-btn renewal-plan" data-state="year" data-id="{{$value->id}}" data-subscription-id="{{@$package_info->id}}" data-plan-type="downgrade">Downgrade</button>
									@elseif($value->id == @$user_package->package_id)
									@if($user->subscription_status == 1 && ($user_package->subscription_type == 'year' || $user_package->subscription_type == '1 year'))
									<button type="button" class="btn pricing-btn" disabled>Current Plan</button>
									@else
									<button type="button" class="btn pricing-btn renewal-plan" data-state="year" data-id="{{$value->id}}" data-subscription-id="{{@$package_info->id}}" data-plan-type="select_plan">Select Plan</button>
									@endif
									@else
									<button type="button" class="btn pricing-btn renewal-plan" data-state="year" data-id="{{$value->id}}" data-subscription-id="{{@$package_info->id}}" data-plan-type="upgrade">Upgrade</button>
									@endif
									@elseif(isset($user_package->package_id) &&  $user->user_type == 1)
									<button type="button" class="btn pricing-btn renewal-plan" data-state="year" data-id="{{$value->id}}" data-subscription-id="{{@$package_info->id}}" data-plan-type="upgrade">Upgrade</button>
									@else
									<button type="button" class="btn pricing-btn renewal-plan" data-state="year" data-id="{{$value->id}}" data-subscription-id="{{@$package_info->id}}" data-plan-type="select_plan">Select Plan</button>
									@endif
								</div>
								<!-- <button type="button" class="btn pricing-btn renewal-plan" data-state="month" data-id="{{$value->id}}">Select Plan</button> -->
							</div>
							@if(count($value->package_feature) > 0)
							<div class="pricing-box-features-list">
								<h4>Features Included</h4>
								<ul>
									@foreach($value->package_feature as $feature)
									<li data-toggle="tooltip" data-placement="left" title="<?php if($feature->tooltip_title == null){ echo $feature->feature;}else{ echo $feature->tooltip_title;}?>">{{$feature->feature}}</li>
									@endforeach
								</ul>
							</div>
							@endif
						</div>
						@endforeach
						@endif
					</div>
				</div>

				<div class="show-stripe-card" style="display:none;">
					<h3 class="back-to-select-plan"><a href="javascript:void(0);"><span uk-icon="arrow-left"></span></a> Card Details</h3>
					<div class="form-group">
						<div id="renew-card-element" class="form-control"></div>
						<span class="payment-errors" id="renew-card-errors" style="color: red;margin-top:10px;"></span>
						<div class="display-error-message"></div>
					</div>
					<button type="button" class="btn blue-btn btn-xl" id="renew-stripe-subscription">Pay</button>
				</div>

				<div class="message-box" style="display:none;" id="display-renew-message"></div>

				<div class="message-box" style="display:none;" id="display-renew-message-manual">
					<h3 class="success">
						<span uk-icon="check" class="uk-icon"></span>
					</h3>
					<div class="payment-review">
						<div class="single" id="add-div-class">
							<div class="pricing-box-head">
								<h3 class="package-name"></h3>
								<h6><big class="display-usd-price">0</big>/mo <small class="display-inr-price">/mo</small></h6>
							</div>
							<div class="pricing-box-main">
								<ul>
									<li data-toggle="tooltip" data-placement="left" title="" data-original-title="Each campaign represents one of your clients">
										<strong class="campaign-list">0</strong> Campaigns
									</li>
									<li data-toggle="tooltip" data-placement="left" title="" data-original-title="One keyword counts towards all of the ranking sources your account has enabled">
										<strong class="keywords-list">0</strong> Keyword Rankings
									</li>
								</ul>
								<div class="price-button"> 
									<a href="javascript:;" id="pay-now-link" target="_blank">
										<button type="button" class="btn pricing-btn">Pay Now</button>
									</a>
								</div>
							</div>
							<p>Once your order is confirmed, your subscription will be automatically upgraded.</p>
						</div>
					</div>
				</div>

				<div class="overlay-loader" style="display:none;">
					<div uk-spinner></div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="popup" data-pd-popup="downgrade-popup" id="downgrade-popup" style="display: none;">
    <div class="popup-inner">
        <h5>Delete Projects/Keywords to downgrade your subscription.</h5>
        <a class="popup-close" data-pd-popup-close="downgrade-popup" href="javascript:;" id="downgrade_popup_close"></a>
        <input type="button" class="btn blue-btn mr-3" value="Continue" id="continue_downgrade">
        <input type="button" class="btn btn-border red-btn-border" value="Cancel" id="cancel_downgrade">
    </div>
</div>