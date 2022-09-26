@extends('layouts.admin_internal_pages')
@section('content')
<div class="project-stats">
	<div uk-grid class="mb-40">
		<!-- <div class="uk-width-1-4@l uk-width-1-4@s">
			<div class="white-box small-chart-box style2">
				<div class="small-chart-box-head">
					<figure class="ajax-loader">
						<img src="{{URL::asset('public/vendor/internal-pages/images/total-keywords-icon.png')}}">
					</figure>
					<h6 class="ajax-loader"><big>{{$total_accounts}}</big> Total Accounts<span
						uk-tooltip="title: Total Accounts; pos: top-left"
						class="fa fa-info-circle"></span>
					</h6>
				</div>
			</div>
		</div>

		<div class="uk-width-1-4@l uk-width-1-4@s">
			<div class="white-box small-chart-box style2">
				<div class="small-chart-box-head">
					<figure class="ajax-loader">
						<img src="{{URL::asset('public/vendor/internal-pages/images/total-projects-icon.png')}}">
					</figure>
					<h6 class="ajax-loader">
						<big>{{$total_keywords}}</big> Total Keywords<span
						uk-tooltip="title: Total Keywords; pos: top-left"
						class="fa fa-info-circle"></span>
					</h6>
				</div>
			</div>
		</div>

		<div class="uk-width-1-4@l uk-width-1-4@s">
			<div class="white-box small-chart-box style2">
				<div class="small-chart-box-head">
					<figure class="ajax-loader">
						<img src="{{URL::asset('public/vendor/internal-pages/images/freelancer-icon.png')}}">
					</figure>
					<h6 class="ajax-loader"><big>{{$total_projects}}</big> Total Projects<span
						uk-tooltip="title:Total Projects; pos: top-left"
						class="fa fa-info-circle"></span>
					</h6>
				</div>
			</div>
		</div>

		<div class="uk-width-1-4@l uk-width-1-4@s">
			<div class="white-box small-chart-box style2">
				<div class="small-chart-box-head">
					<figure class="ajax-loader">
						<img src="{{URL::asset('public/vendor/internal-pages/images/freelancer-icon.png')}}">
					</figure>
					<h6 class="ajax-loader"><big>{{'$'.$monthly_amount}}</big> Monthly Subscription Amount<span
						uk-tooltip="title:Subscription; pos: top-left"
						class="fa fa-info-circle"></span>
					</h6>
				</div>
			</div>
		</div> -->

		<div class="uk-width-1-4@m uk-width-1-3@s uk-width-1-2">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class="dashboard-top-three">{{$active_accounts}}<small>/{{$total_accounts}}</small></h6>
                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/total-keywords-icon.png')}}"> Total Active Accounts</p>
               </div>
            </div>
         </div>

         <div class="uk-width-1-4@m uk-width-1-3@s uk-width-1-2">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class="dashboard-top-three ">{{$total_keywords}}</h6>
                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/total-keywords-icon.png')}}"> Active Keywords</p>
               </div>
            </div>
         </div>

         <div class="uk-width-1-4@m uk-width-1-3@s uk-width-1-2">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class="dashboard-top-three ">{{$total_projects}}</h6>
                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/total-projects-icon.png')}}"> Active Projects</p>
               </div>
            </div>
         </div>

         <div class="uk-width-1-4@m uk-width-1-3@s uk-width-1-2">
            <div class="white-box ex-small-chart-box">
               <div class="ex-small-chart-box-head">
                  <h6 class="dashboard-top-three green">{{'$'.$monthly_amount}}</h6>
                  <p><img src="{{URL::asset('public/vendor/internal-pages/images/freelancer-icon.png')}}"> Monthly Subscription Amount</p>
               </div>
               <div class="ex-small-chart-box-foot">
                  <p>{{'('.date('F d,Y', strtotime('- 30 days')).' to '.date('F d,Y').')'}}</p>
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
						<h2>Accounts
							<span uk-tooltip="title: Accounts; pos: top-left"
							class="fa fa-info-circle"></span></h2>
						</div>
					</div>
				</div>
				<div class="white-box-body">
					<div class="project-table-cover">
						<div class="project-table-head">
							<div class="project-entries ajax-loader">
								<label>Show
									<select id="admin_dashboard_accounts">
										<option value="20">20</option>
										<option value="50" selected>50</option>
										<option value="100">100</option>
									</select>
								entries</label>
							</div>

							<div class="project-search ajax-loader style2">
								<div id="filter-search-form" class="filter-search-form">
									<input type="text" placeholder="Search..." class="account_search" id="account_search_id" autocomplete="off">
									<div class="refresh-search-icon" id="admin-refresh-search">
										<span uk-icon="refresh"></span>
									</div>
									<a href="javascript:;" class="admin-dashboard-search-clear"><span class="clear-input AdminDashboardClear" uk-icon="icon: close;"></span></a> 
									<button type="submit"><span uk-icon="icon: search"></span></button>
								</div>
							</div>

						</div>
						<div class="project-table-body">
							<table id="account-list">
								<thead>
									<tr>
										<th class="ajax-loader">
											Status
										</th>
										<th class="ajax-loader">
											ID
										</th>
										<th class="ajax-loader">
											Email
										</th>
										<th class="ajax-loader">
											Agency
										</th>
										<th class="ajax-loader">
											Projects
										</th>
										<th class="ajax-loader">
											Keywords
										</th>
										<th class="ajax-loader">
											Package
										</th>
										<th class="ajax-loader">
											Last login
										</th>
										<th class="ajax-loader">
											Account Created
										</th>
										<th class="ajax-loader">
											Actions
										</th>
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
									</tr>
									@endfor
								</tbody>
							</table>
						</div>
						<input type="hidden" id="dashboard_hidden_page" value="1" />
						<input type="hidden" id="dashboard_limit" value="50" />

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