@extends('layouts.admin_internal_pages')
@section('content')
<div class="white-box pa-0 mb-40">
	<div class="white-box-head">
		<div class="left">
			<div class="heading ajax-loader">
				<img src="{{URL::asset('public/vendor/internal-pages/images/active-campaigns-icon.png')}}">
				<h2>Audit List
					<span uk-tooltip="title: Audit List; pos: top-left"
					class="fa fa-info-circle"></span></h2>
				</div>
			</div>

			<div class="right">
				<div class="heading ajax-loader">
					<a href="{{url('/admin/site-audit/create')}}"><button class="btn blue-btn">Add New</button></a>
				</div>
			</div>
		</div>
		<div class="white-box-body">
			<div class="project-table-cover">
				<div class="project-table-head">
					<div class="project-entries ajax-loader">
						<label>Show
							<select id="admin_audit_list">
								<option value="20" selected>20</option>
								<option value="50">50</option>
								<option value="100">100</option>
							</select>
						entries</label>
					</div>

					<div class="project-search ajax-loader style2">
						<div id="filter-search-form" class="filter-search-form">
							<input type="text" placeholder="Search..." class="audit-list-search" id="audit_list_search" autocomplete="off">
							<div class="refresh-search-icon" id="admin-audit-refresh-search">
								<span uk-icon="refresh"></span>
							</div>
							<a href="javascript:;" class="admin-audit-list-clear" style="display: none;"><span class="clear-input AuditListClear" uk-icon="icon: close;"></span></a> 
							<button type="submit"><span uk-icon="icon: search"></span></button>
						</div>
					</div>

				</div>
				<div class="project-table-body">
					<table id="audit-error-list">
						<thead>
							<tr>
								<th class="ajax-loader">
									Category
								</th>
								<th class="ajax-loader">
									Error Key
								</th>
								<th class="ajax-loader">
									Error Label
								</th>
								<th class="ajax-loader">
									Action
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
							</tr>
							@endfor
						</tbody>
					</table>
				</div>
				<input type="hidden" id="audit_hidden_page" value="1" />
				<input type="hidden" id="audit_limit" value="20" />

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