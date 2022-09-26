@if(isset($summaryAuditPages['results'][$name]))
<li>
    <div class="uk-grid">
        <div class="uk-width-1-4">
            <h5>
                @include('vendor.audits.partials.status')
                {{ __('SEO friendly URL') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
            @if($summaryAuditPages['results'][$name]['passed'])
                <p>
                    {{ __('The URL is SEO friendly.') }}
                </p>
            @else
                @foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
                 @if($error == 'bad_format')
                   <p> {{ __('The URL is not SEO friendly.') }}</p>
                @endif

                @if($error == 'missing')
                   <p> {{ __('The URL does not contain any relevant keywords.') }}</p>
                @endif
                @endforeach
            @endif
            <p><small>{{ $summaryAuditPages['results'][$name]['value'] }}</small></p>
        </div>
    </div>
    <a class="uk-accordion-title" href="#" uk-tooltip="title: More; pos: top-center">
        <span uk-icon="info"></span>
    </a>
    <div class="uk-accordion-content">
        <div class="inner-content">
            <p> {{ __('The SEO friendly URLs are URLs that contain relevant keywords with the webpage\'s topic, and contain no special characters besides slashes and dashes.') }}</p>
            <hr>
            <p>Learn more <a target="_blank" href="https://developers.google.com/search/docs/advanced/guidelines/url-structure">Google <span uk-icon="arrow-right"></span></a></p>
        </div>
    </div>
</li>
@endif