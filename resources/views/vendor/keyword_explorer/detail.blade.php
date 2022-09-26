<style type="text/css">
	div#projectdetailheader {
		display: none;
	}
</style>
<div class="keywords-page">
	<!-- <nav class="keyTabs">
		<a href="javascript:void(0)" class="search_keywords">Search</a>
		<a href="javascript:void(0)" uk-toggle="target: #offcanvas-Lists" class="show-list">Lists</a>
		<a href="javascript:void(0)" uk-toggle="target: #offcanvas-History" class="show-history">History</a>
	</nav> -->

	<div class="white-box keywordSearchDetail">
		<div class="keywordSearch-inner">
			<div class="uk-flex">
				<h3>
					Keyword Explorer
					<div class="text-tooltip ajax-loader" id="custom-tooltip-text">
						<a class="tooltip-btn display-time" href="javascript:;">
							<span uk-icon="clock"></span> .........
						</a>
						<!-- <div class="uk-custom-tooltip">
							<div class="uk-custom-tooltip-inner">
								<b>Last time this SERP was fetched.</b> You can request new SERP and updated KD by clicking the button below.
								<br>
								<a href="javascript:;" class="btn blue-btn"><span uk-icon="refresh"></span> Fetch new SERF</a>
							</div>
						</div> -->
					</div>
				</h3>
				<nav class="btn-group">
					<div class="uk-inline">
						<a class="search_keywords"><button class="btn blue-btn" type="button"><i class="fa fa-search"></i> New Search</button></a>
					</div>
					<a href="javascript:;" class="btn icon-btn color-orange show-list" uk-tooltip="title: Lists; pos: top-center" title="" aria-expanded="false" uk-toggle="target: #offcanvas-Lists">
						<span uk-icon="list"></span>
					</a>
					<a href="javascript:;" class="btn icon-btn color-red show-history" uk-tooltip="title: History; pos: top-center" title="" aria-expanded="false" uk-toggle="target: #offcanvas-History" >
						<span uk-icon="history"></span>
					</a>
				</nav>
			</div>
			<ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: .searchContainer">
				<li class="searchByKeyword">
					<a href="#searchKeyword">Search by keyword</a>
				</li>
				<li class="searchByDomain">
					<a href="#searchDomain">Search by domain</a>
				</li>
			</ul>
			<ul class="uk-switcher searchContainer">
				<li id="searchKeyword" class="ideas">
					<form class="uk-flex">
						<div class="form-group">
							<span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/keyword-seach-icon.png')}}" alt="keyword-seach-icon"></span>
							<input type="text" class="form-control detail_query_field" placeholder="Enter keyword" autocomplete="false">
						</div>
						<!-- <div class="form-group">
							<span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/keyword-location-icon.png')}}" alt="keyword-location-icon"></span>
							<select class="selectpicker" id="detail_keyword_locations" data-live-search="true"></select>
							<input type="hidden" class="detail_keyword_location_id">
						</div> -->

						<div class="form-group ">
							<span class="icon"><img src="{{asset('public/vendor/internal-pages/images/keyword-location-icon.png')}}" alt="keyword-location-icon"></span>
							<input id="OpenCustomDropdownDetailKeyword" type="text" class="form-control look-like-select keyword-locations" readonly placeholder="Anywhere" value="Anywhere">
							<div class="custom-dropdown-menu DetailKeywordDiv">
								<div class="custom-dropdown-menu-inner">
									<div class="custom-bs-searchbox">
										<input type="text" class="form-control input-search-keyword-detail" autocomplete="off" role="textbox" aria-label="Search">
										<img src="{{URL::asset('public/vendor/internal-pages/images/refresh-add-icon.gif')}}" class="refresh-search-icon refresh-icon" style="display: none;">
									</div>
									<ul class="dropdown-menu-inner" id="detail_keyword_locations"></ul>
								</div>
							</div>
							<input type="hidden" class="detail_keyword_location_id">
						</div>
						<div class="form-group">
							<span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/keyword-language-icon.png')}}" alt="keyword-language-icon"></span>
							<select class="selectpicker" id="detail_keyword_language" data-live-search="true"></select>
							<input type="hidden" class="detail_keyword_language_id">
						</div>
						<div class="form-group"><button type="button" class="btn blue-btn detail_find_keywords" data-category="1">Find keywords</button></div>
					</form>
				</li>
				<li id="searchDomain" class="ideas">
					<form class="uk-flex">
						<div class="form-group">
							<span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/keyword-seach-icon.png')}}" alt="keyword-seach-icon"></span>
							<input type="text" class="form-control detail_domain_query_field" placeholder="Enter domain or URL" autocomplete="false">
						</div>
						<!-- <div class="form-group">
							<span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/keyword-location-icon.png')}}" alt="keyword-location-icon"></span>
							<select class="selectpicker" id="detail_domain_locations" data-live-search="true"></select>
							<input type="hidden" class="detail_domain_location_id">
						</div> -->
						<div class="form-group ">
							<span class="icon"><img src="{{asset('public/vendor/internal-pages/images/keyword-location-icon.png')}}" alt="keyword-location-icon"></span>
							<input id="OpenCustomDropdownDetailDomain" type="text" class="form-control look-like-select domain-locations" readonly placeholder="Anywhere" value="Anywhere">
							<div class="custom-dropdown-menu DetailDomainDiv">
								<div class="custom-dropdown-menu-inner">
									<div class="custom-bs-searchbox">
										<input type="text" class="form-control input-search-domain-detail" autocomplete="off" role="textbox" aria-label="Search">
										<img src="{{URL::asset('public/vendor/internal-pages/images/refresh-add-icon.gif')}}" class="refresh-search-icon refresh-icon" style="display: none;">
									</div>
									<ul class="dropdown-menu-inner" id="detail_domain_locations"></ul>
								</div>
							</div>
							<input type="hidden" class="detail_domain_location_id">
						</div>
						<div class="form-group">
							<button type="button" class="btn blue-btn detail_find_keywords" data-category="2">Find keywords</button>
						</div>
					</form>
				</li>
			</ul>

			<div class="project-table-cover keywordsTable">
				<div class="project-table-body" id="project-table-body">
					<table class="remove-hover-effect" id="ke-table">
						<thead>
							<tr>
								<th>
									<input class="uk-checkbox" type="checkbox" id="selectAllIdeas">
								</th>
								<th class="kw-ideas-sorting" data-sorting_type="asc" data-field="search_term">
									<span uk-icon="arrow-up"></span>
									<span uk-icon="arrow-down"></span>
									Keywords
								</th>
								<th>
									Trend
								</th>
								<th class="kw-ideas-sorting" data-sorting_type="asc" data-field="sv">
									<span uk-icon="arrow-up" ></span>
									<span uk-icon="arrow-down" ></span>
									Search
								</th>
								<th class="kw-ideas-sorting" data-sorting_type="asc" data-field="page_bid_low">
									<span uk-icon="arrow-up" ></span>
									<span uk-icon="arrow-down" ></span>
									<font>Top of page bid <small>( low range )</small></font>
								</th>
								<th class="kw-ideas-sorting" data-sorting_type="asc" data-field="page_bid_high">
									<span uk-icon="arrow-up" ></span>
									<span uk-icon="arrow-down" ></span>
									<font>Top of page bid <small>( high range )</small></font>
								</th>
								<th class="kw-ideas-sorting" data-sorting_type="asc" data-field="competition_index">
									<span uk-icon="arrow-up" ></span>
									<span uk-icon="arrow-down"></span>
									CI
								</th>
							</tr>
						</thead>
						<tbody class="ke-table-data" id="ke-table-data">
							@for($i=1; $i<=10; $i++)
							<tr>
								<td class="ajax-loader">....</td>
								<td class="ajax-loader">....</td>
								<td class="ajax-loader">....</td>
								<td class="ajax-loader">....</td>
								<td class="ajax-loader">....</td>
								<td class="ajax-loader">....</td>
								<td class="ajax-loader">....</td>
							</tr>
							@endfor						
						</tbody>
					</table>
						<div class="animation-load" uk-spinner></div>
				</div>
				<div class="star-tooltip"></div>
				<div class="bar-canvas-popup"></div>
				<div class="tableFooter">
					<div class="left">
						<strong>
							<span class="selected-keyword-count">0 /</span>
							<span class="show-total-count">0</span>
						</strong>
					</div>
					<div class="right">
						<input type="hidden" class="total-keyword-ideas">
						<input type="hidden" class="keyword-search-id">
						<input type="hidden" class="hidden_column_name" value="sv">
						<input type="hidden" class="hidden_sort_type" value="desc">

						<input type="hidden" id="row" value="0">
						<input type="hidden" id="total-count" value="0">
						<input type="hidden" id="scroll-status" value="in-progress">
						<input type="hidden" id="scroll-counter" value="1">

						<a href="javascript:void(0)" class="btn disabled"  id="add_to_list" uk-toggle="target: #add-keywords-list"><i class="fa fa-star"></i> Add to list</a>
						<a href="javascript:void(0)" class="btn disabled" id="export_keyword_ideas" data-type="ideas"><i class="fa fa-download"></i> Export</a>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>