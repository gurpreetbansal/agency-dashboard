
<div class="uk-width-expand@l right-box">
	<div uk-grid>
		<div class="uk-width-1-3">
			<div class="white-box small-chart-box">
				<div class="single">
					<h6 class="ok-total ajax-loader">
						<big class="organic-keyword-total">??</big>
						<cite class="organic_keywords"></cite>
					</h6>
					<p>Organic Keywords</p>
					<div class="chart ok-graph ajax-loader">
						<canvas id="canvas-organic-keyword"></canvas>
					</div>
				</div>
			</div>
		</div>
		@if($connectivity['ga4'] == true)
		<div class="uk-width-1-3 ga4-overview">
			<div class="white-box small-chart-box">
				<div class="single">
					<h6 class="au-total ajax-loader">
						<big class="allUsers-count">??</big>
						<cite class="allUsers_growth"></cite>
					</h6>
					<p>All Users </p>
					<div class="chart au-graph ajax-loader">
						<canvas id="canvas-ga4-allUser"></canvas>
					</div>
				</div>
			</div>
		</div>
		@else
		<div class="uk-width-1-3">
			<div class="white-box small-chart-box">
				<div class="single">
					<h6 class="ov-total ajax-loader">
						<big class="organic-visitors-count">??</big>
						<cite class="organic_visitor_growth"></cite>
					</h6>
					<p>Organic Visitors </p>
					<div class="chart ov-graph ajax-loader">
						<canvas id="canvas-organic-visitor"></canvas>
					</div>
				</div>
			</div>
		</div>
		@endif
		<div class="uk-width-1-3">
			<div class="white-box small-chart-box">
				<div class="single">
					<h6 class="pa-stats ajax-loader">
						<big class="pa_stats">??</big>
						<cite class="pageAuthority_avg"></cite>
					</h6>
					<p>Page Authority</p>
					<div class="chart page-authority ajax-loader">
						<canvas id="canvas-page-authority"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="uk-width-1-3">
			<div class="white-box small-chart-box">
				<div class="single">
					<h6 class="rd-total ajax-loader">
						<big class="backlink_total">??</big>
						<cite class="backlink_avg"></cite>
					</h6>
					<p>Referring Domains</p>
					<div class="chart rd-graph ajax-loader">
						<canvas id="canvas-referring-domains"></canvas>
					</div>
				</div>
			</div>
		</div>
		@if($connectivity['ga4'] == true)
		<div class="uk-width-1-3">
			<div class="white-box small-chart-box">
				<div class="single">
					<h6 class="ga4-conversions ajax-loader">
						<big class="Google-analytics4-conversions">??</big>
						<cite class="conversions-result"></cite>
					</h6>
					<p>Conversions </p>
					<div class="chart ga4-overview-conversions ajax-loader">
						<canvas id="canvas-ga4-conversions"></canvas>	
					</div>
				</div>
			</div>
		</div>
		@else
		<div class="uk-width-1-3">
			<div class="white-box small-chart-box">
				<div class="single">
					<h6 class="goalToal ajax-loader">
						<big class="Google-analytics-goal">??</big>
						<cite class="goal_result"></cite>
					</h6>
					<p>Google Goals </p>
					<div class="chart gc-overview-organic ajax-loader">
						<canvas id="google-goal-completion-overview"></canvas>	
					</div>
				</div>
			</div>
		</div>
		@endif
		<div class="uk-width-1-3">
			<div class="white-box small-chart-box">
				<div class="single">
					<h6 class="da-stats ajax-loader">
						<big class="da_stats">??</big>
						<cite class="domainAuthority_avg"></cite>
					</h6>
					<p>Domain Authority</p>
					<div class="chart domain_authority ajax-loader">
                		<canvas id="canvas-domain-authority"></canvas>                  	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>