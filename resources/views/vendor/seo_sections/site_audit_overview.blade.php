<div class="uk-width-auto@m">
	<div class="white-box h-100 left-box">
		<div class="progress-loader sa-overview-loader"></div>
		<div class="elem-start">
			<div class="circle-donut ajax-loader" style="width:200px;height:200px;">
				<div class="circle_inbox">
					<span class="percent_text">0</span> of 100
				</div>
				<canvas id="detail-siteAudit-chart-data" width="50" height="50"></canvas>
			</div>
			<div class="score-for">
				<p><small>Website score for</small></p>
				<h2 class="ajax-loader audit-domain-name">.........</h2>
				<ul>
					<li class="ajax-loader audit-ip-address"><i class="fa fa-map-marker"></i>.........</li>
					<li>|</li>
					<li class="ajax-loader audit-ssl-status"><i class="fa fa-lock"></i>  ..........</li>
				</ul>
				<a href="{{url('/audit/detail/'.$campaign_id)}}" target="_blank" data-id="{{$campaign_id}}" class="btn btn-sm blue-btn" id="sa-overview">View Audit</a>
			</div>
		</div>
		<div class="elem-end">
			<ul>
				<li>
					<div><img src="{{URL::asset('public/vendor/internal-pages/images/pages-icon.png')}}" alt="pages-icon"> Crawled pages</div>
					<div class="ajax-loader crawled-pages">......</div>
				</li>
			</ul>
			<ul>
				<li>
					<div>
						<img src="{{URL::asset('public/vendor/internal-pages/images/google-indexed-logo.png')}}" alt="google-indexed-logo">
						Google indexed pages
					</div>
					<div class="ajax-loader audit-indexed-pages">....</div>
				</li>
				<li>
					<div>
						<img src="{{URL::asset('public/vendor/internal-pages/images/google-safe-icon.png')}}" alt="google-safe-icon">
						Google safe browsing
					</div>
					<div class="ajax-loader audit-site-status">...........</div>
				</li>
			</ul>
		</div>
	</div>
</div>