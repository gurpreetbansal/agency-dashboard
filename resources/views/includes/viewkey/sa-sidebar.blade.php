<aside class="sidebar">
    <div class="logo">
        <a href="{{url('/')}}">
            <img src="{{ URL::asset('public/front/img/logo.svg')}}" alt="logo">
           
        </a>
    </div>
    
    <nav>
         <div class="uk-navbar-item">
            <form action="javascript:void(0)">
                <input type="text" placeholder="Search..." class="projects_autocomplete">
                <div class="refresh-search-icon" id="refresh-sidebar-search">
                    <span uk-icon="refresh"></span>
                </div>
                <a href="javascript:;" class="sidebar-search-clear"><span class="clear-input sidebarClear" uk-icon="icon: close;"></span></a>
                <button type="submit"><span uk-icon="icon:search"></span></button>
            </form>
        </div>
    
        
        @php
            @$key = array_search($summaryAudit->url, array_column($summaryAudit->pages->toArray(), 'url'));
        @endphp
        <ul class="uk-nav-default uk-nav-parent-icon" >

            <li data-id="{{ '' }}" class="uk-parent uk-open sa-auditHome active uk-active">
                <a href="javascript:;" data-page="summary" class="individual-audit" >

                    <figure><img src="{{ url('public/vendor/internal-pages/images/check-site-audit.png') }}" alt="Dashboard"></figure>
                    <span>Site Audit</span>
                </a>
                <ul class="uk-nav-sub" id="defaultCampaignList">
                    @foreach($summaryAudit->pages as $key => $value)
                    <li uk-tooltip="title:{{ $value->url }}; pos: top-left" class="">
                        <a data-audit="{{ $value->id }}" href="{{ url('/audit/page/detail') }}/{{ $summaryAudit->share_key }}/{{ $value->id }}" class="audit-pages-details"  >{{ parse_url($value->url, PHP_URL_PATH) }}</a>
                    </li>
                    @endforeach
                </ul>
            </li>
        </ul>
    </nav>
</aside>