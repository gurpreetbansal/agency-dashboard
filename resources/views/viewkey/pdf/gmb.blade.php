<div class="main-data-pdf" id="gmbDashboard" uk-sortable="handle:.white-box-handle">
	<input type="hidden" class="location_lat" value="{{@$gmb_location_data->location_lat}}">
	<input type="hidden" class="location_lng" value="{{@$gmb_location_data->location_lng}}">
	<input type="hidden" class="location_name" value="{{@$gmb_location_data->location_name}}">
	<input type="hidden" class="selected_direction_request" value="{{@$selected_direction_request}}">
	@include('viewkey.pdf.gmb_sections.customer_search')
	@include('viewkey.pdf.gmb_sections.customer_views')
	@include('viewkey.pdf.gmb_sections.customer_actions')
	@include('viewkey.pdf.gmb_sections.direction_requests')
	@include('viewkey.pdf.gmb_sections.phone_calls')
	@include('viewkey.pdf.gmb_sections.photo_views')
	@include('viewkey.pdf.gmb_sections.reviews')
</div>