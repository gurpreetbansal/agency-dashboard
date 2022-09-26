@extends('layouts.admin_internal_pages')
@section('content')
<div class="white-box pa-0 mb-40">
	<div class="white-box-head">
		<div class="left">
			<div class="heading ajax-loader">
				<img src="{{URL::asset('public/vendor/internal-pages/images/account-details.png')}}">
				<h2>Agency Account Details
					<span uk-tooltip="title: Agency Account Details; pos: top-left" class="fa fa-info-circle"></span>
				</h2>
			</div>
		</div>
		<div class="right">
			<div class="heading ajax-loader">
				<a href="{{url()->previous()}}"><button class="btn blue-btn">Back</button></a>
			</div>
		</div>
	</div>
	<div class="white-box-body">
		<div class="project-table-cover">
			<div class="project-table-head">
				<div class="project-entries ajax-loader">
					<label>Show
						<select id="admin_agency_account_details_limit">
							<option value="20" selected>20</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select>
					entries</label>
				</div>

				<div class="project-search ajax-loader style2">
					<div id="filter-search-form" class="filter-search-form">
						<input type="text" placeholder="Search..." class="agency-account-details-list-search" id="agency_account_details_list_search" autocomplete="off">
						<div class="refresh-search-icon" id="agency-account-details-refresh-search">
							<span uk-icon="refresh"></span>
						</div>
						<a href="javascript:;" class="agency-account-details-list-clear" style="display: none;"><span class="clear-input AgencyAccountDetailsListClear" uk-icon="icon: close;"></span></a> 
						<button type="submit"><span uk-icon="icon: search"></span></button>
					</div>
				</div>

			</div>
			<div class="project-table-body">
				<table id="agency-account-details-list">
					<thead>
						<tr>
							<th class="agency_acct_sorting ajax-loader" data-sorting_type="asc" data-column_name="domain_name">
								<span uk-icon="arrow-up"></span>
								<span uk-icon="arrow-down"></span>
								Campaigns
							</th>
							<th class="agency_acct_sorting ajax-loader" data-sorting_type="asc" data-column_name="domain_register">
								<span uk-icon="arrow-up"></span>
								<span uk-icon="arrow-down"></span>
								Domain Registered on
							</th>
							<th class="ajax-loader">Manager</th>
							<th class="ajax-loader">Client</th>
							<th class="ajax-loader">Viewkey</th>
							<th class="agency_acct_sorting ajax-loader" data-sorting_type="asc" data-column_name="status">
								<span uk-icon="arrow-up"></span>
								<span uk-icon="arrow-down"></span>
								Campaign Status
							</th>
							<th class="ajax-loader">Issues in Account</th>
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
						</tr>
						@endfor
					</tbody>
				</table>
			</div>
			<input type="hidden" id="agency_account_details_hidden_page" value="1" />
			<input type="hidden" id="agency_account_details_limit" value="20" />
			<input type="hidden" id="agency_id" value="{{$agency_id}}" />
			<input type="hidden" id="hidden_agency_acct_column_name" value="created" />
			<input type="hidden" id="hidden_agency_acct_sort_type" value="desc" />

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