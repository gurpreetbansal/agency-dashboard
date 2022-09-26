 <div id="offcanvas-Lists" uk-offcanvas="flip: true; overlay: true" style="display: none;">
    <div class="uk-offcanvas-bar custom-offcanvas keywordsLists-side">
        <button class="uk-offcanvas-close keywordList-close" type="button" uk-close></button>
        <div class="gbox">
            <h3><i class="fa fa-star"></i> Keyword lists</h3>
        </div>
        <div class="list-data">
        	<form class="searchBox">
        		<div class="form-group gray-gradient">
        			<span class="icon">
        				<img src="/public/vendor/internal-pages/images/keyword-seach-icon.png" alt="">
        			</span>
        			<input type="text" class="form-control search-in-listing" placeholder="Search for a list..." name="keyword" autocomplete="false" onkeydown="return (event.keyCode!=13);">
        		</div>
        	</form>
			<div class="scroll-list">
				@for($i=1;$i<=3;$i++)
				<div class="single ">
					<a href="javascript:void(0)">
						<h5>..............</h5>
						<ul>
							<li>.........</li>
							<li>.........</li>
						</ul>
					</a>
					<div class="action-btns">
						<a href="javascript:void(0)" uk-tooltip="title: Rename the list; pos: top">
							<svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#kwPencil"></use></svg>
						</a>
						<a href="javascript:void(0)" uk-tooltip="title: Export to CSV; pos: top">
							<svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#kwDownload"></use></svg>
						</a>
						<a href="javascript:void(0)" uk-tooltip="title: Delete list; pos: top">
							<svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#kwDelete"></use></svg>
						</a>
					</div>
				</div>
				@endfor
			</div>
		</div>
    </div>
</div> 

<div id="offcanvas-History" uk-offcanvas="flip: true; overlay: true" style="display: none;">
    <div class="uk-offcanvas-bar custom-offcanvas keywordsHistory-side">
        <button class="uk-offcanvas-close keywordHistory-close" type="button" uk-close></button>
        <div class="gbox">
            <h3>
            	<svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#kwSearchHistory"></use></svg>
             	Search history

             	<a href="javascript:void(0)" class="btn" id="clear_search_history">
					<svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#kwDelete"></use></svg>
                    Clear history
				</a>
         	</h3>
        </div>
        <div class="scroll-history">
        	@for($i=1;$i<=3;$i++)
        	 <div class="single">
        		<a href="javascript:void(0)">
					<div class="count low ajax-loader">0</div>
					<div class="details">
						<h6 class="ajax-loader">.....................</h6>
						<ul class="ajax-loader">
							<li><i class="fa fa-map-marker"></i>.....................</li>
							<li><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#kwLanguage"></use></svg>.................</li>
						</ul>
					</div>
				</a>
        	</div>
        	@endfor
        </div>
    </div>
</div>

