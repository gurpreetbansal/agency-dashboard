@if(isset($dashboardStatus) && $dashboardStatus == false)
<div class="main-data-viewDeactive" uk-sortable="handle:.white-box-handle" id="seoDashboard">  
  <div class="white-box mb-40 " id="seoDashboardDeactive" >
    <div class="integration-list" >
      <article>
        <figure>
          <img src="{{URL::asset('public/vendor/internal-pages/images/google-search-console-img.png')}}">
        </figure>
        <div>
          <p>The SEO Dashboard is not enabled for your account.</p>
          <?php
          if(isset($profile_data->ProfileInfo->email)){
            $email = $profile_data->ProfileInfo->email;
          }else{
            $email = $profile_data->UserInfo->email;
          }
          ?>

          <a href="mailto:{{ $email }}" class="btn btn-border blue-btn-border">Contact us</a>
        </div>

      </article>
    </div>
  </div>

  @elseif($types <> null)
  
<div class="popup" id="activityprogress" data-pd-popup="checkProgress"> </div>
<div class="main-data-view" uk-sortable="handle:.white-box-handle" id="seoDashboard">
<input type="hidden" id="viewactivityload" name="viewtype" value="viewload"/>
<div class="campaign-hero mb-40"><div uk-grid class="uk-grid">@include('viewkey.seo_sections.site_audit_overview')@include('viewkey.seo_sections.graphs_overview')</div></div>
@include('viewkey.seo_sections.search_console')
@include('viewkey.seo_sections.organic_traffic_growth')
@include('viewkey.seo_sections.ott_ga4')
<div id="seoDashboardMore">
@include('viewkey.seo_sections.organic_keyword_growth')
@include('viewkey.seo_sections.live_keyword_tracking')
@include('viewkey.seo_sections.backlink_profile')
@include('viewkey.seo_sections.goal_completion')
@include('viewkey.seo_sections.ga4_goals')
@include('viewkey.seo_sections.activities')
</div>
@else
<div class="main-data-viewDeactive" uk-sortable="handle:.white-box-handle" id="seoDashboard">  
  <div class="white-box mb-40 " id="seoDashboardDeactive" >
    <div class="integration-list" >
      <article>
        <figure>
          <img src="{{URL::asset('public/vendor/internal-pages/images/google-search-console-img.png')}}">
        </figure>
        <div>
          <p>The SEO Dashboard is not enabled for your account.</p>
          <?php
          if(isset($profile_data->ProfileInfo->email)){
            $email = $profile_data->ProfileInfo->email;
          }else{
            $email = $profile_data->UserInfo->email;
          }
          ?>

          <a href="mailto:{{ $email }}" class="btn btn-border blue-btn-border">Contact us</a>
        </div>

      </article>
    </div>
  </div>
  @endif    
</div>    