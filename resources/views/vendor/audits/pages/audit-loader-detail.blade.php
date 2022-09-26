<div class="sAudit-section audit-page-details">
	<div class="inner">
		<div class="top-flex">
			<ul class="breadcrumb-list">
				@if(!isset($pageType) || $pageType !== 'shareKey')
				<li class="breadcrumb-item">
			    	<a href="javascript:;" data-page="home" class="individual-audit" >Home</a>
			    </li>
		    	<li class="breadcrumb-item">
			    	<a href="javascript:;" data-page="summary" class="individual-audit" >Site Audit</a>
			    </li>	
	    	    <li class="uk-active breadcrumb-item">Page Audit </li>
			    @else
			    <li class="breadcrumb-item">
			    	<a href="javascript:;" data-page="summary" class="individual-audit" >Home</a>
			    </li>	
	    	    <li class="uk-active breadcrumb-item">Page Audit </li>
			    @endif

			</ul>
			<div class="right-icons">
				<nav class="btn-group">
					
					<a target="_blank" href="{{ url('/download/pdf/'.$key.'/audit-detail') }}" class="btn icon-btn page-pdf-download color-red" data-enable="tooltip" title="" data-original-title="Print">
						<img src="{{URL::asset('public/vendor/internal-pages/images/pdf-icon.png')}}">
					</a>
					@if(!isset($pageType) || $pageType !== 'shareKey')
					<a href="javascript:;" id="page-refresh" class="btn individual-refresh icon-btn color-orange" data-type="detail-page" audit-id="" uk-tooltip="title: Refresh; pos: top-center">
						<img src="{{URL::asset('public/vendor/internal-pages/images/refresh-yellow-icon.png')}}">
					</a>
					<a href="javascript:;" id="ShareKey" data-id="" data-share-key="" class="btn icon-btn ShareKeyAudit color-purple" uk-tooltip="title: Generate Shared Key; pos: top-center" aria-expanded="false" >
						<img src="{{ URL::asset('/public/vendor/internal-pages/images/share-key-icon.png') }}">
					</a>
					@endif
					
				</nav>		
			</div>
		</div>
		<nav class="sAudit-nav detail-page-overview">
			@include('vendor.audits.loaders.pages-overview')
		</nav>
		<div id="Overview" class="white-box detail-page-summary overviewBox p-0">
			@include('vendor.audits.loaders.pages-summary')
		</div>
		<div class="detail-page-reports">
			@include('vendor.audits.loaders.pages-details')
		</div>
	</div>
</div>