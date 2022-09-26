<!-- How customers search Row -->
	<div class="white-box pa-0 mb-40 white-box-handle">
		<div class="section-head">
		    <h4>How customers search for your business</h4>
		    <hr />
		    <p class="gmb_time"></p>
	  	</div>
		<div class="white-box-body">
			<div class="total-searches-box">
				<div class="elem-start ajax-loader" id="customer_search_pie_chart">
					<div id="chartjs-tooltip-text"><div><p>All Searches</p></div></div>
					<div>
						<canvas id="customers_search" height="350" width="500"></canvas>
					</div>
				</div>
				<div class="elem-end">
					<ul>
						<li>
							<figure>
								<img src="{{URL::asset('public/vendor/internal-pages/images/green-pin-icon.png')}}">
							</figure>
							<h5>Direct - <span class="direct_search">0</span> searches</h5>
							<p>Customers who find your listing searching for your business name or address.</p>
						</li>
						<li>
							<figure>
								<img src="{{URL::asset('public/vendor/internal-pages/images/blue-search-icon.png')}}">
							</figure>
							<h5>Discovery - <span class="discovery_search">0</span> searches</h5>
							<p>Customers who find your listing searching for a category, product, or service.</p>
						</li>
						<li>
							<figure>
								<img src="{{URL::asset('public/vendor/internal-pages/images/yellow-start-icon.png')}}">
							</figure>
							<h5>Branded - <span class="branded_search">0</span> searches</h5>
							<p>Customers who find your listing searching for a brand related to your business.</p>
						</li>
					</ul>
				</div>
			</div>
		</div>

	</div>
	<!-- How customers search Row End -->