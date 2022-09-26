@if(isset($summaryAuditPages['results'][$name]))
<li>
	<div class="uk-grid">
        <div class="uk-width-1-4">
        	<h5>
            	@include('vendor.audits.partials.status')
                {{ __('Robots.txt') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
                @if($summaryAuditPages['results'][$name]['passed'])
                        <p> {{ __('The webpage can be accessed by search engines.') }}</p>
                @else
                @foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
                        @if($error == 'failed')
                        <p> {{ __('The webpage cannot be accessed by search engines.') }} </p>
                        @if(!empty($details))
                        <ul class="inner-accordion uk-accordion" uk-accordion="multiple: true">
                               
                                 <li>
                                    <a class="uk-accordion-title" href="#">
                                        {{ __('Rules') }} <span class="count-badge badge-secondary">{{ number_format(count($details), 0, __('.'), __(',')) }}</span>
                                    </a>
                                    <div class="uk-accordion-content">
                                        <div class="inner-content">
                                            <ul>
                                                @foreach($details as $rule)
                                                <li>{{ $rule }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                               
                        @endif
                        </ul>
                        @endif
                @endforeach
                @endif
        </div>
    </div>
        <a class="uk-accordion-title" href="#" uk-tooltip="title: More; pos: top-center">
        	<span uk-icon="info"></span>
        </a>
	<div class="uk-accordion-content">
		<div class="inner-content">
			<p> {{ __('A robots.txt file inform the search engines which links from the website can be accessed.') }}</p>
			<hr>
			<p>Learn more <a href="https://www.bing.com/webmasters/help/how-to-create-a-robots-txt-file-cb7c31ec" target="_blank">Bing <span uk-icon="arrow-right"></span></a> <a href="https://developers.google.com/search/docs/advanced/robots/intro" target="_blank">Google <span uk-icon="arrow-right"></span></a></p>
		</div>
	</div>
</li>
@endif