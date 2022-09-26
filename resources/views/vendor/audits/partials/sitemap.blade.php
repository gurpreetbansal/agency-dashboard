@if(isset($summaryAuditPages['results'][$name]))
<li>
    <div class="uk-grid">
        <div class="uk-width-1-4">
            <h5>
                @include('vendor.audits.partials.status')
                {{ __('Sitemap') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
            @if($summaryAuditPages['results'][$name]['passed'])

                <p> {{ __('The website has sitemaps.') }}</p>
                 @if(!empty($summaryAuditPages['results'][$name]['value']))
                    <ul class="inner-accordion uk-accordion" uk-accordion="multiple: true">
                         <li>
                            <a class="uk-accordion-title" href="#">
                                {{ __('Sitemaps') }} <span class="count-badge badge-secondary">{{ number_format(count($summaryAuditPages['results'][$name]['value']), 0, __('.'), __(',')) }}</span>
                            </a>
                            <div class="uk-accordion-content">
                                <div class="inner-content">
                                    <ul>
                                    @foreach($summaryAuditPages['results'][$name]['value'] as $error => $details)
                                        <li><a href="{{ $details }}" class="text-break" rel="nofollow" target="_blank">{{ $details }}</a></li>
                                    @endforeach
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                 @endif

            @else
                @foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
                <p> 
                    @if($error == 'failed')
                        {{ __('No sitemap found.') }}
                    @endif
                </p>
                @endforeach
            @endif
            
        </div>
    </div>
    <a class="uk-accordion-title" href="#" uk-tooltip="title: More; pos: top-center">
        <span uk-icon="info"></span>
    </a>
    <div class="uk-accordion-content">
        <div class="inner-content">
            <p>{{ __('Sitemaps inform search engines about pages available for crawling on the website.') }}</p>
            <hr>
            <p>Learn more 
                <a href="https://www.bing.com/webmasters/help/sitemaps-3b5cf6ed" target="_blank">Bing <span uk-icon="arrow-right"></span></a>
                <a href="https://developers.google.com/search/docs/advanced/sitemaps/overview" target="_blank">Google <span uk-icon="arrow-right"></span></a></p>
        </div>
    </div>
</li>
@endif