@extends('layouts.vendor_internal_pages')
@section('content')
<div class="tabs">
    <div class="loader h-27 half" style="margin-bottom: 15px"></div>
    <ul class="breadcrumb-list">
        <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i aria-hidden="true" class="fa fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="{{url('/campaign-detail/'.$campaign_id)}}">{{$project_detail->host_url}}</a></li>
        <li class="uk-active breadcrumb-item">create activity</li>
    </ul>

</div>
<input type="hidden" name="campaign_id" class="campaign_id" value="{{ $campaign_id }}" >
<!-- Activity Section -->
<div class="white-box pa-0 mb-40">
    <div class="white-box-head">
        <div class="progress-loader create-activity-loader"></div>
        <div class="left">
            <div class="heading">
                <img src="{{URL::asset('public/vendor/internal-pages/images/activity-img.png')}}">
                <h2>Project Activity
                    <span uk-tooltip="title:  It shows a list of activities (or tasks) performed under the campaign. Click and add more. ; pos: top-left" class="fa fa-info-circle"></span></h2>
                </div>
            </div>
            <div class="right">
                <a class="btn icon-btn color-red"  data-href="{{url('/campaign-detail/'.$campaign_id)}}" id="close-activity" onclick="setTimeout(function(){var ww = window.open(window.location, '_self'); ww.close(); }, 200);">
                    <span class="fa fa-times"></span>
                </a>
            </div>
        </div>
        <div class="white-box-body">
            <div class="activity-tab-section">
                <div class="white-box-tab-head mb-20">
                    <ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: .activityTabContent">
                        @foreach($taskCetegory as $key=>$value)
                        <li><a href="#">{{ $value->name }}</a></li>
                        @endforeach
                    </ul>

                </div>
                <div class="white-box-body pa-0">
                    <div class="activityTabContentHead">
                        <article>
                            <form method="get" class="activitiesLoad" id="activitiesLoad">
                                <div class="act-ques">
                                    <p><strong>Activity</strong></p>
                                </div>

                                <div class="act-status">
                                    <p><strong>Status</strong></p>
                                </div>

                                <div class="act-file-link ">
                                    <p><strong>Progress</strong></p>
                                </div>

                                <div class="act-hours">
                                    <p><strong>Date</strong></p>
                                </div>

                                <div class="act-hours">
                                    <p><strong>Time Spent</strong></p>
                                </div>

                                <div class="act-note">
                                    <p><strong>Notes</strong></p>
                                </div>

                                <div class="act-submit">

                                </div>
                            </form>
                        </article>
                    </div>
                    <div class="uk-switcher activityTabContent">
                        @foreach($taskCetegory as $key=>$value)
                        <div>
                            @foreach($value->lists as $listkey=>$listing)
                            <article>
                                <form method="post" class="activitiesLoad" id="activitiesLoad" enctype="multipart/form-data">
                                    <div class="act-ques">
                                        <input type="hidden" name="campaign_id" value="{{ $campaign_id }}" >
                                        <input type="hidden" name="category_id" value="{{ $value->id }}" >
                                        <input type="hidden" name="activity_id" value="{{ $listing->id }}" >
                                        {{ $listing->name }}
                                    </div>
                                    <div class="parent-activity">
                                        <div class="single-activity">
                                            <div class="activity-actions">
                                                <a href="javascript:;" data-id="{{ $listing->id }}" data-value="{{ $listing->total_count }}" class="btn icon-btn color-red addMoreList" uk-tooltip="title: Add more ; pos: top-left"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                                @if($listing->user_id <> 0)
                                                <a href="javascript:;" data-id="{{ $listing->id }}" data-value="{{ $listing->total_count }}" class="btn icon-btn color-red deleteActivityList" uk-tooltip="title:  Delete activity list; pos: top-left"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                @endif
                                            </div>
                                            <div class="act-status">
                                                <select class="select form-control" id="activity_status" name="status[]" >
                                                    <option value="1" >Working</option>
                                                    <option value="2" >Completed</option>
                                                    <option value="3" >Already Set</option>
                                                    <option value="4" >Suggested</option>
                                                </select>
                                            </div>
                                            <div class="act-file-link" >
                                               <button type="button"  placeholder="File Link:" class="form-control file_link" id="file_link" name="file_link[]" data-pd-popup-open="addProgressPopup" data-attr="0">
                                                   <i class="fa fa-paperclip"></i>Add Progress
                                               </button>
                                               <input type="hidden" name="activityfilelinked" value="blank" id="activityfilelinked">
                                               <input type="hidden" name="activityfilelink[]" id="activityfilelink" />
                                           </div>

                                           <div class="act-hours dates" id="datepicker">
                                               <input type="text" class="form-control project_domain_register activity_date" id="activity_date" name="activity_date[]"   placeholder="YYYY-MM-DD" autocomplete="off" readonly / >
                                           </div>

                                           <div class="act-hours time" id="timepicker">
                                            <input type="number" class="form-control activity_time" id="activity_hours" name="activity_hours[]" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "2"  placeholder="HH"/ >
                                            <input type="number" class="form-control activity_time" id="activity_seconds" name="activity_seconds[]" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" 
                                            maxlength = "2" placeholder="MM"/ >
                                        </div>
                                        <div class="act-note">
                                            <textarea class="form-control activity_note" id="activity_note" name="notes[]" placeholder="Type something:" maxlength="120"></textarea>
                                        </div>

                                        <div class="img-append-section" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="act-submit">
                                    <button class="btn icon-btn color-blue"><i class="fa fa-paper-plane-o"></i></button>
                                </div>
                            </form>
                        </article>
                        @endforeach
                        <article  id="{{ preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $value->name)) }}">
                            <div class="uk-text-center mt-30">
                                <a class="btn btn-sm blue-btn"  href="javascript:;" data-id="{{ $value->id }}" data-name="{{ preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $value->name)) }}" id="addMoreActivity">
                                    <span uk-icon="plus-circle"></span> Add New Activity
                                </a>
                            </div>
                        </article>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Activity Section End -->

