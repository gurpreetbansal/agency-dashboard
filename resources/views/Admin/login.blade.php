@extends('layouts.admin_outer_pages')
@section('content')
<div class="login-page">
	<div class="elem-left" style="background-image: url('public/front/img/login-bg.jpg');">
		<div class="post-with-icon-cover">

			<div class="post-with-icon" data-aos="fade-up" data-aos-duration="1000">
				<figure>
					<img src="{{URL::asset('public/front/img/seo-icon.png')}}" alt="SEO">
				</figure>
				<h4>SEO</h4>
				<p>Showcase your rankings and search visibility</p>
			</div>

			<div class="post-with-icon" data-aos="fade-up" data-aos-duration="1000">
				<figure>
					<img src="{{URL::asset('public/front/img/ppc-icon.png')}}" alt="PPC">
				</figure>
				<h4>Pay Per Click</h4>
				<p>Track progress of your Google Ads campaign</p>
			</div>

			<div class="post-with-icon" data-aos="fade-up" data-aos-duration="1000">
				<figure>
					<img src="{{URL::asset('public/front/img/smm-icon.png')}}" alt="SMM">
				</figure>
				<h4>Social media
				marketing</h4>
				<p>Brand monitoring on fb, twitter, insta and more...</p>
			</div>

			<div class="post-with-icon" data-aos="fade-up" data-aos-duration="1000">
				<figure>
					<img src="{{URL::asset('public/front/img/my-business-icon.png')}}" alt="My business">
				</figure>
				<h4>My business</h4>
				<p>Find out more about your local SEO</p>
			</div>

		</div>

		<div class="text-center">
			<h2><strong>Manage All Your Dashboards</strong> Under One Roof</h2>
			<a href="{{\config('app.base_url')}}" class="btn btn-blue btn-xl">Know More</a>
		</div>

	</div>
	<div class="elem-right">
		<div class="logo">
			<img src="{{URL::asset('public/front/img/logo.png')}}" alt="Agency Dashboard Logo">
		</div>
		<div class="form-heading">
			<h3>All-In-One Reporting Platform for Agencies</h3>
			<h4>Welcome Admin! Please Login to your account to continue</h4>
		</div>
		<div class="login-form">
			<form  method="POST" action="{{ url('/do_login') }}">
				@csrf

				@if (session('error'))
				<div class="alert alert-danger" role="alert">
					{{ session('error') }}
				</div>
				@endif

				<div class="form-group">
					<label>Email</label>
					<input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your Email ID" value="{{ old('email')}}" name="email">
					@error('email')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>
				<div class="form-group">
					<label>Password</label>
					<input name="password" id="examplePassword" placeholder="Password here..." type="password" class="form-control @error('password') is-invalid @enderror">
					@error('password')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>
				<div class="d-flex justify-content-between form-group">
					<div class="checkbox-group">
						<label>
							<input id="exampleCheck" type="checkbox" class="form-check-input" name="remember"  {{ old('remember') ? 'checked' : '' }} value="1" checked>
							<span class="custom-checkbox"></span>
							Keep me logged in
						</label>
					</div>

					<a href="#">Recover Password</a>
				</div>
				<div class="text-left custm-btn-group form-group">
					<input type="submit" class="btn btn-blue btn-xl" value="Login">
				</div>
			</form>
		</div>
	</div>
</div>
@endsection