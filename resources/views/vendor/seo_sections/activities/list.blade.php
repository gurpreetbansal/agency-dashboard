<?php
    $categories = (int) $categories;
?>
@if(count($taskActivity) > 0)
    @foreach($taskActivity as $akey=>$avalue)
        <tr>
            <td><input type="hidden" class="categoriesId" value="{{ $avalue->category_id }}"> {{ date('d M Y', strtotime($avalue->activity_date)) }} </td>
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
                @elseif($avalue->status == 3)
                    <button type="button" class="btn btn-sm btn-border blue-btn-border">Already Set</button>
                @else
                    <button type="button" class="btn btn-sm btn-border blue-btn-border">Suggested</button>
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
    @if($loadtime == 'first')
    <tr>
        <td colspan="8"> <input type="hidden" id="endlist" value="0"><center> No activity found</center></td>
    </tr>
    @endif
    <tr style="display: none;">
        <td colspan="8"> <input type="hidden" id="endlist" value="0"></td>
    </tr> 
@endif    