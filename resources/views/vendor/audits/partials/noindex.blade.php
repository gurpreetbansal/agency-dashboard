@if(isset($summaryAuditPages['results'][$name]))
<li>
    <div class="uk-grid">
        <div class="uk-width-1-4">
            <h5>
                @include('vendor.audits.partials.status')
                {{ __('Noindex') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
            @if($summaryAuditPages['results'][$name]['passed'])
                    <p> {{ __('The webpage does not have a noindex tag set.') }}</p>
            @else
            @foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
                    @if($error == 'missing')
                    <p> {{ __('The webpage has a noindex tag set.') }} </p>
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
            <p> {{ __('The noindex tag instruct the search engines to not index the webpage.') }}</p>
            <hr>
            <p>Learn more <a href="https://www.bing.com/webmasters/help/which-robots-metatags-does-bing-support-5198d240" target="_blank">Bing <span uk-icon="arrow-right"></span></a>
             <a href="https://developers.google.com/search/docs/advanced/crawling/block-indexing" target="_blank">Google <span uk-icon="arrow-right"></span></a></p>
        </div>
    </div>
</li>
@endif