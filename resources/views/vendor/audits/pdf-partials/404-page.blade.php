@if(isset($summaryAuditPages['results'][$name]))
<li>
	<div class="uk-grid">
                <div class="uk-width-1-4">
                       <h5>
                           @include('vendor.audits.partials.status')
                           {{ __('404 page') }}
                   </h5>
           </div>
           <div class="uk-width-3-4">
               @if($summaryAuditPages['results'][$name]['passed'])
               <p>{{ __('The website has 404 error pages.') }}</p>
               @else
               @foreach($summaryAuditPages['results'][$name]['errors'] as $error => $details)
               @if($error == 'missing')
               <p> {{ __('The website does not have 404 error pages.') }} </p>
               @endif
               @endforeach
               @endif
               <p><small><a target="_blank" href="{{ $summaryAuditPages['results'][$name]['value'] }}">{{ $summaryAuditPages['results'][$name]['value'] }}</a></small></p>
       </div>
</div>
</li>
@endif