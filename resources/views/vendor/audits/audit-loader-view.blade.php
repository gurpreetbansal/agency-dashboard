<div class="sAudit-section">
	<div class="inner">
		<div class="top-flex">
			<ul class="breadcrumb-list">
				@if(!isset($pageType) || $pageType !== 'shareKey')
				<li class="breadcrumb-item">
					<a href="javascript:;" data-page="home" class="individual-audit" >Home</a>
				</li>
			    <li class="uk-active breadcrumb-item">Site Audit</li>
			    @else
			   <!--  <li class="breadcrumb-item">
					<a href="javascript:;" data-page="summary" class="individual-audit" >Home</a>
				</li> -->
			    @endif
			</ul>
			<div class="right-icons">
				<nav class="btn-group">
					@if(!isset($pageType) || $pageType !== 'shareKey')
					<span class="audit-disable" style="cursor: not-allowed;">
						<a href="javascript:;" class="btn site-audit-refresh icon-btn color-orange" data-status="progress" data-type="detail-page" audit-id="" uk-tooltip="title: Refresh; pos: top-center" style="pointer-events: none;" >
							<img src="{{URL::asset('public/vendor/internal-pages/images/refresh-yellow-icon.png')}}">
						</a>
					</span>
					<a href="javascript:;" id="ShareKey" data-id="" data-share-key="" class="btn icon-btn ShareKeyAudit color-purple" uk-tooltip="title: Generate Shared Key; pos: top-center" aria-expanded="false" >
						<img src="{{ URL::asset('/public/vendor/internal-pages/images/share-key-icon.png') }}">
					</a>
					@endif
				</nav>
			</div>
		</div>

		<div class="white-box overviewBox p-0">
			@include('vendor.audits.loaders.audit-summary')
		</div>
		
		<div class="white-box pagesBox p-0">
			@include('vendor.audits.loaders.audit-lists')
		</div>
	</div>
</div>