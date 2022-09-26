@if(isset($result) && !empty($result) && ($result->total() > 0))
@foreach($result as $key=>$value)
<tr>
	<td >
		<div class="flex">
			<h6 class="pr-20">{{$value->project_name->host_url}}</h6>
			<div class="icons-list fixed">
				<a href="#schedule-report-history" uk-toggle uk-tooltip="title:Reports History; pos: top-center" report-id="{{$value->id}}" report-format="{{$value->format}}" class="scheduled_report_history" project-name="{{$value->project_name->host_url}}"><i class="fa fa-history text-primary"></i></a>
				
				<a href="#delete-schedule-report" uk-toggle uk-tooltip="title:Remove Scheduled Report; pos: top-center" report-id="{{$value->id}}" class="remove_scheduled_report"><i class="fa fa-trash text-danger"></i></a>

				<a href="#schedule-report-edit" uk-toggle uk-tooltip="title:Edit Scheduled Report; pos: top-center" report-id="{{$value->id}}" class="schedule_report_edit"><i class="fa fa-edit text-success"></i></a>

				<a href="javascript:;" uk-tooltip="title:Send Report Now; pos: top-center" report-id="{{$value->id}}" class="send_report_now"><i class="fa fa-send text-warning"></i></a>
			</div>
		</div>
	</td> 
	<td uk-tooltip="title: {{$value->project_name->clientName}}; pos: top-left"><h6 class="pr-20">{{$value->project_name->clientName}}</h6></td>
	<td uk-tooltip="title: {{$value->email}}; pos: top-left"><div  class="table-large-content">{{$value->email}}</div></td>
	<td uk-tooltip="title: {{$value->frequency}}; pos: top-left"><div  class="table-large-content">{{$value->frequency}}</div></td>
	<td>{{$value->last_delivered}}</td>
	<td>{{date('F d, Y',strtotime($value->next_delivery))}}</td>
	<td>{{($value->report_type === 1)?'Full':'Keywords'}}</td>
	<td>{{$value->report_format}}</td>
</tr>	
@endforeach
@else
<tr><td colspan="6"><center>No Scheduled Reports</center></td></tr>
@endif