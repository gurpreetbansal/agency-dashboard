@if(isset($summaryAuditPages['results'][$name]))
<li>
    <div class="uk-grid">
        <div class="uk-width-1-4">
            <h5>
                @include('vendor.audits.partials.status')
                {{ __('Headings') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
            @if($summaryAuditPages['results'][$name]['passed'])
                <p>{{ __('The headings are properly set.') }}</p>
            @else
                @foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
                <p>
                    @if($error == 'missing')
                        {{ __('There is no h1 tag on the webpage.') }}
                    @endif

                    @if($error == 'too_many')
                        {{ __('Only one h1 tag should be present on the webpage.') }}
                    @endif

                    @if($error == 'duplicate')
                        {{ __('The h1 tag is the same with the title tag.') }}
                    @endif
                </p>
                @endforeach
            @endif
            
            <ul class="inner-accordion uk-accordion" uk-accordion="multiple: true">
                @foreach($summaryAuditPages['results'][$name]['value'] as $key => $value)
                 <li>
                    <a class="uk-accordion-title" href="#">
                        {{ $key }} <span class="count-badge badge-secondary">{{ number_format(count($value), 0, __('.'), __(',')) }}</span>
                    </a>
                    <div class="uk-accordion-content">
                        <div class="inner-content">
                            <ul>
                                @foreach($value as $heading)
                                <li>{{ $heading }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <a class="uk-accordion-title" href="#" uk-tooltip="title: More; pos: top-center">
        <span uk-icon="info"></span>
    </a>
    <div class="uk-accordion-content">
        <div class="inner-content">
            <p>{{ __('The h tags represents the headings of the webpage.') }} {{ __('The h1 tag is the most important h tag, and describes the main topic of the page, while the rest of the tags describe the sub-topics of the webpage.') }}</p>
            <hr>
            <p>Learn more <a target="_blank" href="https://developer.mozilla.org/en-US/docs/Web/HTML/Element/Heading_Elements">Google <span uk-icon="arrow-right"></span></a></p>
        </div>
    </div>
</li>
@endif