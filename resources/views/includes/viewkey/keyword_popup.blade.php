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
                  <option value="favorited">Favorites</option>
                  <option value="unfavorited">Non-favorites</option>
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



<div class="edit-keywordsFilters-popup-rankings">
    <div class="edit-keywords-popup-inner">
        <div class="add-keywords-popup-head">
            <h3>
                <figure><img src="{{URL::asset('public/vendor/internal-pages/images/keyword-filter.png')}}"></figure> Keyword Filters
            </h3>
            <div class="filter-progress-loader progress-loader"></div>
        </div>
        <form id="keywords_Filter_rankings">
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
            <div class="form-group" id="fitler-tags-div-rankings"><label>Tags</label></div>
            <div class="text-left">
                <input type="button" class="btn blue-btn" value="Apply" id="update_keyword_filters_rankings">
            </div>
        </form>
        <a class="popup-close" id="EditKeywordsFiltersRankingsClose" href="javascript:;"></a>
    </div>
</div>