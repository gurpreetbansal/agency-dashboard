@extends('layouts.vendor_internal_pages')
@section('content')

<div class="social-area white-box">
	<div class="tab-head">
		<input type="hidden" class="campaignID" value="{{$campaignId}}">
        <ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: #social_tabs; animation: uk-animation-slide-left-medium, uk-animation-slide-right-medium">
            <li class="uk-active"><a href="javascript:void(0)">Overview</a></li>
            <li><a href="javascript:void(0)">Facebook</a></li>
            <li><a href="javascript:void(0)">Twitter</a></li>
            <li><a href="javascript:void(0)">Instagram</a></li>
            <li><a href="javascript:void(0)">Linkedin</a></li>
            <li><a href="javascript:void(0)">Youtube</a></li>
            <li><a href="javascript:void(0)">Pinterest</a></li>
        </ul>
        <div class="right">
            @include('vendor.social.facebook-sections.facebook-filter')
            @include('vendor.social.facebook-sections.facebook-dateRange-popup')
        </div>
    </div>
    <div id="social_tabs" class="uk-switcher">
        <div class="uk-active">
        	<div class="overview-body">
                <div uk-grid class="uk-grid">
                    <div class="uk-width-1-4">
                    	<div class="single">
                    		
                    	</div>
                    </div>
                </div>
        	</div>
        </div>
        <div>
    		<div class="social-body">
    			@include('vendor.social.facebook-sections.facebook-likes')
    			@include('vendor.social.facebook-sections.facebook-engagement')
    			@include('vendor.social.facebook-sections.facebook-reach')
    			@include('vendor.social.facebook-sections.facebook-post-reviews')
    		</div>
    	</div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>

@endsection