<svg style="display:none !important;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <symbol id="kwPencil" viewBox="0 0 512 512"><path fill="currentColor" d="M497.9 142.1l-46.1 46.1c-4.7 4.7-12.3 4.7-17 0l-111-111c-4.7-4.7-4.7-12.3 0-17l46.1-46.1c18.7-18.7 49.1-18.7 67.9 0l60.1 60.1c18.8 18.7 18.8 49.1 0 67.9zM284.2 99.8L21.6 362.4.4 483.9c-2.9 16.4 11.4 30.6 27.8 27.8l121.5-21.3 262.6-262.6c4.7-4.7 4.7-12.3 0-17l-111-111c-4.8-4.7-12.4-4.7-17.1 0zM124.1 339.9c-5.5-5.5-5.5-14.3 0-19.8l154-154c5.5-5.5 14.3-5.5 19.8 0s5.5 14.3 0 19.8l-154 154c-5.5 5.5-14.3 5.5-19.8 0zM88 424h48v36.3l-64.5 11.3-31.1-31.1L51.7 376H88v48z"></path></symbol>

    <symbol id="kwDownload" viewBox="0 0 512 512"><path fill="currentColor" d="M216 0h80c13.3 0 24 10.7 24 24v168h87.7c17.8 0 26.7 21.5 14.1 34.1L269.7 378.3c-7.5 7.5-19.8 7.5-27.3 0L90.1 226.1c-12.6-12.6-3.7-34.1 14.1-34.1H192V24c0-13.3 10.7-24 24-24zm296 376v112c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V376c0-13.3 10.7-24 24-24h146.7l49 49c20.1 20.1 52.5 20.1 72.6 0l49-49H488c13.3 0 24 10.7 24 24zm-124 88c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20zm64 0c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20z"></path></symbol>

    <symbol id="kwDelete" viewBox="0 0 448 512"><path fill="currentColor" d="M268 416h24a12 12 0 0 0 12-12V188a12 12 0 0 0-12-12h-24a12 12 0 0 0-12 12v216a12 12 0 0 0 12 12zM432 80h-82.41l-34-56.7A48 48 0 0 0 274.41 0H173.59a48 48 0 0 0-41.16 23.3L98.41 80H16A16 16 0 0 0 0 96v16a16 16 0 0 0 16 16h16v336a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48V128h16a16 16 0 0 0 16-16V96a16 16 0 0 0-16-16zM171.84 50.91A6 6 0 0 1 177 48h94a6 6 0 0 1 5.15 2.91L293.61 80H154.39zM368 464H80V128h288zm-212-48h24a12 12 0 0 0 12-12V188a12 12 0 0 0-12-12h-24a12 12 0 0 0-12 12v216a12 12 0 0 0 12 12z"></path></symbol>

    <symbol id="kwSearchHistory" viewBox="0 0 512 512"><path fill="currentColor" d="M504 255.531c.253 136.64-111.18 248.372-247.82 248.468-59.015.042-113.223-20.53-155.822-54.911-11.077-8.94-11.905-25.541-1.839-35.607l11.267-11.267c8.609-8.609 22.353-9.551 31.891-1.984C173.062 425.135 212.781 440 256 440c101.705 0 184-82.311 184-184 0-101.705-82.311-184-184-184-48.814 0-93.149 18.969-126.068 49.932l50.754 50.754c10.08 10.08 2.941 27.314-11.313 27.314H24c-8.837 0-16-7.163-16-16V38.627c0-14.254 17.234-21.393 27.314-11.314l49.372 49.372C129.209 34.136 189.552 8 256 8c136.81 0 247.747 110.78 248 247.531zm-180.912 78.784l9.823-12.63c8.138-10.463 6.253-25.542-4.21-33.679L288 256.349V152c0-13.255-10.745-24-24-24h-16c-13.255 0-24 10.745-24 24v135.651l65.409 50.874c10.463 8.137 25.541 6.253 33.679-4.21z"></path></symbol>

    <symbol id="kwLanguage" viewBox="0 0 640 512"><path fill="currentColor" d="M152.1 236.2c-3.5-12.1-7.8-33.2-7.8-33.2h-.5s-4.3 21.1-7.8 33.2l-11.1 37.5H163zM616 96H336v320h280c13.3 0 24-10.7 24-24V120c0-13.3-10.7-24-24-24zm-24 120c0 6.6-5.4 12-12 12h-11.4c-6.9 23.6-21.7 47.4-42.7 69.9 8.4 6.4 17.1 12.5 26.1 18 5.5 3.4 7.3 10.5 4.1 16.2l-7.9 13.9c-3.4 5.9-10.9 7.8-16.7 4.3-12.6-7.8-24.5-16.1-35.4-24.9-10.9 8.7-22.7 17.1-35.4 24.9-5.8 3.5-13.3 1.6-16.7-4.3l-7.9-13.9c-3.2-5.6-1.4-12.8 4.2-16.2 9.3-5.7 18-11.7 26.1-18-7.9-8.4-14.9-17-21-25.7-4-5.7-2.2-13.6 3.7-17.1l6.5-3.9 7.3-4.3c5.4-3.2 12.4-1.7 16 3.4 5 7 10.8 14 17.4 20.9 13.5-14.2 23.8-28.9 30-43.2H412c-6.6 0-12-5.4-12-12v-16c0-6.6 5.4-12 12-12h64v-16c0-6.6 5.4-12 12-12h16c6.6 0 12 5.4 12 12v16h64c6.6 0 12 5.4 12 12zM0 120v272c0 13.3 10.7 24 24 24h280V96H24c-13.3 0-24 10.7-24 24zm58.9 216.1L116.4 167c1.7-4.9 6.2-8.1 11.4-8.1h32.5c5.1 0 9.7 3.3 11.4 8.1l57.5 169.1c2.6 7.8-3.1 15.9-11.4 15.9h-22.9a12 12 0 0 1-11.5-8.6l-9.4-31.9h-60.2l-9.1 31.8c-1.5 5.1-6.2 8.7-11.5 8.7H70.3c-8.2 0-14-8.1-11.4-15.9z"></path></symbol>
