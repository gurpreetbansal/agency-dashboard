<form method="get" class="addNewLoad" id="activitiesLoad">
    <div class="act-ques">
        <input type="hidden" name="campaign_id" value="{{ $campaignId }}">
        <input type="hidden" name="category_id" value="{{ $categoryId }}">
        <input type="hidden" name="category_name" value="{{ $categoryName }}">
        <input type="hidden" name="activity_id" value="">
        <input type="text" name="activity_name" value="" class="form-control activity_name" placeholder="Add Activity Question">
    </div>

    <div class="parent-activity">
        <div class="single-activity">
            <div class="activity-actions">   
                <button class="btn icon-btn color-red activity-cancel"  data-id="{{ $categoryId }}" data-name="{{ $categoryName }}" >
                    <i class="fa fa-times"></i>
                </button>
                <button class="btn icon-btn color-blue activity-submit"  data-id="{{ $categoryId }}" data-name="{{ $categoryName }}" >
                    <i class="fa fa-check"></i>
                </button>
            </div>
            <div class="act-status">
                <select class="select form-control" id="activity_status" name="status[]" >
                    <option value="1" >Working</option>
                    <option value="2" >Completed</option>
                    <option value="3" >Already Set</option>
                </select>
            </div>
            <div class="act-file-link">
                <button type="button"  placeholder="File Link:" class="form-control" id="file_link" name="file_link[]" data-pd-popup-open="addProgressPopup">
                    <i class="fa fa-paperclip"></i>
                Add Progress</button>
            </div>

            <div class="act-hours dates" id="datepicker">
               <input type="text" class="form-control project_domain_register activity_date" id="activity_date" name="activity_date[]"   placeholder="YYYY-MM-DD"/ >
           </div>

           <div class="act-hours time" id="timepicker">
            <input type="number" class="form-control activity_time" id="activity_hours" name="activity_hours[]" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "2"  placeholder="HH"/ >
            <input type="number" class="form-control activity_time" id="activity_seconds" name="activity_seconds[]" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" 
            maxlength = "2" placeholder="MM"/ >
        </div>
        <div class="act-note">
            <textarea class="form-control" id="activity_note" name="notes[]" placeholder="Type something:" ></textarea>
        </div>
    </div>
</div>
<div class="act-submit">
    <button class="btn icon-btn color-blue" disabled><i class="fa fa-paper-plane-o"></i></button>
</div>
</form>