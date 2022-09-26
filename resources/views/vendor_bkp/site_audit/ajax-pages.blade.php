@inject('audit', 'App\Http\Controllers\Vendor\SiteAuditController')

<input type="hidden" class="issuetags" value="{{ @$issueTags[$filter] }}" name="">

<div id="offcanvas-flip" uk-offcanvas="flip: true; overlay: true">
    <div class="uk-offcanvas-bar custom-offcanvas">
        <button class="uk-offcanvas-close" type="button" uk-close></button>
        <div class="gbox red-gradient">
            <h3><small>Critical</small> <span class="sidedrawer-label"> 4xx client errors </span> </h3>
        </div>
        <div class="progress-loader"> </div>
        <div class="content-box">
            <div class="sidedrawer-short-description" >
                <p>Below you see the list of URLs with 4xx status code. Sitechecker bot found these URLs because
                    other pages on your website link to them. To check which pages contain the specific broken
                    link, click “Anchors.”
                </p>
            </div>
            <hr>
            <div class="sidedrawer-description" >
                <h5>Why It's Important</h5>
                <p>A 4xx error deserves maximum attention.</p>
                <p>Such error signals that the content of the page isn’t visible to search engines, which also
                    means that the page won’t be displayed in search engine results - this will impact organic
                    traffic to the page. Importantly, if a 4xx error is detected by search engines, the
                    respective page would be removed from their index and it might be troublesome to get it
                    re-indexed once the problem is solved. If multiple 4xx errors are detected on your site,
                    search engines might even lower its ranking or the number of pages indexed.
                </p>
            </div>
        </div>
        
    </div>
</div>


@foreach($summaryTask as $pagekey => $pageValue)

<?php $errorsType = $audit->errorBifurcationPages($pageValue); ?>
<div class="audit-content-inner">
    <div class="uk-flex">
        <div>
            <p class="mb-0">
                <a target="_blank" href="{{ $pageValue['url'] }}"> {{ $pageValue['url'] }} </a> 
                <a class="copy-icon copy-page-url" data-clipboard-text="{{ $pageValue['url'] }}" uk-tooltip="title: Click for copy; pos: top-center">
                    <svg width="100%" height="100%" viewBox="0 0 24 24" fit="" preserveAspectRatio="xMidYMid meet" focusable="false"><path d="M4.02425 12.5687C4.02425 11.0052 5.29511 9.73434 6.85854 9.73434H10.5157V7.99719H6.85854C5.64611 7.99719 4.48336 8.47883 3.62605 9.33613C2.76874 10.1934 2.28711 11.3562 2.28711 12.5687C2.28711 13.781 2.76874 14.9438 3.62605 15.8011C4.48336 16.6584 5.64611 17.1401 6.85854 17.1401H10.5157V15.403H6.85854C5.29511 15.403 4.02425 14.1321 4.02425 12.5687ZM7.77282 13.483H15.0871V11.6544H7.77282V13.483ZM16.0014 7.99719H12.3442V9.73434H16.0014C17.5648 9.73434 18.8357 11.0052 18.8357 12.5687C18.8357 14.1321 17.5648 15.403 16.0014 15.403H12.3442V17.1401H16.0014C17.2138 17.1401 18.3766 16.6584 19.2338 15.8011C20.0912 14.9438 20.5728 13.781 20.5728 12.5687C20.5728 11.3562 20.0912 10.1934 19.2338 9.33613C18.3766 8.47883 17.2138 7.99719 16.0014 7.99719Z"></path></svg>
                </a> 
            </p>
            <small>{{ $pageValue['meta']['title']}} </small>
            <div class="links">Links:
                <a href="javascript:;" class="pages-anchor links-tabing" uk-toggle="target: #offcanvas-links" data-url="{{ $pageValue['url'] }}" data-title="{{ $pageValue['meta']['title']}}" ><img src="{{ URL::asset('public/vendor/internal-pages/images/anchor-icon.png') }}" alt="" /> Anchors: <span>{{ $pageValue['meta']['inbound_links_count']}}</span></a>
                <a href="javascript:;" class="pages-internal links-tabing" uk-toggle="target: #offcanvas-links" data-url="{{ $pageValue['url'] }}" data-title="{{ $pageValue['meta']['title']}}" ><img src="{{ URL::asset('public/vendor/internal-pages/images/internal-icon.png') }}" alt="" /> Internal: <span>{{ $pageValue['meta']['internal_links_count']}}</span></a>
                <a href="javascript:;" class="pages-external links-tabing" uk-toggle="target: #offcanvas-links" data-url="{{ $pageValue['url'] }}" data-title="{{ $pageValue['meta']['title']}}" ><img src="{{ URL::asset('public/vendor/internal-pages/images/external-icon.png') }}" alt="" /> External: <span>{{ $pageValue['meta']['external_links_count']}}</span></a>
            </div>
        </div>
        <div class="uk-margin-auto-left">
            <a href="{{ url('/audit-details/'.$campaign_id.'/'.$pagekey) }}" class="btn btn-xsm blue-btn"><i class="fa fa-file-text" aria-hidden="true"></i> View page audit</a>
            <a href="javascript:;" uk-toggle="target: #offcanvas-pagecode"  class="btn btn-xsm btn-border blue-btn-border viewsource" data-url="{{ $pageValue['url'] }}" data-title="{{ $pageValue['meta']['title']}}" ><i class="fa fa-file-code-o" aria-hidden="true"></i> View page code </a>
            @if($optAnArr <>  null)
            <a href="javascript:;" uk-toggle="target: #{{ $optAnArr['target'] }}"  class="btn btn-xsm btn-border blue-btn-border {{ $optAnArr['optionAnchorClass'] }}" data-url="{{ $pageValue['url'] }}" data-title="{{ $pageValue['meta']['title']}}" data-description="{{ $pageValue['meta']['description']}}" ><i class="fa fa-files-o" aria-hidden="true"></i> {{ $optAnArr['optionAnchor'] }} </a>
            @endif
        </div>
    </div>

    <div class="color-messages">
        @foreach($errorsType['critical'] as $keyName => $valueName)
        <span><a href="javascript:;" uk-toggle="target: #offcanvas-flip" class="critical-border sidedrawererror" data-type="critical" data-value="{{ $keyName }}" >{{ $auditLevel[$keyName] }}</a></span>
        @endforeach
       

        @foreach($errorsType['warning'] as $keyName => $valueName)
        <span><a href="javascript:;" uk-toggle="target: #offcanvas-flip" class="warning-border sidedrawererror" data-type="warning" data-value="{{ $keyName }}" >{{ $auditLevel[$keyName] }}</a></span>
        @endforeach

        @foreach($errorsType['notices'] as $keyName => $valueName)
        <span><a href="javascript:;" uk-toggle="target: #offcanvas-flip" class="notices-border sidedrawererror" data-type="notices" data-value="{{ $keyName }}" >{{ $auditLevel[$keyName] }}</a></span>
        @endforeach
    </div>
</div>

@endforeach