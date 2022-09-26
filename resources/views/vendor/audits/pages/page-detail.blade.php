<div id="SEO" class="white-box sAudit-detail p-0">
	<div class="white-box-head">
		<h5>{{ __('SEO') }}</h5>
	</div>
	<div class="white-box-body p-0">
		<ul class="uk-accordion" uk-accordion="multiple: true">
			@foreach($summaryAuditPages->categories['seo'] as $category)
	            @include('vendor.audits.partials.' . str_replace('_', '-', $category), ['name' => $category])
	        @endforeach
	    </ul>
	</div>
</div>
<div id="Performance" class="white-box sAudit-detail p-0">
	<div class="white-box-head">
		<h5>{{ __('Performance') }}</h5>
	</div>
	<div class="white-box-body p-0">
		<ul class="uk-accordion" uk-accordion="multiple: true">
			@foreach($summaryAuditPages->categories['performance'] as $category)
	            @include('vendor.audits.partials.' . str_replace('_', '-', $category), ['name' => $category])
	        @endforeach
	    </ul>
	</div>
</div>

<div id="Security" class="white-box sAudit-detail p-0">
	<div class="white-box-head">
		<h5>{{ __('Security') }}</h5>
	</div>
	<div class="white-box-body p-0">
		<ul class="uk-accordion" uk-accordion="multiple: true">
			@foreach($summaryAuditPages->categories['security'] as $category)
	            @include('vendor.audits.partials.' . str_replace('_', '-', $category), ['name' => $category])
	        @endforeach
	    </ul>
	</div>
</div>

<div id="Miscellaneous" class="white-box sAudit-detail p-0">
	<div class="white-box-head">
		<h5>{{ __('Miscellaneous') }}</h5>
	</div>
	<div class="white-box-body p-0">
		<ul class="uk-accordion" uk-accordion="multiple: true">
			@foreach($summaryAuditPages->categories['miscellaneous'] as $category)
	            @include('vendor.audits.partials.' . str_replace('_', '-', $category), ['name' => $category])
	        @endforeach
	    </ul>
	</div>
</div>