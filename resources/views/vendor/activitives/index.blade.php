<!-- Activity Section -->
<div class="white-box pa-0 mb-40">
    <div class="white-box-head">
        <div class="left">
            <div class="heading">
                <img src="{{URL::asset('public/vendor/internal-pages/images/activity-img.png')}}">
                <h2>Project Activity
                    <span uk-tooltip="title: Project Activity Here...; pos: top-left"
                    class="fa fa-info-circle"></span></h2>
            </div>
        </div>
        <div class="right">
            <a class="btn icon-btn color-red"  href="javascript:;" id="close-activity">
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
                                    @if($listing->user_id <> 0)
                                        <a href="javascript:;" data-id="{{ $listing->id }}" data-value="{{ $listing->total_count }}" class="btn icon-btn color-red deleteActivityList"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                    @endif
                                </div>
                                <div class="act-status">
                                    <select class="select form-control" id="activity_status" name="status" >
                                        <option value="1" >Working</option>
                                        <option value="2" >Completed</option>
                                        <option value="3" >Already Set</option>
                                    </select>
                                </div>
                                <div class="act-file-link" >
                                     <button type="button"  placeholder="File Link:" class="form-control file_link" id="file_link" name="file_link" data-pd-popup-open="addProgressPopup">
                                         <i class="fa fa-paperclip"></i>Add Progress
                                     </button>
                                     <input type="hidden" name="activityfilelinked" value="blank" id="activityfilelinked">
                                     <input type="hidden" name="activityfilelink" id="activityfilelink" />
                                </div>
                                <div class="act-hours time" id="timepicker">
                                    <input type="number" class="form-control activity_time" id="activity_hours" name="activity_hours" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "2"  placeholder="HH"/ >
                                    <input type="number" class="form-control activity_time" id="activity_seconds" name="activity_seconds" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" 
                                    maxlength = "2" placeholder="MM"/ >
                                </div>
                                <div class="act-note">
                                    <textarea class="form-control" id="activity_note" name="notes" placeholder="Type something:" ></textarea>
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
                        </div>
                        <div class="uk-text-right">
                        <input type="submit" class="btn blue-btn" value="Submit">
                        </div>
                    </form>
                </div>
                <div>
                    <form method="get" class="imgFile" id="imgFile">
                        <div class="form-group file-group">
                            <span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/attach-icon.png')}}"></span>
                            <label class="custom-file-label">
                                <input type="file" name="files" accept="image/png,image/jpg,image/jpeg" id="img_filess">
                                <div class="custom-file form-control">
                                    <span uk-icon="icon:  upload"></span>
                                    <span id="fileName" class="fileName">Progress</span>
                                    <span>Choose a file or drag it here.</span>
                                    <div class="uploaded-file" >
                                        <img id="progress_preview_container"  alt="profile-img" >
                                    </div>
                                </div>
                                <!-- <div class="custom-file form-control">
                                    <span>Upload File</span>
                                </div> -->
                            </label>
                        </div>
                        <div class="uk-text-right" style="margin-top: 30px;">
                            <input type="submit" class="btn blue-btn" value="Submit">
                        </div>
                    </form>
                </div>
            </div>


            <a class="popup-close" id="activity-popup" data-pd-popup-close="addProgressPopup" href="#"></a>
        </div>
    </div>