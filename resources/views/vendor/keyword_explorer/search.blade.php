<div class="keywords-page">
	<!-- <nav class="keyTabs">
		<a href="javascript:void(0)" class="search_keywords active">Search</a>
		<a href="javascript:void(0)" uk-toggle="target: #offcanvas-Lists" class="show-list">Lists</a>
		<a href="javascript:void(0)" uk-toggle="target: #offcanvas-History" class="show-history">History</a>
	</nav> -->
	<div class="white-box keywordSearch">		
		<div class="floating-elem">
			<span class="layer1"><img src="{{URL::asset('public/vendor/internal-pages/images/particle-layer1.png')}}" alt="layers"></span>
			<span class="layer2"><img src="{{URL::asset('public/vendor/internal-pages/images/particle-layer2.png')}}" alt="layers"></span>
			<span class="layer3"><img src="{{URL::asset('public/vendor/internal-pages/images/particle-layer3.png')}}" alt="layers"></span>
			<span class="layer4"><img src="{{URL::asset('public/vendor/internal-pages/images/particle-layer4.png')}}" alt="layers"></span>
			<span class="layer5"><img src="{{URL::asset('public/vendor/internal-pages/images/particle-layer5.png')}}" alt="layers"></span>
			<span class="layer6"><img src="{{URL::asset('public/vendor/internal-pages/images/particle-layer6.png')}}" alt="layers"></span>
			<span class="layer7"><img src="{{URL::asset('public/vendor/internal-pages/images/particle-layer7.png')}}" alt="layers"></span>
			<span class="layer8"><img src="{{URL::asset('public/vendor/internal-pages/images/particle-layer8.png')}}" alt="layers"></span>
			<span class="layer9"><img src="{{URL::asset('public/vendor/internal-pages/images/particle-layer9.png')}}" alt="layers"></span>
			<span class="layer10"><img src="{{URL::asset('public/vendor/internal-pages/images/particle-layer10.png')}}" alt="layers"></span>
		</div>
		<nav class="btn-group">
			<a href="javascript:;" class="btn icon-btn color-blue show-import" uk-tooltip="title: Import keywords; pos: top-center" title="" aria-expanded="false" uk-toggle="target: #offcanvas-import">
				<span uk-icon="download"></span>
			</a>
			<a href="javascript:;" class="btn icon-btn color-orange show-list" uk-tooltip="title: Lists; pos: top-center" title="" aria-expanded="false" uk-toggle="target: #offcanvas-Lists">
				<span uk-icon="list"></span>
			</a>
			<a href="javascript:;" class="btn icon-btn color-red show-history" uk-tooltip="title: History; pos: top-center" title="" aria-expanded="false" uk-toggle="target: #offcanvas-History" >
				<span uk-icon="history"></span>
			</a>
		</nav>
		<div class="keywordSearch-inner">
			<h1><strong>Thousands of keyword ideas</strong> <br>are waiting for you</h1>
			<ul class="uk-subnav uk-subnav-pill" uk-switcher="connect: .searchContainer">
				<li><a href="#searchKeyword">Search by keyword</a></li>
				<li><a href="#searchDomain">Search by domain</a></li>
			</ul>
			<ul class="uk-switcher searchContainer">
				<li id="searchKeyword" class="search-section">
					<form class="uk-flex">
						<div class="form-group">
							<span class="icon"><img src="{{asset('public/vendor/internal-pages/images/keyword-seach-icon.png')}}" alt="keyword-seach-icon"></span>
							<input type="text" class="form-control query_field searchTerm" placeholder="Enter keyword"  autocomplete="false" />
						</div>
						<div class="form-group">
							<span class="icon"><img src="{{asset('public/vendor/internal-pages/images/keyword-location-icon.png')}}" alt="keyword-location-icon"></span>
							<input id="OpenCustomDropdown" type="text" class="form-control look-like-select keyword-locations" readonly placeholder="Anywhere" value="Anywhere" />
								<div class="custom-dropdown-menu">
									<div class="custom-dropdown-menu-inner">
									    <div class="custom-bs-searchbox">
									        <input type="text" class="form-control input-search-keyword" />
									        <img src="{{URL::asset('public/vendor/internal-pages/images/refresh-add-icon.gif')}}" class="refresh-search-icon refresh-icon" id="refresh-icon-keyword">
									    </div>
									    <ul class="dropdown-menu-inner" id="keyword_locations"></ul>
									</div>
								</div>
							<input type="hidden" class="keyword_location_id">
						</div>
						<div class="form-group">
							<span class="icon"><img src="{{asset('public/vendor/internal-pages/images/keyword-language-icon.png')}}" alt="keyword-language-icon"></span>
							<select class="selectpicker language" id="keyword_language" data-live-search="true"></select>
							<input type="hidden" class="keyword_language_id">
						</div>
						<div class="form-group">
							<button type="button" class="btn blue-btn find_keywords" data-category="1">Find keywords</button>
						</div>
					</form>
					<p>Try <a href="javascript:;" data-query="seo agency" data-category="1" class="search_query">seo agency</a> or <a href="javascript:;" data-category="2"  data-query="agencydashboard.io" class="search_query">agencydashboard.io</a></p>
				</li>
				<li id="searchDomain" class="search-section">
					<form class="uk-flex">
						<div class="form-group">
							<span class="icon"><img src="{{asset('public/vendor/internal-pages/images/keyword-seach-icon.png')}}" alt="keyword-seach-icon"></span>
							<input type="text" class="form-control domain_query_field searchTerm" placeholder="Enter domain or URL" autocomplete="false">
						</div>
						<!-- <div class="form-group">
							<span class="icon"><img src="{{asset('public/vendor/internal-pages/images/keyword-location-icon.png')}}" alt="keyword-location-icon"></span>
							<select class="selectpicker locations" id="domain_locations" data-live-search="true"></select>
							<input type="hidden" class="domain_location_id">
						</div> -->
						<div class="form-group">
							<span class="icon"><img src="{{asset('public/vendor/internal-pages/images/keyword-location-icon.png')}}" alt="keyword-location-icon"></span>
							<input id="OpenCustomDropdownDomain" type="text" class="form-control look-like-select domain-locations" readonly placeholder="Anywhere" value="Anywhere">
								<div class="custom-dropdown-menu domainDiv">
									<div class="custom-dropdown-menu-inner">
									    <div class="custom-bs-searchbox">
									        <input type="text" class="form-control input-search-domain" autocomplete="off" role="textbox" aria-label="Search">
									        <img src="{{URL::asset('public/vendor/internal-pages/images/refresh-add-icon.gif')}}" class="refresh-search-icon refresh-icon" style="display: none;">
									    </div>
									    <ul class="dropdown-menu-inner" id="domain_locations"></ul>
									</div>
								</div>
							<input type="hidden" class="domain_location_id">
						</div>
						<div class="form-group">
							<button type="button" class="btn blue-btn find_keywords" data-category="2">Find keywords</button>
						</div>
					</form>
					<p>Try <a href="javascript:;" data-query="seo agency" data-category="1" class="search_query">seo agency</a> or <a href="javascript:;" data-category="2"  data-query="agencydashboard.io" class="search_query">agencydashboard.io</a></p>
				</li>
			</ul>
		</div>
	</div>
</div>