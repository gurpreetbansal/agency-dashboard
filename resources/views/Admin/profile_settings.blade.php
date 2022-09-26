@extends('layouts.admin_internal_pages')
@section('content')
<style>
.errorStyle {
    display: none;
}
</style>
<div class="setting-container">
	<!-- <div class="loader h-300"></div> -->
	<div class="white-box pa-0 mb-40">
		<div class="white-box-body">
			<ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: .profileSettingNav">
				<li id="profile-account-li">
					<a href="#">
						<figure><img src="{{URL::asset('public/vendor/internal-pages/images/account-icon.png')}}"></figure>
						Account
					</a>
				</li>
				<li>
					<a href="#">
						<figure><img src="{{URL::asset('public/vendor/internal-pages/images/lock-icon.png')}}"></figure>
						Change password
					</a>
				</li>
			
			</ul>
			<div class="uk-switcher profileSettingNav">
				<!-- Account Tab -->
				<div id="admin-account-profile-div">
					<div class="account-form-box"  id="account-form-id">
						<div class="account-form-box-head">
							<figure><img src="{{URL::asset('public/vendor/internal-pages/images/account-icon.png')}}"></figure>
							Account
							<div class="admin-profileSetting-progress-loader progress-loader"></div>
						</div>
						<div class="account-form-box-body">
							<form id="adminProfileSettingForm" enctype="multipart/form-data">
								@csrf
								<input type="hidden" name="user_id" value="{{$user_id}}">
								<div class="field-group center" id="admin-profile-image-section">
									<div class="form-group file-group">
										<label>Profile Image</label>
										<label class="custom-file-label">
											<input type="file" name="profile_image" id="admin_profile_image" accept="image/png,image/jpg,image/jpeg" class="profileAccount">
											<div class="custom-file form-control <?php if($user->profile_image != '' || $user->profile_image != null){ echo 'selected';}?>" id="admin-custom-profile-file-div">
												<span uk-icon="icon:  upload"></span>
												<span uk-icon="icon:  pencil" class="edit"></span>
												<span id="fileName" class="fileName">Profile Image</span>
												<span>Choose a file or drag it here.</span>
												<div class="uploaded-file" >
													@if(isset($user->profile_image) && !empty($user->profile_image))
													<img id="admin_profile_image_preview_container" src="{{ $user->profile_image }}" alt="profile-img" >
													@else
													<img id="admin_profile_image_preview_container"  alt="profile-img" >
													@endif
												</div>
											</div>
										</label>
									</div>
									<div class="elem-right text-right">
										<input type="button" class="btn btn-sm blue-btn" value="Remove" id="admin-remove-profile-picture" data-id="{{$user_id}}"  @if($user->profile_image == '' || $user->profile_image == null) disabled @endif>
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
										<select name="country" class="selectpicker profileAccount" data-live-search="true" title="Select">
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
									<button type="submit" id="save_admin_profile_settings" class="btn blue-btn">Update</button>
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
							<form id="admin_form_change_password">
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
									</div>
									<div class="form-group">
										<label>Confirm Password</label>
										<span class="icon">
											<img src="{{URL::asset('public/vendor/internal-pages/images/lock-icon.png')}}">
										</span>
										<input type="password" class="form-control confirm_password profilePassword" placeholder="Confirm Password" name="confirm_password">
										<span class="errorStyle"><p id="ChangePasswordErrorConfirm"></p></span>
									</div>
								</div>
								<input type="hidden" class="ErrorCountPassword" value="0">
								<div class="uk-text-right">
									<button type="button" id="admin_store_change_password" class="btn blue-btn">Save</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- Change password Tab End -->
			</div>
		</div>
	</div>
</div>
@endsection