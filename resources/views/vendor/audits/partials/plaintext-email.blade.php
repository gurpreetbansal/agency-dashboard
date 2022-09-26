@if(isset($summaryAuditPages['results'][$name]))
<li>
    <div class="uk-grid">
        <div class="uk-width-1-4">
            <h5>
                @include('vendor.audits.partials.status')
                {{ __('Plaintext email') }}
            </h5>
        </div>
        <div class="uk-width-3-4">
            @if($summaryAuditPages['results'][$name]['passed'])
                <p>{{ __('The webpage does not contain any plaintext emails.') }}</p>
            @else
                @foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
                    @if($error == 'failed')
                        <p>{{ __('The webpage contains plaintext emails.') }} </p>
                    <ul class="inner-accordion uk-accordion" uk-accordion="multiple: true">
                        
                         <li>
                            <a class="uk-accordion-title" href="#">
                                {{ __('Emails') }} <span class="count-badge badge-secondary">{{ number_format(count($details), 0, __('.'), __(',')) }}</span>
                            </a>
                            <div class="uk-accordion-content">
                                <div class="inner-content">
                                    <ul>
                                        @foreach($details as $email)
                                        <li>{{ $email }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </li>
                        
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
            <p>   {{ __('Email addresses posted in public are likely to be fetched by crawlers and then collected in lists used for spam.') }}</p>
      
           
        </div>
    </div>
</li>
@endif