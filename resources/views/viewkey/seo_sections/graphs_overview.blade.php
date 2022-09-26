
<div class="uk-width-expand@l right-box">
	<div uk-grid class="uk-grid">
		<div class="uk-width-1-3@xl uk-width-1-3@l uk-width-1-3@m uk-width-1-2@s">
			<div class="white-box">
				<div class="single">
					<h6 class="ok-total ajax-loader">
						<big class="organic-keyword-total">??</big>
						<cite class="organic_keywords"></cite>
					</h6>
					<p>Organic Keywords <span uk-tooltip="title: This section shows growth in organic keywords month after month, however we check this total number of keywords you are ranking for on weekly basis and same can be seen in graph.; pos: top-left" class="fa fa-info-circle" aria-expanded="true"></span></p>
					<div class="chart ok-graph ajax-loader">
						<!-- <div class="ok-graph ajax-loader loader-text h-60-chart"></div> -->
						<canvas id="canvas-organic-keyword"></canvas>
					</div>
				</div>
			</div>
		</div>
		@if($connectivity['ga4'] == true)
		<div class="uk-width-1-3@xl uk-width-1-3@l uk-width-1-3@m uk-width-1-2@s ga4-overview">
			<div class="white-box">
				<div class="single">
					<h6 class="au-total ajax-loader">
						<big class="allUsers-count">??</big>
						<cite class="allUsers_growth"></cite>
					</h6>
					<p>All Users <span uk-tooltip="title: This section shows total number of users to your website in selected time period.; pos: top-left" class="fa fa-info-circle" aria-expanded="false"></span></p>
					<div class="chart au-graph ajax-loader">
						<canvas id="canvas-ga4-allUser"></canvas>
					</div>
				</div>
			</div>
		</div>
		@else
		<div class="uk-width-1-3@xl uk-width-1-3@l uk-width-1-3@m uk-width-1-2@s  ua-overview">
			<div class="white-box">
				<div class="single">
					<h6 class="ov-total ajax-loader">
						<big class="organic-visitors-count">??</big>
						<cite class="organic_visitor_growth"></cite>
					</h6>
					<p>Organic Visitors <span uk-tooltip="title: This section shows total number of organic visits to your website in selected time period.; pos: top-left" class="fa fa-info-circle" aria-expanded="false"></span></p>
					<div class="chart ov-graph ajax-loader">
						<canvas id="canvas-organic-visitor"></canvas>
					</div>
				</div>
			</div>
		</div>
		@endif
		<div class="uk-width-1-3@xl uk-width-1-3@l uk-width-1-3@m uk-width-1-2@s">
			<div class="white-box">
				<div class="single">
					<h6 class="pa-stats ajax-loader">
						<big class="pa_stats">??</big>
						<cite class="pageAuthority_avg"></cite>
					</h6>
					<p>Page Authority <span uk-tooltip="title: This section shows Page authority trend.; pos: top-left" class="fa fa-info-circle" aria-expanded="false"></span></p>
					<div class="chart page-authority ajax-loader">
						<!-- <div class=" loader-text h-60-chart"></div> -->
						<canvas id="canvas-page-authority"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="uk-width-1-3@xl uk-width-1-3@l uk-width-1-3@m uk-width-1-2@s">
			<div class="white-box">
				<div class="single">
					<h6 class="rd-total ajax-loader">
						<big class="backlink_total">??</big>
						<cite class="backlink_avg"></cite>
					</h6>
					<p>Referring Domains <span uk-tooltip="title: This section shows growth in referring domains month after month, however we check the total number of referring domains on weekly basis and same can be seen in graph. ; pos: top-left" class="fa fa-info-circle" title="" aria-expanded="true"></span></p>
					<div class="chart rd-graph ajax-loader">
						<!-- <div class=" loader-text h-60-chart"></div> -->
						<canvas id="canvas-referring-domains"></canvas>
					</div>
				</div>
			</div>
		</div>
		@if($connectivity['ga4'] == true)
		<div class="uk-width-1-3@xl uk-width-1-3@l uk-width-1-3@m uk-width-1-2@s">
			<div class="white-box">
				<div class="single">
					<h6 class="ga4-conversions ajax-loader">
						<big class="Google-analytics4-conversions">??</big>
						<cite class="conversions-result"></cite>
					</h6>
					<p>Conversions <span uk-tooltip="title: This section shows conversions from Google Analytics 4 in selected time period. ; pos: top-left" class="fa fa-info-circle" aria-expanded="false"></span></p>
					<div class="chart ga4-overview-conversions ajax-loader">
						<canvas id="canvas-ga4-conversions"></canvas>	
					</div>
				</div>
			</div>
		</div>
		@else
		<div class="uk-width-1-3@xl uk-width-1-3@l uk-width-1-3@m uk-width-1-2@s">
			<div class="white-box">
				<div class="single">
					<h6 class="goalToal ajax-loader">
						<big class="Google-analytics-goal">??</big>
						<cite class="goal_result"></cite>
					</h6>
					<p>Google Goals <span uk-tooltip="title: This section shows goal completion from Google Analytics in selected time period. ; pos: top-left" class="fa fa-info-circle" aria-expanded="false"></span></p>
					<div class="chart gc-overview-organic ajax-loader">
						<canvas id="google-goal-completion-overview"></canvas>	
					</div>
				</div>
			</div>
		</div>
		@endif
		<div class="uk-width-1-3@xl uk-width-1-3@l uk-width-1-3@m uk-width-1-2@s">
			<div class="white-box">
				<div class="single">
					<h6 class="da-stats ajax-loader">
						<big class="da_stats">??</big>
						<cite class="domainAuthority_avg"></cite>
					</h6>
					<p>Domain Authority <span uk-tooltip="title: This section shows Domain authority trend.; pos: top-left" class="fa fa-info-circle" aria-expanded="false"></span></p>
					<div class="chart domain_authority ajax-loader">
						<!-- <div class="domain_authority ajax-loader loader-text h-60-chart"></div> -->
                		<canvas id="canvas-domain-authority"></canvas>                  	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>