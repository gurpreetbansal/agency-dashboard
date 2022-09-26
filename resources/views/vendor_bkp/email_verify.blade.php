@extends('layouts.vendor_internal_pages')
@section('content')
<input type="hidden" id="user_id" value="{{$user_id}}">
<div class="project-stats">
	<div uk-grid class="mb-40">
		<div class="uk-width-1-3@l uk-width-1-3@s">
			<div class="white-box small-chart-box style2">
				<div class="small-chart-box-head">
					<figure class="ajax-loader">
						<img src="{{URL::asset('public/vendor/internal-pages/images/total-keywords-icon.png')}}">
					</figure>
					<h6 class="ajax-loader"><big class="dashboard-keyword-detail">0<span>/0</span></big> Total Keywords<span
						uk-tooltip="title: Total number of keywords available in your package; pos: top-left"
						class="fa fa-info-circle"></span></h6>
				</div>
			</div>
		</div>
		<div class="uk-width-1-3@l uk-width-1-3@s">
			<div class="white-box small-chart-box style2">
				<div class="small-chart-box-head">
					<figure class="ajax-loader">
						<img src="{{URL::asset('public/vendor/internal-pages/images/total-projects-icon.png')}}">
					</figure>
					<h6 class="ajax-loader">
						<big class="dashboard-project-detail">0<span>/0</span></big> Total Projects<span
						uk-tooltip="title: Total number of projects available in your package; pos: top-left"
						class="fa fa-info-circle"></span>
					</h6>
				</div>
			</div>
		</div>
		<div class="uk-width-1-3@l uk-width-1-3@s">
			<div class="white-box small-chart-box style2">
				<div class="small-chart-box-head">
					<figure class="ajax-loader">
						<img src="{{URL::asset('public/vendor/internal-pages/images/freelancer-icon.png')}}">
					</figure>
					<h6 class="ajax-loader"><big class="dashboard-project-name">Subscription</big>Subscription<span
						uk-tooltip="title:You can upgrade/downgrade your subscription from billing section in settings.; pos: top-left"
						class="fa fa-info-circle"></span></h6>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="white-box pa-0 mb-40">
	<div class="white-box-body emailAlert">
		<h3><img src="{{URL::asset('public/vendor/internal-pages/images/alert-icon.png')}}"> ALERT</h3>
		<p>Please verify your email in order to continue with the dashboard. </p>
		<button class="btn blue-btn"  id="ResendEmail"> Resend Link</button>
	</div>
</div>
@endsection