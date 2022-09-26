@extends('layouts.vendor_internal_pages')
@section('content')

<div class="project-stats">

	<div uk-grid class="smallChartBox mt-0" id="dashboard-project-stats">
         <div class="uk-width-1-6@m uk-width-1-3@s uk-width-1-2">
         	<span class="dashboard-project-stats-span"></span>
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class=" dashboard-keywords-up ajax-loader">0</h6>
                  <div class="loader h-33 "></div>
                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/keywords-up-img.png')}}"> Keywords Up</p>
				  <button type="button" class="showMainChartBox"><span uk-icon="icon: arrow-up" uk-tooltip="title: Back; pos: top-left"></span></button>
               </div>
               <div class="ex-small-chart-box-foot">
                  <p class="dashboard-top-all-since ajax-loader">since start</p>
               </div>
            </div>
         </div>
         <div class="uk-width-1-6@m uk-width-1-3@s uk-width-1-2">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class=" dashboard-top-three ajax-loader">0/0</h6>
                  <div class="loader h-33 "></div>
                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 3</p>
               </div>
               <div class="ex-small-chart-box-foot">
                  <p class="dashboard-top-three-since ajax-loader"><strong>0</strong> since start</p>
               </div>
            </div>
         </div>
         <div class="uk-width-1-6@m uk-width-1-3@s uk-width-1-2">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class=" dashboard-top-ten ajax-loader">0/0</h6>
                  <div class="loader h-33 "></div>
                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 10</p>
               </div>
               <div class="ex-small-chart-box-foot">
                  <p class="dashboard-top-ten-since ajax-loader"><strong>0</strong> since start</p>
               </div>
            </div>
         </div>
         <div class="uk-width-1-6@m uk-width-1-3@s uk-width-1-2">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class=" dashboard-top-twenty ajax-loader">0/0</h6>
                  <div class="loader h-33 ajax-loader"></div>
                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 20</p>
               </div>
               <div class="ex-small-chart-box-foot">
                  <p class="dashboard-top-twenty-since ajax-loader"><i class="icon ion-arrow-up-a"></i><strong>0</strong> since start</p>
               </div>
            </div>
         </div>
         <div class="uk-width-1-6@m uk-width-1-3@s uk-width-1-2">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class=" dashboard-top-thirty ajax-loader">0/0</h6>
                  <div class="loader h-33 "></div>
                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 30</p>
               </div>
               <div class="ex-small-chart-box-foot">
                  <p class="dashboard-top-thirty-since ajax-loader"><strong>0</strong> since start</p>
               </div>
            </div>
         </div>
         <div class="uk-width-1-6@m uk-width-1-3@s uk-width-1-2">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class=" dashboard-top-hundred ajax-loader">0/0</h6>
                  <div class="loader h-33 "></div>
                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/users-img.png')}}"> In Top 100</p>
               </div>
               <div class="ex-small-chart-box-foot">
                  <p class="dashboard-top-hundred-since ajax-loader"><strong>0</strong> since start</p>
               </div>
            </div>
         </div>
    </div>

	<div uk-grid class="mb-40 mainChartBox mt-0 showMe">
		<div class="uk-width-1-3@l uk-width-1-3@s ">
			<div class="white-box small-chart-box style2">
				<div class="small-chart-box-head">
					<figure class="ajax-loader">
						<img src="{{URL::asset('public/vendor/internal-pages/images/total-keywords-icon.png')}}">
					</figure>
					<h6 class="ajax-loader"><big class="dashboard-keyword-detail">0<span>/0</span></big> Total Keywords<span
						uk-tooltip="title: Total number of keywords available in your package; pos: top-left"
						class="fa fa-info-circle"></span></h6>

						<button type="button" class="showOtherChart"><span uk-icon="icon: arrow-right" uk-tooltip="title: Live Tracking Summary; pos: top-left"></span></button>
				</div>
			</div>
		</div>

		<div class="uk-width-1-3@l uk-width-1-3@s ">
			<div class="white-box small-chart-box style2">
				<div class="small-chart-box-head">
					<figure class="ajax-loader">
						<img src="{{URL::asset('public/vendor/internal-pages/images/total-projects-icon.png')}}">
					</figure>
					<h6 class="ajax-loader">
						<big class="dashboard-project-detail">0<span>/0</span></big> Total Projects<span
						uk-tooltip="title: Total number of projects available in your package; pos: top-left"
						class="fa fa-info-circle"></span>
					</h6>
				</div>
			</div>
		</div>

		<div class="uk-width-1-3@l uk-width-1-3@s ">
			<div class="white-box small-chart-box style2">
				<div class="small-chart-box-head">
					<figure class="ajax-loader">
						<img src="{{URL::asset('public/vendor/internal-pages/images/freelancer-icon.png')}}">
					</figure>
					<h6 class="ajax-loader"><big class="dashboard-project-name">Subscription</big> Subscription<span
						uk-tooltip="title: You can upgrade/downgrade your subscription from billing section in settings.; pos: top-left"
						class="fa fa-info-circle"></span></h6>
				</div>
			</div>
		</div>

	</div>
