@extends('layouts.sa-view_key_layout')
@section('content')
<!-- Project Tabs Content -->
<div class="tab-content ">
    <div class="projectNavContainer audit-share-key">
        
        <input type="hidden" name="key" id="audit_id" value="{{ $summaryAudit->id }}">
        <input type="hidden" name="key" id="audit-type" value="{{ $auditType }}">
        <input type="hidden" name="key" id="page-type" value="{{ $pageType }}">
        <div  class="sa-audit-overview">
            @include('vendor.audits.audit-loader-view')
        </div>
        
        <div class="sa-audit-details"></div>
      
    </div>

    <div class="uk-switcher projectNavContainerSideBar">
    </div>
</div>
<!-- Project Tabs Content End -->

@endsection