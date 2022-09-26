@extends('layouts.vendor_internal_pages')
@section('content')
<div class="setting-container">
	<div class="loader h-300"></div>
	<div class="white-box pa-0 mb-40">
		<div class="white-box-body">
			<ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: .profileSettingNav">
				<li id="profile-account-li">
					<a href="#">
						<figure><img src="{{URL::asset('public/vendor/internal-pages/images/account-icon.png')}}"></figure>
						Profile
					</a>
				</li>
				<li>
					<a href="#">
						<figure><img src="{{URL::asset('public/vendor/internal-pages/images/lock-icon.png')}}"></figure>
						Change password
					</a>
				</li>
				@if(Auth::user()->role_id ==2)
				<li id="profile-plan-li">
					<a href="#">
						<figure><img src="{{URL::asset('public/vendor/internal-pages/images/package-icon.png')}}"></figure>
						Plan
					</a>
				</li>
				<li>
					<a href="#">
						<figure><img src="{{URL::asset('public/vendor/internal-pages/images/billing-icon.png')}}"></figure>
						Invoices
					</a>
				</li>
				<li>
					<a href="#">
						<figure><img src="{{URL::asset('public/vendor/internal-pages/images/billing-card-icon.png')}}"></figure>
						Card Details
					</a>
				</li>
				<li>
					<a href="#">
						<figure><img src="{{URL::asset('public/vendor/internal-pages/images/system-preference.png')}}"></figure>
						System Preferences
					</a>
				</li>
				@endif
			</ul>
			<div class="uk-switcher profileSettingNav">
				<!-- Account Tab -->
				<div id="account-profile-div">
					<div class="account-form-box"  id="account-form-id">
						<div class="account-form-box-head">
							<figure><img src="{{URL::asset('public/vendor/internal-pages/images/account-icon.png')}}"></figure>
							Profile
							<div class="profileSetting-progress-loader progress-loader"></div>
						</div>
						<div class="account-form-box-body">
							<form id="profileSettingForm" enctype="multipart/form-data">
								@csrf
								<input type="hidden" name="user_id" value="{{Auth::user()->id}}">
								<div class="field-group center">
									<div class="form-group file-group">
										<label>Profile Image</label>
										<label class="custom-file-label">
											<input type="file" name="profile_image" id="profile_image" accept="image/png,image/jpg,image/jpeg" class="profileAccount">
											<div class="custom-file form-control <?php if($user->profile_image != '' || $user->profile_image != null){ echo 'selected';}?>" id="custom-profile-file-div">
												<span uk-icon="icon:  upload"></span>
												<span uk-icon="icon:  pencil" class="edit"></span>
												<span id="fileName" class="fileName">Profile Image</span>
												<span>Choose a file or drag it here.</span>
												<div class="uploaded-file" >
													@if(isset($user->profile_image) && !empty($user->profile_image))
													<img id="profile_image_preview_container" src="{{ $user->profile_image }}" alt="profile-img" >
													@else
													<img id="profile_image_preview_container"  alt="profile-img" >
													@endif
												</div>
											</div>
										</label>
									</div>
									<div class="elem-right text-right">
										<?php if($user->profile_image <> '' || $user->profile_image <> null){?>
											<input type="button" class="btn btn-sm blue-btn" value="Remove" id="remove-profile-picture" data-id="{{$user->id}}"  >
										<?php }else{?>
											<input type="button" class="btn btn-sm blue-btn" value="Remove" id="remove-profile-picture" data-id="{{$user->id}}"  disabled>
										<?php } ?>
										<span class="errorStyle error"><p id="profile-logo-error"></p></span>
									</div>

								</div>

								<div class="form-row">
									<div class="form-group">
										<label>Name</label>
										<span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/account-icon.png')}}"></span>
										<input type="text" class="form-control profile_name profileAccount" placeholder="Name" value="{{@$user->name}}" name="name">
										<span class="errorStyle"><p id="profileErrorName"></p></span>	
									</div>
									<div class="form-group">
										<label>Email Address</label>
										<span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/mail-icon.png')}}"></span>
										<input type="text" class="form-control profileAccount" placeholder="Email" value="{{@$user->email}}" disabled>
									</div>
									@if(Auth::user()->role_id == 2)
									<div class="form-group">
										<label>Vanity URL</label>
										<span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/website-icon.png')}}"></span>
										<input type="text" class="form-control change_company_name profileAccount vanity-url-field" placeholder="Vanity Url" value="{{@$user->company_name}}" name="company_name">
										<span class="vanity-url-span">https://</span>
										<span class="vanity-url-span">.agencydashboard.io</span>
										<span class="errorStyle"><p id="ProfileErrorCompany"></p></span>	
									</div>
									@endif
									<div class="form-group">
										<label>Phone Number</label>
										<span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/phone-icon.png')}}"></span>
										<input type="number" class="form-control profile_phone profileAccount" placeholder="Phone" value="{{@$user->phone}}" name="phone" maxLength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
										<span class="errorStyle"><p id="ProfileErrorPhone"></p></span>	
									</div>
									<div class="form-group">
										<label>Address</label>
										<span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/location-icon.png')}}"></span>
										<input type="text" class="form-control profile_address_line_1 profileAccount" placeholder="Address Line 1" value="{{@$user->UserAddress->address_line_1}}" name="address_line_1">
										<span class="errorStyle"><p id="ProfileErrorAddress1"></p></span>
									</div>
									<div class="form-group">
										<label>Address Line 2</label>
										<span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/location-icon.png')}}"></span>
										<input type="text" class="form-control profileAccount" placeholder="Address Line 2" value="{{@$user->UserAddress->address_line_2}}" name="address_line_2">
									</div>
									<div class="form-group">
										<label>City</label>
										<span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/city-icon.png')}}"></span>
										<input type="text" class="form-control profile_city profileAccount" placeholder="City" value="{{@$user->UserAddress->city}}" name="city">
										<span class="errorStyle"><p id="ProfileErrorCity"></p></span>
									</div>
									<div class="form-group dropdown">
										<label>Country</label>
										<select name="country" class="selectpicker profileAccount" data-live-search="true">
											@if(isset($countries))
											@foreach($countries as $country)
											<option value="{{$country->id}}" {{$country->id==@$user->UserAddress->country?'selected':''}}>{{$country->countries_name}}</option>
											@endforeach
											@endif
										</select>
									</div>

									<div class="form-group">
										<label>Zip</label>
										<span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/zip-icon.png')}}"></span>
										<input type="text" class="form-control profile_zip profileAccount" placeholder="Zip" value="{{@$user->UserAddress->zip}}" name="zip">
										<span class="errorStyle"><p id="ProfileErrorZip"></p></span>
									</div>
								</div>
								<input type="hidden" class="ErrorCount" value="0">
								
								<div class="uk-text-right">
									<button type="submit" id="save_profile_settings" class="btn blue-btn">Update</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Account Tab End -->
				<!-- Change password Tab -->
				<div>
					<div class="account-form-box">
						<div class="account-form-box-head">
							<figure><img src="{{URL::asset('public/vendor/internal-pages/images/lock-icon.png')}}"></figure>
							Change password
						</div>
						<div class="account-form-box-body">

							<div id="AllErrors" style="display: none;"></div>
							<form id="form_change_password">
								@csrf
								<input type="hidden" name="user_id" value="{{Auth::user()->id}}">
								<div class="form-row">
									<div class="form-group">
										<label>Current Password</label>
										<span class="icon">
											<img src="{{URL::asset('public/vendor/internal-pages/images/lock-icon.png')}}">
										</span>
										<input type="password" class="form-control current_password profilePassword" placeholder="Current Password" name="current_password">
										<span class="errorStyle"><p id="ChangePasswordErrorCurrent"></p></span>	
										<img src="{{URL::asset('public/vendor/internal-pages/images/refresh-add-icon.gif')}}"  class="refresh-icon current-pwd-refresh" style="display: none;">
										<span uk-icon="check" class="current-pwd-check green input-validation-icon" style="display: none;"></span>
										<span uk-icon="close" class="current-pwd-cross red input-validation-icon" style="display: none;"></span>
									</div>
								</div>
								<div class="form-row">
									<div class="form-group">
										<label>New Password</label>
										<span class="icon">
											<img src="{{URL::asset('public/vendor/internal-pages/images/lock-icon.png')}}">
										</span>
										<input type="password" class="form-control new_password profilePassword" placeholder="New Password" name="new_password">
										<span class="errorStyle"><p id="ChangePasswordErrorNew"></p></span>	
										<img src="{{URL::asset('public/vendor/internal-pages/images/refresh-add-icon.gif')}}"  class="refresh-icon new-pwd-refresh" style="display: none;">
										<span uk-icon="check" class="new-pwd-check green input-validation-icon" style="display: none;"></span>
										<span uk-icon="close" class="new-pwd-cross red input-validation-icon" style="display: none;"></span>
									</div>
									<div class="form-group">
										<label>Confirm Password</label>
										<span class="icon">
											<img src="{{URL::asset('public/vendor/internal-pages/images/lock-icon.png')}}">
										</span>
										<input type="password" class="form-control confirm_password profilePassword" placeholder="Confirm Password" name="confirm_password">
										<span class="errorStyle"><p id="ChangePasswordErrorConfirm"></p></span>	
										<img src="{{URL::asset('public/vendor/internal-pages/images/refresh-add-icon.gif')}}"  class="refresh-icon confirm-pwd-refresh" style="display: none;">
										<span uk-icon="check" class="confirm-pwd-check green input-validation-icon" style="display: none;"></span>
										<span uk-icon="close" class="confirm-pwd-cross red input-validation-icon" style="display: none;"></span>
									</div>
								</div>
								<input type="hidden" class="ErrorCountPassword" value="0">
								<div class="uk-text-right">
									<button type="button" id="store_change_password" class="btn blue-btn">Save</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Change password Tab End -->

				@if(Auth::user()->role_id ==2)
				<!-- Package Tab -->
				<div id="plan_div">
					<div class="account-form-box" id="plan-section">
						<div class="account-form-box-head">
							<figure><img src="{{URL::asset('public/vendor/internal-pages/images/package-icon.png')}}"></figure>
							Plan

							@if($user->subscription_status == 1)
							@if(isset($package_info) && !empty($package_info) && date($package_info->trial_ends_at) < date('Y-m-d H:i:s') && $package_info->stripe_status=='active')
							(Active)
							@else
							(Free Trial)
							@endif
							@elseif($user->subscription_status == 0 && $user->subscription_ends_at <=  date('Y-m-d H:i:s'))
							(Expired)
							@endif
						</div>
						<div class="account-form-box-body pa-0">
							<div class="package-table">

								<table>
									<tr>
										<td>Your Plan</td>
										<td>
											@if(isset($user_package) && !empty($user_package))
											@if($user_package->subscription_type == 'year')
											{{$user_package->package->name .' ($'.($user_package->price/12).'/month)'}}
											@else
											{{$user_package->package->name .' ($'.$user_package->price.'/month)'}}
											@endif
											@endif
										</td>
									</tr>
									<tr>
										<td>Valid Till</td>
										<td>@if(isset($package_info) && !empty($package_info)){{date('M d, Y',strtotime($package_info->current_period_end))}} @else - @endif</td>
									</tr>
									<tr>
										@if($user->subscription_status == 0  && $user->subscription_ends_at <=  date('Y-m-d H:i:s'))
										<td>
											<p>Your plan has expired, upgrade to continue.</p>
										</td>
										@elseif($user->subscription_status == 0 && $user->subscription_ends_at >=  date('Y-m-d H:i:s'))
										<td>
											<p>Your plan has been cancelled, upgrade to continue.</p>
										</td>
										@endif

										<td @if($user->subscription_status==1) colspan="2" @endif>
											@if($user->subscription_status==1)
											<input type="button"  data-id="{{Auth::user()->id}}" class="btn blue-btn renewPlan" value="Upgrade" data-value="upgrade">
											<a href="javascript:;" class="cancel_subscription" data-id="{{Auth::user()->id}}"><input type="button" class="btn btn-border red-btn-border" value="Cancel"></a>
											@else
											<input type="button"  data-id="{{Auth::user()->id}}" class="btn blue-btn renewPlan" value="Renew" data-value="renew">
											@endif
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
				<!-- Package Tab End -->
				<!-- Billing Tab -->
				<div>
					<div class="account-form-box">
						<div class="account-form-box-head">
							<figure><img src="{{URL::asset('public/vendor/internal-pages/images/billing-icon.png')}}"></figure>
							Invoices
							<a href="{{url('/download-excel/'.$user_id)}}" target="_blank" class="btn btn-sm green-btn">
								<img src="{{URL::asset('public/vendor/internal-pages/images/excel-icon.png')}}"> Download
							</a>
						</div>
						<div class="account-form-box-body pa-0">
							<div class="billing-table project-table-cover">
								<div class="project-table-body">
									<table>
										<thead>
											<tr>
												<th>
													Date
												</th>
												<th>
													Invoice ID
												</th>
												<th>
													Price
												</th>
												<th>
													Status
												</th>
												<th>
													Action
												</th>
											</tr>
										</thead>
										<tbody>

											@if(isset($invoices) && !empty($invoices))
											@foreach($invoices as $invoice_data)
											@foreach($invoice_data->data as $invoice)
											<tr>
												<td>{{date('d M Y | H:i A',$invoice->created)}} </td>
												<td>{{$invoice->number}}</td>
												<td>$ {{number_format(($invoice->amount_paid/100),2)}}</td>
												<td>{{$invoice->status}}</td>
												<td>
													<a href="{{url('/download-invoice/'.$invoice->id)}}" target="_blank" class="btn btn-sm btn-border red-btn-border"><img src="{{URL::asset('public/vendor/internal-pages/images/pdf-icon.png')}}"> Download</a>
												</td>
											</tr>

											@endforeach
											@endforeach
											@else
											<tr><td colspan="5"><center>No Invoice Found</center></td></tr>
											@endif
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

				</div>
				<!-- Billing Tab End -->
				<!-- Card Tab -->
				<div>
					<div class="account-form-box">
						<div class="account-form-box-head">
							<figure><img src="{{URL::asset('public/vendor/internal-pages/images/billing-card-icon.png')}}"></figure>
							Update Card Details
						</div>
						<div class="account-form-box-body" id="billing-card-section">

							<form id="card-details-update">
								<input type="hidden" class="user_id" value="{{Auth::user()->id}}">
								<input type="hidden" class="stripe_key" value="{{\config('app.STRIPE_KEY')}}">
								<div class="form-group">
									<div id="card-element" class="form-control"></div>
									<span class="errorStyle"><p id="card-errors"></p></span>
								</div>
								<div class="uk-text-right">
									<button id="card-details-button" class="btn blue-btn">Update</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Card Tab -->

				<!-- System Prefernce tab -->
				<div>
					<div class="account-form-box">
						<div class="account-form-box-head">
							<figure><img src="{{URL::asset('public/vendor/internal-pages/images/system-preference.png')}}"></figure>
							System Preferences
						</div>
						<div class="account-form-box-body">
							<form id="form_system_preference">
								@csrf
								<input type="hidden" name="user_id" value="{{Auth::user()->id}}">
								<div class="form-row">
									<div class="form-group">
										<label>Mail Delivery</label>
										<label><input class="uk-radio" type="radio" checked name="email_delivery" value="{{\config('app.mail')}}"> Send emails from {{\config('app.mail')}}</label>
									</div>
								</div>
								<div class="form-row">
									<div class="form-group">
										<label>Recieve replies on</label>
										<input type="text" class="form-control email_reply_to" name="email_reply_to" value="<?php if(!empty($system_setting) && isset($system_setting->email_reply_to)){ echo $system_setting->email_reply_to; }else{ echo \config('app.mail');}?>" />
										<span class="errorStyle"><p id="ReplyToErrorName"></p></span>
									</div>
								</div>
								<div class="uk-text-right">
									<button type="submit" id="update_system_preference" class="btn blue-btn">Save</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- System Prefernce tab -->
				@endif
			</div>
		</div>
	</div>
</div>
@endsection