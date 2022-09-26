@extends('layouts.pdf_layout')
@section('content')
@inject('audit', 'App\Http\Controllers\Vendor\SiteAuditController')

<input type="hidden" name="key" id="encriptkey" value="{{ $key }}">
<input type="hidden" class="campaignID" name="campaign_id" value="{{ $campaign_id }}">
<input type="hidden" class="campaign_id" name="campaign_id" value="{{ $campaign_id }}">
<input type="hidden" id="user_id" name="user_id" value="{{ $user_id }}">

<!-- Site Audit PDF Content -->
<div id="SideAudit">
	<div class="audit-summary box-boxshadow">
		<div class="elem-start">
	        <div class="circle_percent">
	        	<div class="circle_inbox">
                    @if($summaryTask['crawl_progress'] == 'finished')
                    <span class="percent_text">{{ (int)$summaryTask['page_metrics']['onpage_score'] }}</span> of 100
                    @else
                    <span class="jumping-dots-loader"><span></span> <span></span> <span></span></span> of 100
                    @endif
                </div>
                <input type="hidden" class="siteAudit-chart-data" value="{{ (int)$summaryTask['page_metrics']['onpage_score'] }}">
                <canvas id="siteAudit-chart-data" width="50" height="50"></canvas>
	        </div>
	        <div class="score-for">
	        	<p>Website score for</p>
	            <h2>{{ $summaryTask['domain_info']['name'] }}</h2>
	        </div>
	    </div>
	</div>

	<div class="site-issues">
		<div class="section-head">
         	<h3>
         		Site-level issues
         		<small><img src="{{URL::asset('public/vendor/internal-pages/images/info.png')}}"> Found {{ $issueCount }}</small>
         	</h3>
      	</div>
      	<div class="audit-table">
	      	<table class="site-level-issues">
	            <tbody>
	                <tr>
	                    <td>
	                    	@if($summaryTask['domain_info']['checks']['sitemap'] == 1)
	                        <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
	                        @else
	                        <img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
	                        @endif
	                        XML sitemap
	                    </td>
	                    <td>
	                    	@if($summaryTask['domain_info']['checks']['sitemap'] == 1)
	                        Site has
	                        <a target="_blank"  href="https://{{ $summaryTask['domain_info']['main_domain'] }}/sitemap_index.xml"> XML sitemap file </a>
	                        @else
	                        Site does not have XML sitemap file
	                        @endif
	                    
	                	</td>
	                </tr>
	                <tr>
	                    <td>
	                        @if($summaryTask['domain_info']['checks']['robots_txt'] == 1 )
	                        <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
	                        @else
	                        <img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
	                        @endif
	                    	Robots.txt
	                    </td>
	                    <td>
	                    	@if($summaryTask['domain_info']['checks']['robots_txt'] == 1 )
	                        Site has <a target="_blank"
	                        href="https://{{ $summaryTask['domain_info']['main_domain'] }}/robots.txt">robots.txt file</a> 
	                        @else
	                        Site does not have robots.txt
	                        @endif
	                    </td>
	                </tr>
	                <tr>
	                    <td>
	                        @if($summaryTask['page_metrics']['checks']['no_favicon'] > 0)
                            <img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
                            @else
                            <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
                            @endif
		                    Favicon
		                </td>
	                    <td>{{ $summaryTask['page_metrics']['checks']['no_favicon'] > 0 ? $summaryTask['page_metrics']['checks']['no_favicon']." page(s) do not have favicon" : "Site has a favicon" }}</td>
	                </tr>
	                <tr>
	                    <td>
	                        @if($summaryTask['page_metrics']['checks']['is_4xx_code'] == 0 )
                            <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
                            @else
                            <img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
                            @endif
	                    	4XX pages
	                    </td>
	                    <td>{{ $summaryTask['page_metrics']['checks']['is_4xx_code'] == 0 ? "No 4XX pages" : $summaryTask['page_metrics']['checks']['is_4xx_code']." page(s) responded with 4xx code"}}</td>
	                </tr>
	                <tr>
	                    <td>
	                        @if($summaryTask['page_metrics']['checks']['is_http'] == 0)
                            <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
                            @else
                            <img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
                            @endif
	                    	HTTP and HTTPS
	                    </td>	                    
	                    <td><?php 
                            if($summaryTask['page_metrics']['checks']['is_http'] == 0){
                                echo "Working protocol redirect: HTTP to HTTPS";
                            }else{
                               $http_count =  $summaryTask['crawl_status']['pages_crawled'] - $summaryTask['page_metrics']['checks']['is_https'];
                               echo $http_count. " page(s) working on http protocol";
                           }
                           ?>
                        </td>
	                </tr>
	                <tr>
	                    <td>
	                       <img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
                            WWW and non-WWW
	                    </td>
	                    <td><?php 
                            if($summaryTask['page_metrics']['checks']['is_www'] == 0){
                                echo "All pages of your domain are on www";
                            }else{
                                echo $summaryTask['page_metrics']['checks']['is_www']. " page(s) of your domain are on www";
                            }
                            ?>
                        </td>
	                </tr>
	            </tbody>
	        </table>
	    </div>
  	</div>

	<div class="audit-stats">
	    <div class="audit-stats-box red">	    
		    <h3> {{ array_sum($errorsListing['critical']) }}</h3>
		    <p>Criticals</p>
	    </div>
	    <div class="audit-stats-box yellow">
	        <h3> {{ array_sum($errorsListing['warning']) }}</h3>
	        <p>Warnings</p>
	    </div>
	    <div class="audit-stats-box blue">
	        <h3> {{ array_sum($errorsListing['notices']) }}</h3>
	        <p>Notices</p>
	    </div>
	</div>

	<div class="issue-overview">
		<div class="section-head">
         	<h3>Issue Overview</h3>
      	</div>
      	<section class="red">
      		<h4>Criticals</h4>
      		<ul>
      			@foreach($errorsListing['critical'] as $keyName => $valueName)
      				@if((int) $valueName >= 1)
      				<li><strong>{{ $auditLevel[$keyName] }}:</strong>	{{ $valueName }} {{ $valueName == 1 ? 'page' : 'pages' }}</li>
      				@endif
      			@endforeach
      		</ul>
      	</section>      	
      	<section class="yellow">
      		<h4>Warnings</h4>
      		<ul>
      			@foreach($errorsListing['warning'] as $keyName => $valueName)
      				@if((int) $valueName >= 1)
      				<li><strong>{{ $auditLevel[$keyName] }}:</strong>	{{ $valueName }} {{ $valueName == 1 ? 'page' : 'pages' }}</li>
      				@endif
      			@endforeach
      		</ul>
      	</section>
      	<section class="blue">
      		<h4>Notices</h4>
      		<ul>
      			@foreach($errorsListing['notices'] as $keyName => $valueName)
      				@if((int) $valueName >= 1)
      				<li><strong>{{ $auditLevel[$keyName] }}:</strong>	{{ $valueName }} {{ $valueName == 1 ? 'page' : 'pages' }}</li>
      				@endif
      			@endforeach
      		</ul>
      	</section>
	</div>

	<div class="pages-overview BreakBefore">
		<div class="section-head">
         	<h3>
	         	Pages Overview
	         	<small><img src="{{URL::asset('public/vendor/internal-pages/images/info.png')}}"> The following list is sorted by internal Page Weight: from highest to lowest</small>
	         </h3>
      	</div>
      	
      	@foreach($summaryTaskPages as $pagekey => $pageValue)
      	<?php $errorsType = $audit->errorBifurcationPages($pageValue); ?>
      	<div class="audit-issue-box">
            <h6>{{ $pageValue['url'] }}</h6>
            <p>{{ $pageValue['meta']['title'] }}</p>
		    <div class="color-messages">
		    	@foreach($errorsType['critical'] as $keyName => $valueName)
		    	<span class="red">{{ $auditLevel[$keyName] }}</span>
		        @endforeach

		        @foreach($errorsType['warning'] as $keyName => $valueName)
		        <span class="yellow">{{ $auditLevel[$keyName] }}</span>
		        @endforeach

		        @foreach($errorsType['notices'] as $keyName => $valueName)
		        <span class="blue">{{ $auditLevel[$keyName] }}</span>
		       
		        @endforeach
                
            </div>
		</div>
		@endforeach
      	
	</div>
</div>

<!-- Site Audit PDF Content End -->
</div>
@endsection