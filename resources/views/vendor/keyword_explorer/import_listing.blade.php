<div class="project-detail-body">
	<div class="keywords-page">
		<div class="white-box keywordSearchDetail">
			<div class="keywordSearch-inner">
				<div class="list-result">
					<div class="uk-flex">
						<h3><span uk-icon="download"></span> Imported keywords</h3>
						<div class="right">
							<nav class="btn-group">
								<div class="uk-inline">
									<a class="search_keywords"><button class="btn" type="button"><i class="fa fa-search"></i> New Search</button></a>
								</div>
								<a href="javascript:;" class="btn icon-btn color-blue show-import" uk-tooltip="title: Import keywords; pos: top-center" title="" aria-expanded="false" uk-toggle="target: #offcanvas-import">
									<span uk-icon="download"></span>
								</a>
								<a href="javascript:;" class="btn icon-btn color-orange show-list" uk-tooltip="title: Lists; pos: top-center" title="" aria-expanded="false" uk-toggle="target: #offcanvas-Lists">
									<span uk-icon="list"></span>
								</a>
								<a href="javascript:;" class="btn icon-btn color-red show-history" uk-tooltip="title: History; pos: top-center" title="" aria-expanded="false" uk-toggle="target: #offcanvas-History" >
									<span uk-icon="history"></span>
								</a>
							</nav>
						</div>
					</div>
					<div class="list-boxes">
						<div uk-grid>
							<div class="uk-width-1-3@m">
								<div class="single">
									<h5 class="ajax-loader kw-stats" id="search_sum">0</h5>
									<p>Search sum</p>
								</div>
							</div>
							<div class="uk-width-1-3@m">
								<div class="single">
									<h5 class="ajax-loader kw-stats" id="avg_top_bid_high">0</h5>
									<p>Avg. Top page bid(High)</p>
								</div>
							</div>
							<div class="uk-width-1-3@m">
								<div class="single">
									<h5 class="ajax-loader kw-stats" id="avg_ci">0</h5>
									<p>Avg. CI</p>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="project-table-cover keywordsTable">
					<div class="project-table-body">
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
							<tbody class="ke-table-data">
								@for($i=1; $i<=5; $i++)
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
					</div>			
					<div class="star-tooltip"></div>
					<div class="bar-canvas-popup"></div>
					<div class="tableFooter">
						<div class="left">
							<strong>
								<span class="import-selected-keyword-count">0 /</span>
								<span class="import-show-total-count">0</span>
							</strong>
						</div>
						<div class="right">
							<input type="hidden" class="total-keyword-ideas">
							<input type="hidden" class="keyword-list-id">
							<input type="hidden" class="hidden_column_name" value="sv">
							<input type="hidden" class="hidden_sort_type" value="desc">

							<input type="hidden" id="row" value="0">
							<input type="hidden" id="total-count" value="0">
							<input type="hidden" id="scroll-status" value="in-progress">
							<input type="hidden" id="scroll-counter" value="1">
							<input type="hidden" id="imported-keyword-id" value="">
							
							<a href="javascript:void(0)" class="btn disabled"  id="add_to_list" uk-toggle="target: #add-keywords-list" data-type="import"><i class="fa fa-star"></i> Add to list</a>
							<a href="javascript:void(0)" class="btn disabled" id="export_imported_keyword_ideas" data-type="import"><i class="fa fa-download"></i> Export</a>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

