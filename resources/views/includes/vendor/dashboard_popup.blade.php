<div class="popup" data-pd-popup="add_project_tags_popup">
	<div class="popup-inner">
		<div class="AddTags-progress-loader popup-progress-loader"></div>
		<!-- <h3><figure><i class="fa fa-tag"></i></figure> Add Tags - <span id="tags_project_name"></span></h3> -->
		<h3 class="uk-text-left"><figure><i class="fa fa-tag"></i></figure> Add Tags</h3>
		<form>
			<input type="hidden" class="campaign_id" >
			<div class="form-group">
				<a class="tags_project_href" href="javascript:;" target="_blank"><i class="fa fa-external-link"></i></a>
				<label id="tags_project_name"></label>
				<input type="text" class="form-control add_project_tag_input" placeholder="Add tag with ',' or enter (Only 3 tags allowed per project)" data-role="tagsinput" value="">
				<span class="errorStyle"><p id="tagsinput_error"></p></span>
			</div>
			<div id="append_project_tag_div"></div>

			<div class="uk-text-center">
				<input type="button" class="btn blue-btn" value="Add tag" id="add_project_tag">
			</div>
		</form>
		<a class="popup-close" data-pd-popup-close="add_project_tags_popup" href="javascript:;" id="add_project_tags_popup_close"></a>
	</div>
</div>