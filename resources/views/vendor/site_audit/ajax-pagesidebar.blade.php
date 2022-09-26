<h5 class="uk-text-medium">Filters</h5>
<div class="heading {{ $filter == '' ? 'active': ''  }}">
 <a href="{{ url('/audit-pages/'.$campaign_id) }}" > <span>All Pages</span> <span class="uk-float-right">{{ array_sum($errorsListing['critical']) + array_sum($errorsListing['warning']) + array_sum($errorsListing['notices']) }}</span> </a>
</div>
<?php $counterNotZero = $counterZero = 0; ?>
@foreach($errorsListing['critical'] as $key => $value)
@if($value > 0)
@if($counterNotZero == 0)
<ul class="all-pages-con">
    <li class="{{ $filter == 'critical' ? 'active': ''  }}" ><a href="{{ url('/audit-pages/'.$campaign_id.'?filter=critical') }}" class="light-red-bg p-1"><span class="uk-text-medium">All Criticals</span> <span class="no">{{ array_sum($errorsListing['critical']) }}</span></a></li>
    @endif         
    <li class="{{ $key == $filter ? 'active': ''  }}" > <a href="{{ url('/audit-pages/'.$campaign_id.'?filter='.$key) }}"> <span>{{ $auditLevel[$key] }}</span> <span class="no">{{ $value }}</span> </a></li>
    <?php $counterNotZero++; ?>
    @else
    @if($counterZero == 0)
</ul>
<ul class="accordion-all-criticals uk-accordion">
    <li>
        <div class="uk-accordion-content" style="display: none;">
            <ul class="all-pages-con">
                @endif            
                <li><span>{{ $auditLevel[$key] }}</span> <span class="no">{{ $value }}</span></li>
                <?php $counterZero++; ?>
                @endif
                @endforeach

            </ul>
        </div>
        <a class="uk-accordion-action accordion-all-criticals-title" href="javascript:void(0)">Show zero criticals</a>
    </li>
</ul>

<?php $counterNotZero = $counterZero = 0; ?>
@foreach($errorsListing['warning'] as $key => $value)
@if($value > 0)
@if($counterNotZero == 0)
<ul class="all-pages-con">
    <li class="{{ $filter == 'warning' ? 'active': ''  }}" > <a href="{{ url('/audit-pages/'.$campaign_id.'?filter=warning') }}" class="light-orange-bg p-1"> <span class="uk-text-medium">All Warnings</span> <span class="no">{{ array_sum($errorsListing['warning']) }}</span></a></li>
    @endif         
    <li class="{{ $key == $filter ? 'active': ''  }}" ><a href="{{ url('/audit-pages/'.$campaign_id.'?filter='.$key) }}"><span>{{ $auditLevel[$key] }}</span> <span class="no">{{ $value }}</span></a></li>

    <?php $counterNotZero++; ?>
    @else

    @if($counterZero == 0)
</ul>
<ul class="accordion-all-warnings uk-accordion">
    <li>
        <div class="uk-accordion-content" style="display: none;">
            <ul class="all-pages-con">
                @endif            
                <li><span>{{ $auditLevel[$key] }}</span> <span class="no">{{ $value }}</span></li>
                <?php $counterZero++; ?>
                @endif


                @endforeach

            </ul>
        </div>
        <a class="uk-accordion-action accordion-all-warnings-title" href="javascript:void(0)">Show zero warnings</a>
    </li>
</ul>

<?php $counterNotZero = $counterZero = 0; ?>
@foreach($errorsListing['notices'] as $key => $value)
@if($value > 0)
@if($counterNotZero == 0)
<ul class="all-pages-con">
    <li class="{{ $filter == 'notices' ? 'active': ''  }}" ><a href="{{ url('/audit-pages/'.$campaign_id.'?filter=notices') }}" class="light-blue-bg p-1"><span class="uk-text-medium">All Notices</span> <span class="no">{{ array_sum($errorsListing['notices']) }}</span></a></li>
    @endif         
    <li class="{{ $key == $filter ? 'active': ''  }}" ><a href="{{ url('/audit-pages/'.$campaign_id.'?filter='.$key) }}"><span>{{ $auditLevel[$key] }}</span> <span class="no">{{ $value }}</span></a></li>

    <?php $counterNotZero++; ?>
    @else

    @if($counterZero == 0)
</ul>
<ul class="accordion-all-notices uk-accordion">
    <li>
        <div class="uk-accordion-content" style="display: none;">
            <ul class="all-pages-con">
                @endif            
                <li><span>{{ $auditLevel[$key] }}</span> <span class="no">{{ $value }}</span></li>
                <?php $counterZero++; ?>
                @endif

                @endforeach
            </ul>
        </div>
        <a class="uk-accordion-action accordion-all-notices-title" href="javascript:void(0)">Show zero notices</a>
    </li>
</ul>