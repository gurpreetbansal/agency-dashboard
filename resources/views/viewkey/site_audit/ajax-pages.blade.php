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
        <div class="helpful">
            <h6>Was this helpful?</h6>
            <div class="smiley">
                <button>
                    <svg _ngcontent-khp-c260="" width="64" height="65" viewBox="0 0 64 65" fill="none" class="ng-tns-c260-18">
                        <circle _ngcontent-khp-c260="" cx="44" cy="23.0237" r="3" class="ng-tns-c260-18"></circle>
                        <circle _ngcontent-khp-c260="" cx="20" cy="23.0237" r="3" class="ng-tns-c260-18"></circle>
                        <path _ngcontent-khp-c260="" d="M18.5134 32.276C17.4564 33.1632 17.3093 34.7529 18.3213 35.691C19.8581 37.1156 21.6131 38.2934 23.5245 39.1772C26.1941 40.4115 29.103 41.0418 32.044 41.0233C34.985 41.0048 37.8857 40.3378 40.5395 39.07C42.4396 38.1622 44.1796 36.9624 45.6983 35.5186C46.6985 34.5678 46.5313 32.98 45.4633 32.1062V32.1062C44.3952 31.2323 42.8326 31.409 41.7938 32.3174C40.768 33.2144 39.6218 33.9701 38.3853 34.5608C36.3946 35.5119 34.2186 36.0122 32.0125 36.026C29.8063 36.0399 27.6243 35.5671 25.6218 34.6412C24.3779 34.0661 23.2223 33.325 22.1853 32.441C21.1351 31.5457 19.5703 31.3887 18.5134 32.276V32.276Z" class="ng-tns-c260-18"></path>
                    </svg>
                </button>
                <button>
                    <svg _ngcontent-dbp-c260="" width="64" height="64" viewBox="0 0 64 64" fill="none" class="ng-tns-c260-14">
                        <circle _ngcontent-dbp-c260="" cx="44" cy="32" r="3" class="ng-tns-c260-14"></circle>
                        <circle _ngcontent-dbp-c260="" cx="20" cy="32" r="3" class="ng-tns-c260-14"></circle>
                        <path _ngcontent-dbp-c260="" d="M45.5942 47.7554C46.6661 46.8863 46.8403 45.2993 45.8443 44.344C44.332 42.8935 42.5973 41.686 40.7012 40.7698C38.0531 39.4903 35.1554 38.8105 32.2145 38.779C29.2736 38.7474 26.3619 39.3649 23.6869 40.5874C21.7716 41.4627 20.0114 42.6327 18.4684 44.0505C17.4522 44.9841 17.5923 46.5745 18.6453 47.4664V47.4664C19.6983 48.3583 21.2637 48.2082 22.3179 47.3176C23.3588 46.4382 24.5177 45.7022 25.7641 45.1326C27.7707 44.2155 29.9548 43.7524 32.1609 43.776C34.3669 43.7997 36.5406 44.3096 38.5271 45.2694C39.761 45.8657 40.9039 46.6264 41.9257 47.5279C42.9605 48.4409 44.5223 48.6245 45.5942 47.7554V47.7554Z"  class="ng-tns-c260-14"></path>
                    </svg>
                </button>
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
            <a href="javascript:;" data-key="{{ $pagekey }}" class="btn btn-xsm blue-btn pagesAudit"><i class="fa fa-file-text" aria-hidden="true"></i> View page audit</a>
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