<div class="popup" data-pd-popup="addProgressPopup">
    <div class="popup-inner">
        <ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: .addProgress">
            <li>
                <a href="#">Add Link</a>
            </li>
            <li>
                <a href="#">Upload File</a>
            </li>
        </ul>

        <div class="uk-switcher addProgress">
            <div>
                <form method="get" class="imgLink" id="imgLink">
                    <div class="form-group">
                        <input type="text" name="links" id="links" class="form-control" placeholder="Add Link">
                        <span class="errorStyle"><p id="link_error"></p></span>
                    </div>
                    <div class="uk-text-right">
                        <input type="submit" class="btn blue-btn" value="Submit">
                    </div>
                </form>
            </div>
            <div>
                <form method="get" class="imgFile" id="imgFile">
                    <div class="form-group file-group">
                        <p id="image_upload_error">You can upload upto 5 images</p>
                        <div class="image-upload-section">
                            <?php 
                            for($i=1;$i<=5;$i++){
                                ?>
                                <label class="custom-file-label custom_label_{{$i}}">
                                    <input type="file" name="activity_image[]"  accept="image/png,image/jpg,image/jpeg" class="activity_image_{{$i}}">
                                    <div class="custom-file form-control" id="custom-div-activityImg-{{$i}}">
                                        <span uk-icon="icon:  upload"></span>
                                        <span>Choose a file or drag it here.</span>
                                        <div class="uploaded-file" id="img-add-activityImage-{{$i}}">
                                        </div>
                                    </div>
                                </label>
                                <?php 
                            }
                            ?>
                        </div>
                        <input type="hidden" class="activity-img-count" value="0">
                    </div>
                    <div class="uk-text-right" style="margin-top: 30px;">
                        <input type="button" class="btn blue-btn imgFileSubmit" value="Submit">
                    </div>
                </form>
            </div>
        </div>
        <a class="popup-close" id="activity-popup" data-pd-popup-close="addProgressPopup" href="#"></a>
    </div>
</div>
@endsection    