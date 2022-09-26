@if(isset($summaryAuditPages['results'][$name]))
<li>
    <div class="uk-grid">
        <div class="uk-width-1-4">
            <h5>
                @include('vendor.audits.partials.status')
                {{ __('Language') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
            @if($summaryAuditPages['results'][$name]['passed'])
                    <p> {{ __('The webpage has the language declared.') }}</p>
                    <div>
                        <code>{{ $summaryAuditPages['results'][$name]['value'] }}</code>
                    </div>
            @else
            @foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
                    @if($error == 'missing')
                    <p> {{ __('The webpage does not have a language declared.') }} </p>
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
            <p> {{ __('The lang attribute declares the webpage\'s language, helping search engines identify the language in which the content is written, and browsers to offer translation suggestions.') }}</p>
            <hr>
            <p>Learn more <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Element/html#accessibility_concerns" target="_blank">Mozilla <span uk-icon="arrow-right"></span></a></p>
        </div>
    </div>
</li>
@endif