<input type="hidden" value="{{$campaign_id}}" class="campaignID">
<div class="tabs-animation">
     <div class="row">
        <div class="col-md-12 col-lg-6 col-xl-12">
            <div class="mb-3 card">
                <div class="card-header-tab card-header-tab-animation card-header">
                    <div class="card-header-title BacklinkSection">
                        <img src="{{URL::asset('/public/vendor/images/backlink-icon.png')}}">
                        Recent Backlinks
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered data-table" id="backlink_profile_viewkey">
                            <thead>
                                <tr>
                                    <th>Referring Page</th>
                                    <th>No Follow</th>
                                    <th>Anchor & Backlink</th>
                                    <th>Like Type</th>
                                    <th>External Links</th>
                                    <th>First Seen</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>