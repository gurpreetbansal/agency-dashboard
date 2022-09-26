<div class="main-data-view" id="keywordExplorer">
	<div class="white-box pa-0 mb-40">
		<input type="hidden" class="user_id" value="{{$user_id}}">
		<input type="hidden" class="location">
		<input type="hidden" class="language">
		<input type="hidden" class="search_term">
		<input type="hidden" class="category">
		<div class="keyword-explorer">
			@include('viewkey.keyword_explorer.search')
		</div>
	</div>
</div>