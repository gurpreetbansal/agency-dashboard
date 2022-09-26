@if(isset($summaryAuditPages['results'][$name]))
<li>
    <div class="uk-grid">
        <div class="uk-width-1-4">
            <h5>
                @include('vendor.audits.partials.status')
                {{ __('Structured data') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
            @if($summaryAuditPages['results'][$name]['passed'])
                <p> {{ __('The webpage has structured data.') }}</p>
            @else
                @foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
                <p> 
                    @if($error == 'missing')
                         {{ __('There are no structured data tags on the webpage.') }}
                    @endif
                </p>
                @endforeach
            @endif
            
            <ul class="inner-accordion uk-accordion" uk-accordion="multiple: true">
                @foreach($summaryAuditPages['results'][$name]['value'] as $key => $value)
                 <li>
                    <a class="uk-accordion-title" href="#">
                        {{ $key }} <span class="count-badge badge-secondary">{{ number_format(count($summaryAuditPages['results'][$name]['value'][$key]), 0, __('.'), __(',')) }}</span>
                    </a>
                    <div class="uk-accordion-content">
                        <div class="inner-content">
                            {{ arrayToHtml($value, ['<ol class="mb-0">', '</ol>'], ['<ul>', '</ul>'], ['<li class="py-1 text-break">', '</li>'], ['<span class="font-weight-medium">', '</span> '], ['<span class="text-muted">', '</span>']) }}
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
            <p>Learn more <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Element/Heading_Elements" target="_blank">Google <span uk-icon="arrow-right"></span></a></p>
        </div>
    </div>
</li>
@endif