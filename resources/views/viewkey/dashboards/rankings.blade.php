<div class="main-data-view" id="rankings" uk-sortable="handle:.white-box-handle">
	<!--Live Keyword Tracking Row -->
	<div class="white-box pa-0 mb-40">
		<div class="white-box-head">
			<!-- <span class="white-box-handle" uk-icon="icon: table"></span> -->
			<div class="left">
				<div class="heading ajax-loader">
					<img src="{{URL::asset('public/vendor/internal-pages/images/live-keyword-tracking-img.png')}}">
					<div>
						<h2>Live Keyword Tracking <span
							uk-tooltip="title: This section actively track your money keywords on day to day basis.; pos: top-left" class="fa fa-info-circle"></span></h2>
							<p class="keyword_time"></p>
							<div class="refresh-progress hidden">
								<p><span id="start">0/</span><span id="total_keywords">0</span><input type="hidden" class="total_keywords_val" value="">
									<span> Keywords</span>
								</p>
								<progress id="js-progressbar" class="uk-progress" value="10" max="100"></progress>
							</div>
						</div>
					</div>
				</div>
				
			</div>
			<div class="white-box-body">
				<div uk-grid class="mb-40">
					<div class="uk-width-1-6@m uk-width-1-3@s">
						<div class="white-box ex-small-chart-box">
							<div class="ex-small-chart-box-head">
								<h6 class=" keywords_up ajax-loader">0</h6>
								<p class="ajax-loader"><img src="{{URL::asset('public/vendor/internal-pages/images/keywords-up-img.png')}}"> Keywords Up</p>
							</div>
							<div class="ex-small-chart-box-foot">
								<p class="top-all-since ajax-loader">since start</p>
							</div>
						</div>
					</div>
					<div class="uk-width-1-6@m uk-width-1-3@s">
						<div class="white-box ex-small-chart-box">
							<div class="ex-small-chart-box-head">
								<h6 class=" top-three ajax-loader">0/0</h6>
								<p class="ajax-loader"><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 3</p>
							</div>
							<div class="ex-small-chart-box-foot">
								<p class="top-three-since ajax-loader"><strong>0</strong> since start</p>
							</div>
						</div>
					</div>
					<div class="uk-width-1-6@m uk-width-1-3@s">
						<div class="white-box ex-small-chart-box">
							<div class="ex-small-chart-box-head">
								<h6 class=" top-ten ajax-loader">0/0</h6>
								<p class="ajax-loader"><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 10</p>
							</div>
							<div class="ex-small-chart-box-foot">
								<p class="top-ten-since ajax-loader"><strong>0</strong> since start</p>
							</div>
						</div>
					</div>
					<div class="uk-width-1-6@m uk-width-1-3@s">
						<div class="white-box ex-small-chart-box">
							<div class="ex-small-chart-box-head">
								<h6 class=" top-twenty ajax-loader">0/0</h6>
								<p class="ajax-loader"><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 20</p>
							</div>
							<div class="ex-small-chart-box-foot">
								<p class="top-twenty-since ajax-loader"><i class="icon ion-arrow-up-a"></i><strong>0</strong> since start</p>
							</div>
						</div>
					</div>
					<div class="uk-width-1-6@m uk-width-1-3@s">
						<div class="white-box ex-small-chart-box">
							<div class="ex-small-chart-box-head">
								<h6 class=" top-thirty ajax-loader">0/0</h6>
								<p class="ajax-loader"><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 30</p>
							</div>
							<div class="ex-small-chart-box-foot">
								<p class="top-thirty-since ajax-loader"><strong>0</strong> since start</p>
							</div>
						</div>
					</div>
					<div class="uk-width-1-6@m uk-width-1-3@s">
						<div class="white-box ex-small-chart-box">
							<div class="ex-small-chart-box-head">
								<h6 class=" top-hundred ajax-loader">0/0</h6>
								<p class="ajax-loader"><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 100</p>
							</div>
							<div class="ex-small-chart-box-foot">
								<p class="top-hundred-since ajax-loader"><strong>0</strong> since start</p>
							</div>
						</div>
					</div>
				</div>
				<div class="project-table-cover">
					<div class="sticky-table">
						<div class="project-table-head">
							<div class="project-entries ajax-loader">
								<label>
									Show
									<select id="live-keyword-limit">
										<option>20</option>
										<option>50</option>
										<option>100</option>
										<option>All</option>
									</select>
									entries
								</label>
							</div>
							<div class="project-entries btn-group keyword-filters">
				               <a href="javascript:;" id="EditKeywordsFiltersRankings" class="btn icon-btn color-orange" uk-tooltip="title: Keyword Filters; pos: top-center">
				                  <img src="{{URL::asset('public/vendor/internal-pages/images/keyword-filter.png')}}" >
				               </a>
				            </div>
							<div class="project-search ajax-loader">
								<form>
									<input type="text" placeholder="Search..." class="live-keyword-search" onkeydown="return (event.keyCode!=13);">
									<div class="refresh-search-icon" id="refresh-liveKeyword-search">
										<span uk-icon="refresh"></span>
									</div>
									<a href="javascript:;" class="liveKeyword-search-clear"><span class="clear-input LiveKeywordClear" uk-icon="icon: close;"></span></a>
									<button type="submit" onclick="return false;"><span uk-icon="icon: search"></span></button>
								</form>
							</div>
						</div>
						<div class="project-table-body LiveKeywordTable" id="LiveKeywordTableDiv">
							<table id="LiveKeywordTable_data">
								<thead>
									<tr>
										<th class="liveKeyword_sorting ajax-loader" data-sorting_type="asc" data-column_name="keyword">
											<span uk-icon="arrow-up"></span>
											<span uk-icon="arrow-down"></span>
											Keyword
										</th>
										@if(!in_array('start_rank',$table_settings))
										<th class="liveKeyword_sorting ajax-loader" data-sorting_type="asc" data-column_name="start_ranking">
											<span uk-icon="arrow-up"></span>
											<span uk-icon="arrow-down"></span>
											Start
										</th>
										@endif
										@if(!in_array('page',$table_settings))
										<th class="liveKeyword_sorting ajax-loader" data-sorting_type="asc" data-column_name="currentPosition">
											<span uk-icon="arrow-up"></span>
											<span uk-icon="arrow-down"></span>
											<img src="{{URL::asset('public/vendor/internal-pages/images/google-icon.png')}}">
											Page
										</th>
										@endif
										@if(!in_array('google_rank',$table_settings))
										<th class="liveKeyword_sorting ajax-loader" data-sorting_type="asc" data-column_name="currentPosition">
											<span uk-icon="arrow-up"></span>
											<span uk-icon="arrow-down"></span>
											<img src="{{URL::asset('public/vendor/internal-pages/images/google-icon.png')}}">
											Rank
										</th>
										@endif
										@if(!in_array('oneday',$table_settings))
										<th class="liveKeyword_sorting ajax-loader" data-sorting_type="asc" data-column_name="oneday_position">
											<span uk-icon="arrow-up"></span>
											<span uk-icon="arrow-down"></span>
											1 Day
										</th>
										@endif
										@if(!in_array('weekly',$table_settings))
										<th class="liveKeyword_sorting ajax-loader" data-sorting_type="asc" data-column_name="one_week_ranking">
											<span uk-icon="arrow-up"></span>
											<span uk-icon="arrow-down"></span>
											7 Days
										</th>
										@endif
										@if(!in_array('monthly',$table_settings))
										<th class="liveKeyword_sorting ajax-loader" data-sorting_type="asc" data-column_name="monthly_ranking">
											<span uk-icon="arrow-up"></span>
											<span uk-icon="arrow-down"></span>
											30 Days
										</th>
										@endif
										@if(!in_array('lifetime',$table_settings))
										<th class="liveKeyword_sorting ajax-loader" data-sorting_type="asc" data-column_name="life_ranking">
											<span uk-icon="arrow-up"></span>
											<span uk-icon="arrow-down"></span>
											Life
										</th>
										@endif
										@if(!in_array('competition',$table_settings))
										<th class="liveKeyword_sorting ajax-loader" data-sorting_type="asc" data-column_name="cmp">
											<span uk-icon="arrow-up"></span>
											<span uk-icon="arrow-down"></span>
											Comp
										</th>
										@endif
										@if(!in_array('sv',$table_settings))
										<th class="liveKeyword_sorting ajax-loader" data-sorting_type="asc" data-column_name="sv">
											<span uk-icon="arrow-up"></span>
											<span uk-icon="arrow-down"></span>
											Search Vo
										</th>
										@endif
										@if(!in_array('date',$table_settings))
										<th class="liveKeyword_sorting ajax-loader" data-sorting_type="asc" data-column_name="created_at">
											<span uk-icon="arrow-up"></span>
											<span uk-icon="arrow-down"></span>
											Date Added
										</th>
										@endif
										@if(!in_array('url',$table_settings))
										<th class="liveKeyword_sorting ajax-loader" data-sorting_type="asc" data-column_name="result_url">
											<span uk-icon="arrow-up"></span>
											<span uk-icon="arrow-down"></span>
											URL
										</th>
										@endif
									</tr>
								</thead>
								<tbody>
									@for($i=1; $i<=5; $i++)
									<tr>
										<td class="ajax-loader">....</td>
										<td class="ajax-loader">....</td>
										<td class="ajax-loader">....</td>
										<td class="ajax-loader">....</td>
										<td class="ajax-loader">....</td>
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

						<input type="hidden" name="hidden_page_liveKeyword" id="hidden_page_liveKeyword" value="1" />
						<input type="hidden" name="hidden_column_name_liveKeyword" id="hidden_column_name_liveKeyword" value="currentPosition" />
						<input type="hidden" name="hidden_sort_type_liveKeyword" id="hidden_sort_type_liveKeyword" value="asc" />
						<input type="hidden" name="limit_liveKeyword" id="limit_liveKeyword" value="20" />
						<input type="hidden" id="tag_id_value" value="">
						<input type="hidden" id="selected_type_val" value="" />
            			<input type="hidden" id="tracking_type_val" value="" />

						<div class="project-table-foot liveKeywords-profile-foot" id="LiveKeywordTable_foot">
							<div class="project-entries">
								<p>................</p>
							</div>
							<div class="pagination LiveKeywords">
								<ul class="pagination" role="navigation">
									<li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
										<span class="page-link" aria-hidden="true">....</span>
									</li>
									<li class="page-item  active">
										<a class="page-link" href="javascript:;">...</a>
									</li>
									<li class="page-item ">
										<a class="page-link" href="javascript:;">...</a>
									</li>
									<li class="page-item">
										<a class="page-link" href="javascript:;" rel="next" aria-label="Next »">.....</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Live Keyword Tracking Row End-->
		<!--Organic Keyword Growth Row -->
		<div class="white-box pa-0 mb-40">
			<div class="white-box-head">
				<span class="white-box-handle" uk-icon="icon: table"></span>
				<div class="left">
					<div class="heading">
						<img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
						<h2>Organic Keyword Growth
							<span uk-tooltip="title: Organic Traffic Growth Data; pos: top-left" class="fa fa-info-circle"></span>
						</h2>
					</div>
				</div>
			</div>

			<div class="white-box-body">
				<div uk-grid>
					<div class="uk-width-1-2@l uk-width-1-1@m">
						<div id="keywords-canvasRank" class="chart h-345 ajax-loader">
							<canvas id="new-keywordsCanvasRank" width="50" height="40" > </canvas>
						</div>
					</div>
					<div class="uk-width-1-2@l uk-width-1-1@m">
						<div class="top-organic-keyword-table">
							<div class="top-organic-keyword-table-head">
								<h6 class="top-key-organic ajax-loader">Top Organic Keywords <small class="total_count"></small></h6>
								<!-- <a href="{{url('extra-organic-keywords/'.$campaign_id)}}" target="_blank" class="btn blue-btn btn-sm top-key-organic ajax-loader">View details</a> -->
								<span class="sideDashboardView">
									<a href="#extraKeywords" aria-expanded="false" class="btn blue-btn btn-sm top-key-organic">View details</a>
								</span>
							</div>
							<div class="table-responsive">
								<table class="style1" id="extra_organic_keywords">
									<thead>
										<tr>
											<th class="ajax-loader">Keyword</th>
											<th class="ajax-loader">Pos.</th>
											<th class="ajax-loader">Volume </th>
											<th class="ajax-loader">CPC (USD)</th>
											<th class="ajax-loader">Traffic %</th>
										</tr>
									</thead>
									<tbody >
										<tr>
											<td class="ajax-loader">....</td>
											<td class="ajax-loader">....</td>
											<td class="ajax-loader">....</td>
											<td class="ajax-loader">....</td>
											<td class="ajax-loader">....</td>
										</tr>
										<tr>
											<td class="ajax-loader">....</td>
											<td class="ajax-loader">....</td>
											<td class="ajax-loader">....</td>
											<td class="ajax-loader">....</td>
											<td class="ajax-loader">....</td>
										</tr>
										<tr>
											<td class="ajax-loader">....</td>
											<td class="ajax-loader">....</td>
											<td class="ajax-loader">....</td>
											<td class="ajax-loader">....</td>
											<td class="ajax-loader">....</td>
										</tr>

									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--Organic Keyword Growth Row End -->


		
	</div>  