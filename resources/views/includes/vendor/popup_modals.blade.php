<div class='back-to-top' id='back-to-top' title='Back to top'><i class='fa fa-angle-up'></i></div>

<div class="share-key-popup">
  <div class="share-key-inner">
    <h3>Share key</h3>
    <form>
      <div class="form-group">
        <div class="icon">
          <img src="{{URL::asset('public/vendor/internal-pages/images/project-website-icon.png')}}">
        </div>
        <input type="text" id="SharekeyInput" value="" class="form-control copy_share_key_value" readonly>
      </div>
      <input type="button" class="btn share-key-btn" data-clipboard-target="#SharekeyInput" value="Click to copy">
    </form>
    <input type="hidden" class="project-id">
    <input type="button" class="btn reset-share-key-btn" value="Reset">
    <button type="button" class="close-share-key">
      <span uk-icon="icon: close"></span>
    </button>
  </div>
</div>


<div class="add-keywords-popup">
  <div class="add-keywords-popup-inner">
    <div class="add-keywords-popup-head">
      <h3><figure><img src="{{URL::asset('public/vendor/internal-pages/images/add.png')}}"></figure> Add Keywords</h3>
    </div>

    <form id="addNewKeyword">
      <input type="hidden" name="campaign_id" value="{{@$campaign_id}}">

      <div uk-grid>

        <div class="uk-width-1-1@l">
          <div class="form-group">
            <div class="icon">
              <img src="{{URL::asset('public/vendor/internal-pages/images/project-website-icon.png')}}">
            </div>
            <input type="text" class="form-control domain_url" placeholder="https://www.example.com" name="domain_url" value="{{@$data->domain_url}}">
            <span id="domain_url_error" class="error errorStyle"><p></p></span>
            <div class="domain-dropDownBox">
              <button type="button" class="keyword_domain_type" name="keyword_domain_type">*.domain.com/*</button>
              <div class="domain-dropDownMenu" id="keyword-domain-dropDownMenu">
                <ul class="domain-type-ul">
                  <li class="domain-type-list active"><h6>*.domain.com/*</h6>All subdomains and all pages</li>
                  <li class="domain-type-list"><h6>URL</h6>Exact URL</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div uk-grid>

        <div class="uk-width-1-1@l">
          <div class="form-group">
            <div class="icon">
              <img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
            </div>
            <textarea class="form-control keyword_field" placeholder="Enter one keyword per line" name="keyword_field" ></textarea>
            <span id="keywords_error" class="error errorStyle"><p></p></span>
          </div>
        </div>

      </div>

      <div uk-grid>

        <div class="uk-width-1-2@m">
          <div class="form-group">
            <div class="icon">
              <img src="{{URL::asset('public/vendor/internal-pages/images/google-icon.png')}}">
            </div>
            <select name="search_engine_region" class="select form-control regions selectpicker"  id="add_region"  >
              <option value="">-Select-</option>
            </select>
            <span id="regions_error" class="error errorStyle"><p></p></span>
          </div>
        </div>

        <div class="uk-width-1-2@m">
          <div class="form-group">
            <div class="icon">
              <img src="{{URL::asset('public/vendor/internal-pages/images/website-icon.png')}}">
            </div>
            <select class="form-control language selectpicker"  id="add_language" name="language">
              <option value="">-Select-</option>
            </select>

            <span id="language_error" class="error errorStyle"><p></p></span>

          </div>
        </div>

      </div>

      <div uk-grid>

        <div class="uk-width-1-1@l">
          <div class="form-group dropdown mb-0">
            <div class="icon">
              <img src="{{URL::asset('public/vendor/internal-pages/images/location-icon.png')}}">
            </div>
            <input name="locations" id="add_keyword_location" type="text" class="form-control dfs_locations" placeholder="Search Location"  value="{{@$data->rank_location}}" />
            <input id="lat" type="hidden" name="lat" value="{{@$data->rank_latitude}}">
            <input id="long" type="hidden" name="long" value="{{@$data->rank_longitude}}">
            <span id="locations_error" class="error errorStyle"><p></p></span>
          </div>
        </div>

      </div>

      <div uk-grid>

        <div class="uk-width-1-1@l mb-20">
          <div class="form-group">
            <div class="radio-group">
              <label>
                <input type="radio" name="tracking_options" checked class="tracking_options" value="desktop">
                <span class="custom-radio"></span>
                Desktop
              </label>

              <label>
                <input type="radio" name="tracking_options" {{@$data->rank_device=='mobile'?'checked':''}} class="tracking_options" value="mobile">
                <span class="custom-radio"></span>
                Mobile
              </label>
              <span class="error errorStyle"><p id="tracking_options_error"></p></span>
            </div>
          </div>
        </div>

      </div>

      <div class="form-group">
        <div uk-grid>
          <div class="uk-width-1-2@s">
            <label>Ignore local listings</label>
          </div>
          <div class="uk-width-1-2@s">
            <label class='sw'>
              <input name="ignore_local_listing" type="checkbox" class="ignore_local_listing">
              <div class='sw-pan'></div>
              <div class='sw-btn'></div>
            </label>
          </div>
        </div>
      </div>

      <div class="text-left">
        <input type="button" class="btn blue-btn" value="Submit" id="add_new_keywords_data">
      </div>


    </form>
    <a class="popup-close" id="AddKeywordsBtnClose" href="javascript:;" ></a>
  </div>
</div>


<div class="edit-keywords-popup">
  <div class="edit-keywords-popup-inner">
    <div class="add-keywords-popup-head">
      <h3><figure><img src="{{URL::asset('public/vendor/internal-pages/images/edit-icon.png')}}"></figure> Edit Keywords</h3>
    </div>
    <form name="edit_keywords" id="edit_keywords">

      <div class="form-group">
        <div class="icon">
          <img src="{{URL::asset('public/vendor/internal-pages/images/project-website-icon.png')}}">
        </div>
        <input type="text" class="form-control update_domain_url has-domain-dropDownBox" placeholder="https://www.example.com" name="update_domain_url" value="{{@$data->domain_url}}">
        <span class="error errorStyle"><p id="update_domain_url_error"></p></span>
        <div class="domain-dropDownBox">
          <button type="button" class="update_keyword_domain_type">*.domain.com/*</button>
          <div class="domain-dropDownMenu" id="update-keyword-domain-dropDownMenu">
            <ul class="update-domain-type-ul">
              <li class="update-domain-type-list active"><h6>*.domain.com/*</h6>All subdomains and all pages</li>
              <li class="update-domain-type-list"><h6>URL</h6>Exact URL</li>
            </ul>
          </div>
        </div>
      </div>
      
      <div class="form-group">
        <div class="icon">
          <img src="{{URL::asset('public/vendor/internal-pages/images/google-icon.png')}}">
        </div>
        <select name="search_engine_region" class="select form-control regions selectpicker" required id="update_region" data-live-search="true">
          <option value="">-Select-</option>

        </select>
        <span class="error errorStyle"><p id="update_regions_error"></p></span>
      </div>

      <div class="form-group">
        <div class="icon">
          <img src="{{URL::asset('public/vendor/internal-pages/images/website-icon.png')}}">
        </div>
        <select class="form-control selectpicker" required id="update_language"  data-live-search="true">
          <option value="">-Select-</option>
        </select>
        <span class="error errorStyle"><p id="update_language_error"></p></span>
      </div>

      <div class="form-group">
        <div class="icon">
          <img src="{{URL::asset('public/vendor/internal-pages/images/location-icon.png')}}">
        </div>
        <input name="locations" id="edit_keyword_location" type="text" class="form-control " placeholder="Search Location" required />
        <input id="latUpdate" type="hidden" name="lat">
        <input id="longUpdate" type="hidden" name="long">
        <span class="error errorStyle"><p id="update_dfs_locations_error"></p></span>
      </div>

      <div class="form-group">
        <div class="radio-group">
          <label>
            <input type="radio" name="Device" checked class="tracking_options" value="desktop">
            <span class="custom-radio"></span>
            Desktop
          </label>

          <label>
            <input type="radio" name="Device" class="tracking_options" value="mobile">
            <span class="custom-radio"></span>
            Mobile
          </label>
          <span class="error errorStyle"><p id="update_tracking_options_error"></p></span>
        </div>
      </div>

      <div class="form-group">
        <div uk-grid>
          <div class="uk-width-1-2@s">
            <label>Ignore local listings</label>
          </div>
          <div class="uk-width-1-2@s">
            <label class='sw'>
              <input name="ignore_local_listing" type="checkbox" class="update_ignore_local_listing">
              <div class='sw-pan'></div>
              <div class='sw-btn'></div>
            </label>
          </div>
        </div>
      </div>

      <div class="text-left">
        <input type="submit" class="btn blue-btn" value="Submit" id="update_keyword_locations">
      </div>

    </form>
    <a class="popup-close" id="EditKeywordsBtnClose" href="javascript:;" ></a>
  </div>
</div>

<!-- Manage Tags -->
<div class="manage-tags-popup">
  <div class="manage-tags-popup-inner">
    <div class="manage-tags-popup-head">
      <h3><figure><i class="fa fa-tag"></i></figure> Search Tag or Create New Tag</h3>
      <div class="tag-progress-loader progress-loader"></div>
    </div>
    <form name="manage_tags" id="manage_tags">

      <div class="form-group">
        <div class="icon">
          <img src="{{URL::asset('public/vendor/internal-pages/images/keywords-up-img.png')}}">
        </div>
        <input type="text" class="form-control search_keyword_tag" placeholder="Start Typing">
      </div>
      <div id="append_tag_div" class="m-height">
      </div>
      
    </form>
    <a class="popup-close" id="KeywordTagBtnClose" href="javascript:;" ></a>
  </div>
</div>

<!-- Add Notes -->
<div class="notes-popup">
  <div class="notes-popup-inner">
    <div class="notes-popup-head">
      <h3><figure><img src="{{URL::asset('public/vendor/internal-pages/images/add-notes.png')}}"></figure> Notes</h3>
      <div class="notes-progress-loader progress-loader"></div>
    </div>
    <div class="new-note">
      <form id="CampaignNoteForm">
       <input type="hidden" name="campaign_id" value="{{@$campaign_id}}" class="campaign_id">
       <div class="form-group">
        <input type="text" class="form-control campaign_notes_date" placeholder="Date" readonly="readonly" value="<?php echo date('m/d/Y',strtotime(now()));?>">
      </div>
      <div class="form-group">
        <textarea class="form-control campaign_notes" placeholder="Start typing your note..."></textarea>
      </div>
      <div class="btn-group start">
        <button type="button" class="btn blue-btn btn-sm" id="addNote" disabled>Add Note</button>
        <button type="reset" class="btn gray-btn btn-sm" id="cancelNote">Cancel</button>
        <button type="reset" class="btn gray-btn btn-sm" id="clearNote" >Clear Note</button>
      </div>
    </form>
  </div>
  <div class="notes-list">

  </div>
  <div class="uk-text-center">
    <a href="javascript:;" class="btn btn-sm blue-btn mb-40" id="newNote">
      <span uk-icon="icon: plus"></span>
      New Note
    </a>
  </div>
  <a class="popup-close" id="NotesBtnClose" href="javascript:;" ></a>
</div>
</div>


<!-- Advance keyword filter -->
<div class="edit-keywordsFilters-popup">
  <div class="edit-keywords-popup-inner">
    <div class="add-keywords-popup-head">
      <h3>
        <figure><img src="{{URL::asset('public/vendor/internal-pages/images/keyword-filter.png')}}"></figure> Keyword Filters
      </h3>
      <div class="filter-progress-loader progress-loader"></div>
    </div>
    <form id="keywords_Filter">
      <div class="form-group">
        <label>Show</label>
        <select class="selectpicker" id="selected_type" data-live-search="true" name="selected_type">
          <option value="all">All Keywords</option>
          <option value="favorited">Favourite</option>
          <option value="unfavorited">Non-favourite</option>
        </select>
      </div>
      <hr />
      <div class="form-group">
        <label>Keywords Tracking</label>
        <div class="highlight-group">
          <label>
            <input type="radio" name="tracking_type" value="all" checked class="tracking_type">
            <span>All</span>
          </label>
          <label>
            <input type="radio" name="tracking_type" value="desktop" class="tracking_type">
            <span>Desktop</span>
          </label>
          <label>
            <input type="radio" name="tracking_type" value="mobile" class="tracking_type">
            <span>Mobile</span>
          </label>
        </div>
      </div>
      <hr />
      <div class="form-group" id="fitler-tags-div"><label>Tags</label></div>

      <div class="text-left">
        <input type="button" class="btn blue-btn" value="Apply" id="update_keyword_filters">
      </div>
    </form>
    <a class="popup-close" id="EditKeywordsFiltersClose" href="javascript:;"></a>
  </div>
</div>