@extends('layouts.vendor_internal_pages')
@section('content')
<div class="sideAudit-page">
	<input type="hidden" class="audit-type" value="individual-audit">
	<div class="white-box sa-audits">
		<div class="floating-elem">
			<span class="layer1"><img src="{{ url('/public/vendor/internal-pages/images/particle-layer1.png') }}"></span>
			<span class="layer2"><img src="{{ url('/public/vendor/internal-pages/images/particle-layer2.png') }}"></span>
			<span class="layer3"><img src="{{ url('/public/vendor/internal-pages/images/particle-layer3.png') }}"></span>
			<span class="layer4"><img src="{{ url('/public/vendor/internal-pages/images/particle-layer4.png') }}"></span>
			<span class="layer5"><img src="{{ url('/public/vendor/internal-pages/images/particle-layer5.png') }}"></span>
			<span class="layer6"><img src="{{ url('/public/vendor/internal-pages/images/particle-layer6.png') }}"></span>
			<span class="layer7"><img src="{{ url('/public/vendor/internal-pages/images/particle-layer7.png') }}"></span>
			<span class="layer8"><img src="{{ url('/public/vendor/internal-pages/images/particle-layer8.png') }}"></span>
			<span class="layer9"><img src="{{ url('/public/vendor/internal-pages/images/particle-layer9.png') }}"></span>
			<span class="layer10"><img src="{{ url('/public/vendor/internal-pages/images/particle-layer10.png') }}"></span>
		</div>
		<div class="inner">
			<h1>SEO Checker for Fast & Complete <br>Website Audit</h1>
			<p>Get a detailed SEO report with a personalized checklist on how to improve your <br>website and get to the top of Google.</p>
			<form action="javascript:void(0)" class="single-site-audit">
				<div class="form-group audit-form">
					<div class="fieldInput">
						<div class="domain-dropDownBox">
				            <input type="hidden" name="audit_url_type" value="https://" id="audit_url_type_input">
				            <button type="button"  class="audit_url_type" name="audit_url_type">https://</button>
				            <div class="domain-dropDownMenu"  id="audit-url-dropDownMenu">
					            <ul class="audit-url-type-ul">
				                    <li class="audit-url-type-list active"><h6>https://</h6>Secure website</li>
				                    <li class="audit-url-type-list"><h6>http://</h6>Non secure website</li>
				                </ul>
				            </div>
			            </div>
						<input type="text" class="form-control run-site-audit" placeholder="example.com" value="" name="domain-name">
						<span class="errorStyle" style="display: none;"><p id="domain_url_error">Not a Valid url.</p></span>
						<span uk-icon="check" class="audit-new-check green" style="display: none;"></span>
						<span uk-icon="close" class="audit-new-cross red" style="display: none;"></span>
					</div>
					<button type="button" class="btn blue-btn run-audit">Analyze Website</button>
				</div>
			</form>
		</div>
		
	</div>
	<div class="sa-audit-overview" style="display:none;" ></div>
	<div class="sa-audit-pages" style="display:none;" ></div>
	<div class="sa-audit-details" style="display:none;" ></div>
</div>
@endsection