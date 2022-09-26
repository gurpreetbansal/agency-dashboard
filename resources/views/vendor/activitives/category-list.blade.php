@extends('layouts.vendor_internal_pages')
@section('content')

    
<!-- Activity Section -->
<input type="hidden" class="campaignID" value="{{ @$taskCetegory[0]->campaign_id }}">
<input type="hidden" class="campaign_id" value="{{ @$taskCetegory[0]->campaign_id }}">
<input type="hidden" class="tasklist_id" value="{{ @$taskCetegory[0]->activity_id }}">
<input type="hidden" class="user_id" value="{{ @$taskCetegory[0]->user_id }}">
<div class="white-box pa-0 mb-40 activity-box" id="page-activity-section">
    <div class="white-box-head">
        <span class="white-box-handle" uk-icon="icon: table"></span>
        <div class="left">
            <div class="loader h-33 half-px"></div>
            <div class="heading">
            <img src="{{URL::asset('public/vendor/internal-pages/images/activity-img.png')}}">
            <h2>Project Activity
                <span uk-tooltip="title: Project Activity Here...; pos: top-left"
                class="fa fa-info-circle"></span></h2>
            </div>
        </div>
    </div>

    <div class="white-box-body">
        <div class="activity-tab-section">
            <div class="white-box-body pa-0">
                <div class="checkActivity">
                    <div class="table-responsive">
                        <table class="activitiesList style1" id="activityList">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Activity Name</th>
                                    <th>Activity</th>
                                    <th>Progress </th>
                                    <th>No. of Hours </th>
                                    <th>Note</th>
                                    <th>Status </th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody> 
                                @if($taskCetegory->total() > 0)
                                @foreach($taskCetegory as $akey => $avalue)
                                    <tr>
                                        <td><input type="hidden" class="categoriesId" value="{{ $avalue->category_id }}"> {{ date('d M Y', strtotime($avalue->created_at)) }} </td>
                                        <td>{{ $avalue->categoriesLists->name }}</td>
                                        <td>{{ $avalue->activityLists->name }}</td>
                                        <td>
                                            @if($avalue->file_link <> null && $avalue->file_link <> '')
                                                <a target="_blank" href="{{ $avalue->file_link }}" data-id="{{ $avalue->id }}" class="check_link">Go to link</a>
                                            @else
                                                <a href="#" data-pd-popup-open="checkProgress" data-id="{{ $avalue->id }}" class="check_progress">Check Progress</a>
                                            @endif
                                        </td>
                                        <td>{{ date('H',strtotime($avalue->time_taken)). ' hours '. date('i',strtotime($avalue->time_taken)). ' Minutes' }} </td>
                                        <td> {{ $avalue->notes }} </td>
                                        <td>
                                            @if($avalue->status == 1)
                                                <button type="button" class="btn btn-sm btn-border yellow-btn-border">Working</button>
                                            @elseif($avalue->status == 2)
                                                <button type="button" class="btn btn-sm btn-border green-btn-border">Completed</button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-border blue-btn-border">Already Set</button>
                                            @endif
                                        </td> 
                                        <td class="action">
                                            <div class="btn-group"> 
                                                <a href="javascript:;" data-id="{{ $avalue->id }}"  class="btn small-btn icon-btn color-red delete_activities" uk-tooltip="title:Delete Activity; pos: top-center" title="" aria-expanded="false">
                                                    <img src="{{ url('public/vendor/internal-pages/images/delete-icon-small.png') }}" class="mCS_img_loaded">
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8"><center>No activity found</center></td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="activitylimit" value="10">
<input type="hidden" id="activitypage" value="2">
<input type="hidden" id="activitydate" value="{{ date('Y-m') }}">

<div class="popup" id="activityprogress" data-pd-popup="checkProgress">
    
</div>

@endsection