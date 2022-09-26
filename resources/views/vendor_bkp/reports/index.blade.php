@extends('layouts.vendor_internal_pages')
@section('content')
<div class="white-box pa-0 mb-40">
	<div class="white-box-head">
		<div class="left">
			<div class="heading">
				<img src="{{URL::asset('public/vendor/internal-pages/images/schedule-email-icon.png')}}">
				<h2>Schedule Email Reports <span uk-tooltip="title: Schedule when to send campaign reports to your clients. Select any weekday or a specific day of every month.; pos: top-left" class="fa fa-info-circle"></span></h2>
			</div>
		</div>
		<div class="right ">
			<div class="btn-group">				
				<a class="btn blue-btn add-schedule-report" href="#add-schedule-report" uk-toggle><span uk-icon="icon: plus" class="uk-icon"></span> Add Schedule Report</a>
			</div>			
		</div>
	</div>
	<div class="white-box-body">
		<div class="project-table-cover">
			<div class="project-table-head">
				<div class="project-entries">
					<label>Show
						<select id="ScheduleReport-limit">
							<option value="20" selected>20</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select>entries
					</label>
				</div> 
				
				<div class="project-search ajax-loader">
					<form>
						<input type="text" placeholder="Search..." class="schedule-report-search">
						<div class="refresh-search-icon" id="refresh-scheduleReport-search">
							<span uk-icon="refresh"></span>
						</div>
						<a href="javascript:;" class="scheduleReport-search-clear"><span class="clear-input scheduleReportClear" uk-icon="icon: close;"></span></a>
						<button type="submit"><span uk-icon="icon: search"></span></button>
					</form>
				</div>

			</div>

			<div class="project-table-body">
				<table id="schedule_report_table">
					<thead>
						<tr>
							<th class="scheduleReport_sorting ajax-loader" data-sorting_type="asc" data-column_name="host_url">
								<span uk-icon="arrow-up"></span>
								<span uk-icon="arrow-down"></span>
							Domain Name</th>
							<th class="scheduleReport_sorting ajax-loader" data-sorting_type="asc" data-column_name="clientName">
								<span uk-icon="arrow-up"></span>
								<span uk-icon="arrow-down"></span>
							Client Name</th>
							<th class="scheduleReport_sorting ajax-loader" data-sorting_type="asc" data-column_name="recipient">
								<span uk-icon="arrow-up"></span>
								<span uk-icon="arrow-down"></span>
							Recipient</th>
							<th class="ajax-loader">
							Frequency</th>
							<th class="scheduleReport_sorting ajax-loader" data-sorting_type="asc" data-column_name="last_delivery">
								<span uk-icon="arrow-up"></span>
								<span uk-icon="arrow-down"></span>
							Last Delivery</th>
							<th class="scheduleReport_sorting ajax-loader" data-sorting_type="asc" data-column_name="next_delivery">
								<span uk-icon="arrow-up"></span>
								<span uk-icon="arrow-down"></span>	
							Next Delivery</th>
							<th class="scheduleReport_sorting ajax-loader" data-sorting_type="asc" data-column_name="report_type">
									<span uk-icon="arrow-up"></span>
									<span uk-icon="arrow-down"></span>
								Report Type</th>
							<th class="scheduleReport_sorting ajax-loader" data-sorting_type="asc" data-column_name="format">
									<span uk-icon="arrow-up"></span>
									<span uk-icon="arrow-down"></span>
								Format</th>
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
							</tr>
							@endfor
						</tbody>
					</table>
				</div>

				<input type="hidden" id="page_scheduleReport" value="1" />
				<input type="hidden" id="column_name_scheduleReport" value="host_url" />
				<input type="hidden" id="sort_type_scheduleReport" value="asc" />
				<input type="hidden" id="limit_scheduleReport" value="20" />

				<div class="project-table-foot" id="schedule_report_table_foot">
					<div class="project-entries ajax-loader">
						<p>................</p>
					</div>
					<div class="pagination schedulereport ajax-loader">
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
	@endsection