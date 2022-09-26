@if($dashtype == 'seo')
    @if($active == 'diable')
        <li class="sideDashboardView active">
            <a href="#socailDashboard">
                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/dashboard-icon.png')}}" alt="Dashboard"></figure>
                <span>Dashboard</span>
            </a>
        </li>
    @else
        <li id="seo_sidebase" class="sideDashboardView  active">
            <a href="#seo_dashboard">
                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/dashboard-icon.png')}}" alt="Dashboard"></figure>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="sideDashboardView">
            <a href="#visibility">
                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/rankings-icon.png')}}" alt="Visibility"></figure>
                <span>Visibility</span>
            </a>
        </li>
        <li class="sideDashboardView">
            <a href="#rankings">
                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/rankings-icon.png')}}" alt="Rankings"></figure>
                <span>Rankings</span>
            </a>
        </li>
        <li class="sideDashboardView">
            <a href="#traffic">
                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/traffic-icon.png')}}" alt="Traffic"></figure>
                <span>Traffic</span>
            </a>
        </li>
        <li class="sideDashboardView">
            <a href="#backlinks">
                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/backlinks-icon.png')}}" alt="Backlinks"></figure>
                <span>Backlinks</span>
            </a>
        </li>
        <li class="sideDashboardView">
            <a href="#goals">
                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/leads-icon.png')}}" alt="Leads"></figure>
                <span>Goals</span>
            </a>
        </li>
        
        <li class="sideDashboardView" id="audit-tab">
            <a href="#audit">
                <figure><img src="https://waveitdigital.com/public/vendor/internal-pages/images/audit-icon.png" alt="Site Audit"></figure>
                <span>Site Audit</span>
            </a>
        </li>

        <li class="sideDashboardView">
            <a href="#activity">
                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/activity-icon.png')}}" alt="Activity"></figure>
                <span>Activity</span>
            </a>
        </li>
         <li class="sideDashboardView">
            <a href="#keywordExplorer">
                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/icon-keywordExplorer.svg')}}" alt="keywordExplorer"></figure>
                <span>Keyword Explorer</span>
            </a>
        </li>
    @endif
@elseif($dashtype == 'ppc')
    @if($active == 'diable')
        <li class="sideDashboardView active">
            <a href="#socailDashboard">
                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/dashboard-icon.png')}}" alt="Dashboard"></figure>
                Dashboard
            </a>
        </li>
    @else
        <li id="ppc_sidebase" class="sideDashboardView active">
            <a href="#ppcDashboard">
                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/dashboard-icon.png')}}" alt="Dashboard"></figure>
                Dashboard
            </a>
        </li>

        <li class="sideDashboardView">
            <a href="#campaignAdGroups">
                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/campaigns.png')}}" alt="Campaigns"></figure>
                Campaigns
            </a>
        </li>

        <li class="sideDashboardView">
            <a href="#keywords">
                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/keywords.png')}}" alt="Keywords"></figure>
                Keywords
            </a>
        </li>
        <li class="sideDashboardView">
            <a href="#ads">
                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/ads.png')}}" alt="Ads"></figure>
                Ads
            </a>
        </li>
        <li class="sideDashboardView">
            <a href="#performance">
                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/performance.png')}}" alt="Performance"></figure>
                Performance Data
            </a>
        </li>
@endif
@elseif($dashtype == 'gmb')
<li class="sideDashboardView active">
    <a href="#gmbDashboard">
        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/dashboard-icon.png')}}" alt="Dashboard"></figure>
        Dashboard
    </a>
</li>
@elseif($dashtype == 'social')
<li class="social_module active overview_view">
    <a href="#overview">
        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/dashboard-icon.png')}}" alt="Dashboard"></figure>
        <span>Dashboard</span>
    </a>
</li>
@if($active != 'facebook')
<li class="social_module facebook_view">
    <a href="#facebook">
        <figure><span uk-icon="icon: facebook"></span>
        </figure>
        <span>Facebook</span>
    </a>
</li>
@endif
<li class="sideDashboardView facebook-view-common" style="display: none;">
    <a href="#facebookviewlikes">
        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/social-likes-icon.png')}}" alt="Likes"></figure>
        <span>Likes</span>
    </a>
</li>
<li class="sideDashboardView facebook-view-common" style="display: none;">
    <a href="#facebookviewreach">
        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/social-reach-icon.png')}}" alt="Reach"></figure>
        <span>Reach</span>
    </a>
</li>
<li class="sideDashboardView facebook-view-common view_key_sidebar_post" style="display: none;">
    <a href="#facebookviewpostreviews">
        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/social-posts-icon.png')}}" alt="Post"></figure>
        <span>Post</span>
    </a>
</li>
<li class="sideDashboardView facebook-view-common" style="display: none;">
    <a href="#facebookviewreviews">
        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/social-reviews-icon.png')}}" alt="Reviews"></figure>
        <span>Reviews</span>
    </a>
</li>
@else
<li class="sideDashboardView active">
    <a href="#socailDashboard">
        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/dashboard-icon.png')}}" alt="Dashboard"></figure>
        Dashboard
    </a>
</li>
@endif
