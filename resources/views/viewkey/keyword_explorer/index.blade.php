@extends('layouts.vendor_internal_pages')
@section('content')
<div class="project-detail-body">
	<input type="hidden" class="campaign_id" value="{{$campaign_id}}">
	<input type="hidden" class="user_id" value="{{$user_id}}">
	<input type="hidden" class="location">
	<input type="hidden" class="language">
	<input type="hidden" class="search_term">
	<input type="hidden" class="category">
	
	<div class="keyword-explorer">
		@include('vendor.keyword_explorer.search')
	</div>
</div>
@endsection