</div>

<div class="white-box pa-0 mb-40">
	<div class="white-box-head">
		<div class="left">
			<div class="heading ajax-loader">
				<img src="{{URL::asset('public/vendor/internal-pages/images/active-campaigns-icon.png')}}">
				<h2>Active Campaigns
					<span uk-tooltip="title:This section shows total number of active campaigns for which we are gathering data on daily basis.; pos: top-left"
					class="fa fa-info-circle"></span></h2>
			</div>
		</div>
		<div class="right">
			<div class="btn-group ajax-loader">
				@if($role != 4)
				<!-- archiving projects -->
				<a href="javascript:;" class="btn icon-btn color-blue archive_projects" uk-tooltip="title: Archive Campaigns; pos: top-center">
					<img src="{{URL::asset('public/vendor/internal-pages/images/archive-icon.png')}}">
				</a>
				@endif
			</div>
		</div>
	</div>
	<div class="white-box-body">
		<div class="project-table-cover">
			<div class="project-table-head">
				<div class="project-entries ajax-loader">
					<label>Show
						<select class="CampaignsToList">
							<!-- <option value="10">10</option> -->
							<option value="20">20</option>
							<option value="50" selected>50</option>
							<option value="100">100</option>
						</select>
					entries</label>
				</div>
				<!-- <div id="output" ></div>
				<input class="check_time_input"> -->
				<div class="project-search ajax-loader style2">
					<div id="filter-search-form" class="filter-search-form">
						<span class="selected-filter-text">project:</span>
						<input type="text" placeholder="Search ..." class="campaign_search" id="campaign_search_id" autocomplete="off">
						<div class="refresh-search-icon" id="refresh-dashboard-search">
							<span uk-icon="refresh"></span>
						</div>
						<a href="javascript:;" class="dashboard-search-clear"><span class="clear-input ActiveCampaignsClear" uk-icon="icon: close;"></span></a> 
						<button type="submit"><span uk-icon="icon: search"></span></button>
						<div class="search-filter">
							<p>Suggested Filters</p>
							<ul>
								@if(Auth::user()->role_id == 2)
								<li class="search-filter-list"><span>project:</span> search by project name</li>
								<li class="search-filter-list"><span>client:</span> search by client name</li>
								<li class="search-filter-list"><span>manager:</span> search by manager name</li>
								<li class="search-filter-list"><span>tags:</span> search by tag</li>
								@endif
								@if(Auth::user()->role_id == 3)
								<li class="search-filter-list"><span>project:</span> search by project name</li>
								<li class="search-filter-list"><span>client:</span> search by client name</li>
								<li class="search-filter-list"><span>tags:</span> search by tag</li>
								@endif
								@if(Auth::user()->role_id == 4)
								<li class="search-filter-list"><span>project:</span> search by project name</li>
								@endif
							</ul>
						</div>
					</div>
				</div>

			</div>
			<div class="project-table-body">
				<table id="campaign-list">
					<thead>
						<tr>
							<th class="sorting ajax-loader" data-sorting_type="asc" data-column_name="domain_name">
								<span uk-icon="arrow-up"></span>
								<span uk-icon="arrow-down"></span>
								Project Name
							</th>
							<th class="sorting ajax-loader" data-sorting_type="asc" data-column_name="domain_register">
								<span uk-icon="arrow-up"></span>
								<span uk-icon="arrow-down"></span>
								Date Added
							</th>
							<th class="ajax-loader">	
								Integration	
							</th>
							<th class="sorting ajax-loader" data-sorting_type="asc" data-column_name="searcher">
								<span uk-icon="arrow-up"></span>
								<span uk-icon="arrow-down"></span>
								Searcher
							</th>
							<th class="sorting ajax-loader" data-sorting_type="asc" data-column_name="country">
								<span uk-icon="arrow-up" ></span>
								<span uk-icon="arrow-down" ></span>
								Country
							</th>
							<th class="sorting ajax-loader" data-sorting_type="asc" data-column_name="keywords">
								<span uk-icon="arrow-up" ></span>
								<span uk-icon="arrow-down" ></span>
								Keywords
							</th>
							<th class="sorting ajax-loader" data-sorting_type="asc" data-column_name="top3">
								<span uk-icon="arrow-up" ></span>
								<span uk-icon="arrow-down" ></span>
								Top 3
							</th>
							<th class="sorting ajax-loader" data-sorting_type="asc" data-column_name="top10">
								<span uk-icon="arrow-up" ></span>
								<span uk-icon="arrow-down"></span>
								Top 10
							</th>
							<th class="sorting ajax-loader" data-sorting_type="asc" data-column_name="top20">
								<span uk-icon="arrow-up" ></span>
								<span uk-icon="arrow-down"></span>
								Top 20
							</th>
							<th class="sorting ajax-loader" data-sorting_type="asc" data-column_name="top100">
								<span uk-icon="arrow-up" ></span>
								<span uk-icon="arrow-down" ></span>
								Top 100
							</th>
							<th class="sorting ajax-loader" data-sorting_type="asc" data-column_name="backlinks">
								<span uk-icon="arrow-up" ></span>
								<span uk-icon="arrow-down" ></span>
								Backlinks
							</th>
							@if($role != 4)
							<th class="ajax-loader">
								Actions
							</th>
							
							
							<th class="ajax-loader">
								<input class="uk-checkbox" type="checkbox" id="checkAll">
							</th>
							@endif
						</tr>
					</thead>
					<tbody>
						@for($i=0;$i<=5;$i++)
						<tr>
							<td class="ajax-loader">..</td>
							<td class="ajax-loader">..</td>
							<td class="ajax-loader">..</td>
							<td class="ajax-loader">..</td>
							<td class="ajax-loader">..</td>
							<td class="ajax-loader">..</td>
							<td class="ajax-loader">..</td>
							<td class="ajax-loader">..</td>
							<td class="ajax-loader">..</td>
							<td class="ajax-loader">..</td>
							<td class="ajax-loader">..</td>
							<td class="ajax-loader">..</td>
							<td class="ajax-loader">..</td>
						</tr>
					@endfor
					</tbody>
				</table>
			</div>
			<input type="hidden" name="hidden_page" id="hidden_page" value="1" />
			<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="domain_name" />
			<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
			<input type="hidden" name="limit" id="limit" value="50" />

			<div class="project-table-foot">
				<div class="project-entries ajax-loader"><p>....</p></div>
				<div class="pagination ajax-loader">
					<ul>
						<li>..</li>
						<li>..</li>
						<li>..</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection