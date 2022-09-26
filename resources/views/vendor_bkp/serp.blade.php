@extends('layouts.vendor_internal_pages')
@section('content')
<input type="hidden" class="campaignID" value="{{@$campaign_id}}">
<input type="hidden" class="campaign_id" value="{{@$campaign_id}}">
<input type="hidden" class="user_id" value="{{@$user_id}}">
@include('vendor.seo_sections.live_keyword_tracking')
@endsection