</svg>


<div id="add-keywords-list" class="keywordList-popup uk-flex-top" uk-modal>
	<div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical px-0">
		<input type="hidden" value="keywords" class="listing-type">
		<div class="custom-scroll">
			<button class="uk-modal-close-default" type="button" uk-close></button>
			<h3><i class="fa fa-star"></i> Add keywords to a list</h3>
			<ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: .listContainer">
			    <li class="existingList-li"><a href="#existingList">Existing list</a></li>
			    <li class="newList-li"><a href="#newList">New list</a></li>
			</ul>
			<ul class="uk-switcher listContainer">
				<li id="existingList">					
			        <form class="searchBox">
						<div class="form-group gray-gradient">
							<span class="icon"><img src="{{URL::asset('public/vendor/internal-pages/images/keyword-seach-icon.png')}}" alt="keyword-seach-icon"></span>
							<input type="text" class="form-control search-in-list" placeholder="Search for a list..." name="keyword" autocomplete="false">
						</div>
					</form>
					<ul class="list" id="display-keyword-list"></ul>
					<button type="submit" class="btn blue-btn"  id="add_to_list_btn">Add keywords</button>
				</li>
				<li id="newList">
					<form >
						<div class="searchBox">
							<div class="form-group gray-gradient">
								<input placeholder="e.g. Black coffee blog post 2020" required type="text" class="create-list-name">
							</div>
						</div>
						<button type="submit" class="btn blue-btn" id="create_list">Create list & Add keywords</button>
					</form>
				</li>
			</ul>
		</div>
	</div>
</div>

<div class="popup" data-pd-popup="deleteListPopup"  id="showPopup">
    <div class="popup-inner">
        <h3><i class="fa fa-trash"></i> Delete list?</h3>
        <form>
            <input type="hidden" class="lastprojectid">
            <p>Do you really want to delete <strong class="display-list-name"></strong> ? Keep in mind this action cannot be reverted.</p>
            <div class="uk-flex">
                <input type="button" class="btn red-btn mr-3" value="Yes, Delete List" id="DeleteList">
                <input type="button" class="btn white-btn" value="No, take me back" data-pd-popup-close="deleteListPopup">
            </div>
        </form>
    </div>
</div>

<!-- import keywords section -->
<div id="offcanvas-import" uk-offcanvas="flip: true; overlay: true" style="display: none;">
	<div class="uk-offcanvas-bar custom-offcanvas keywordsImport-side">
		<button class="uk-offcanvas-close import-close" type="button" uk-close></button>
		<div class="gbox">
			<h3><span uk-icon="download"></span>Import</h3>
		</div>
		<div class="scroll-import">
			<form id="import-form">
				<div class="form-group">
					<label>Select location</label>
					<span class="icon"><img src="{{asset('public/vendor/internal-pages/images/keyword-location-icon.png')}}" alt="keyword-location-icon"></span>
					<input id="OpenCustomDropdownImport" type="text" class="form-control look-like-select import-keyword-locations" readonly placeholder="Anywhere" value="Anywhere" />
					<div class="custom-dropdown-menu-import">
						<div class="custom-dropdown-menu-inner">
							<div class="custom-bs-searchbox">
								<input type="text" class="form-control input-search-keyword-import" />
								<img src="{{URL::asset('public/vendor/internal-pages/images/refresh-add-icon.gif')}}" class="refresh-search-icon refresh-icon" id="refresh-icon-keyword">
							</div>
							<ul class="dropdown-menu-inner" id="keyword_locations_import"></ul>
						</div>
					</div>
					<input type="hidden" class="import_keyword_location_id">
				</div>

				<div class="form-group ">
					<div class="d-flex">
						<label>write the keywords <span>[<span class="tags-count">0</span> / 200]</span></label>
						<a href="javascript:void(0)" class="btn remove-added-tags">
							<svg>
			                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#kwDelete"></use>
			                </svg>
			                Clear all keywords
						</a>
					</div>
					<input type="text" class="form-control create-multiple-keyword-tags" placeholder="Separate with Tab or Enter ... " />
				</div>

				<div class="form-group uk-text-right">
					<button type="button" class="btn blue-btn find-import-keywords" data-category="1">Find keywords</button>
				</div>
			</form>
		</div>
	</div>
</div>