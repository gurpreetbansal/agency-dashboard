@extends('layouts.vendor_internal_pages')
@section('content')
<div class="sAudit-section detail-overview">
	<input type="hidden" class="audit-id" value="{{ @$summaryAudit->id }}">
	<input type="hidden" class="campaign_id" value="{{ $campaignData->id }}">
	<input type="hidden" class="url" value="{{ $campaignData->domain_url }}">
	<div class="inner">
		<div class="top-flex">
			<ul class="breadcrumb-list">
				<li class="breadcrumb-item">
					<a href="{{ url('/dashboard') }}">Home</a>
				</li>
				@if(@$campaignData->id)
					<li class="breadcrumb-item">
			    		<a href="{{ url('/campaign-detail/').'/'. $campaignData->id }}">{{ $campaignData->host_url }}</a>
			    	</li>
			    	<li class="uk-active breadcrumb-item">Site Audit</li>
		    	@else
		    		<li class="uk-active breadcrumb-item">Site Audit</li>
		    	@endif
			</ul>
			<div class="right-icons">
				<nav class="btn-group">
					<span class="audit-disable" style="cursor: not-allowed">
					<a href="javascript:;" class="btn site-audit-refresh icon-btn color-orange ajax-loader" data-status="{{ $summaryAudit <> null && $summaryAudit->audit_status == 'process' ? 'progress' : 'completed' }}" data-auditid="{{ @$summaryAudit->id }}" style="{{ @$summaryAudit->audit_status == 'completed' ? '' : 'pointer-events: none' }}"data-auditurl="{{ @$summaryAudit->url }}" uk-tooltip="title: Refresh; pos: top-center" >
						<img src="{{URL::asset('public/vendor/internal-pages/images/refresh-yellow-icon.png')}}">
					</a>
					</span>
					<span class="audit-disable" style="cursor: not-allowed">
					<a target="_blank" href="{{ url('/download/pdf/'.$key.'/audit') }}" class="btn icon-btn campaign-pdf color-red ajax-loader" uk-tooltip="title: Download pdf; pos: top-center" style="{{ @$summaryAudit->audit_status == 'completed' ? '' : 'pointer-events: none' }}" >
						<img src="{{URL::asset('public/vendor/internal-pages/images/pdf-icon.png')}}">
					</a>
					</span>
					<a target="_blank" href="{{url('/project-settings/'.$campaignData->id)}}" class="btn icon-btn color-blue" uk-tooltip="title:Project Setting; pos: top-center">
						<img src="{{ URL::asset('/public/vendor/internal-pages/images/setting-icon.png') }} ">
					</a>
					<a href="javascript:;" id="ShareKey" data-id="{{ $campaignData->id }}" data-share-key="{{ @$campaignData->share_key }}" class="btn icon-btn color-purple" uk-tooltip="title: Generate Shared Key; pos: top-center" aria-expanded="false" >
						<img src="{{ URL::asset('/public/vendor/internal-pages/images/share-key-icon.png') }}">
					</a>
				</nav>
			</div>
		</div>

		<div class="white-box overviewBox p-0">
			<div class="white-box-body">
				<div class="left">
	                <div class="circle_percent ajax-loader" style="display:block;"></div>
			    </div>
			    <div class="right uk-flex">
					<div class="score-for">
						<p><small class="ajax-loader">Website score for</small></p>
						<h2 class="ajax-loader">cpeguide.com</h2>
						<p><a class="ajax-loader">How is it calculated?</a></p>
						<ul class="ajax-loader" style="max-width: 250px;">
							<li>162.159.134.42</li>
						</ul>
						<a class="btn btn-sm ajax-loader">
							<i class="fa fa-code"></i> View page code
						</a>
						<a class="btn btn-sm ajax-loader">View issues</a>
					</div>
					<div class="elem-end">
						<article>
		            		<ul>
			                    <li class="ajax-loader"></li>
			                    <li class="ajax-loader"></li>
		            		</ul>
			                <ul>
			                    <li class="ajax-loader"></li>
		                        <li class="ajax-loader"></li>
			                </ul>
		        		</article>
					</div>
				</div>
			</div>
			<div class="white-box-foot">
				<div class="uk-child-width-expand@s uk-grid">
					<div>
						<div class="issuesSingle">
							<div class="uk-flex">
								<div class="issueName ajax-loader">
									<p>0 high issues</p>
								</div>
								<p class="ajax-loader">0.00%</p>
							</div>
							<progress class="uk-progress ajax-loader"></progress>
						</div>
					</div>
					<div>
						<div class="issuesSingle">
							<div class="uk-flex">
								<div class="issueName ajax-loader">
									<p>0 high issues</p>
								</div>
								<p class="ajax-loader">0.00%</p>
							</div>
							<progress class="uk-progress ajax-loader"></progress>
						</div>
					</div>
					<div>
						<div class="issuesSingle">
							<div class="uk-flex">
								<div class="issueName ajax-loader">
									<p>0 high issues</p>
								</div>
								<p class="ajax-loader">0.00%</p>
							</div>
							<progress class="uk-progress ajax-loader"></progress>
						</div>
					</div>
					<div>
						<div class="issuesSingle">
							<div class="uk-flex">
								<div class="issueName ajax-loader">
									<p>0 high issues</p>
								</div>
								<p class="ajax-loader">0.00%</p>
							</div>
							<progress class="uk-progress ajax-loader"></progress>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="white-box pagesBox p-0">
			<div class="white-box-head">
				<h5>Pages</h5>
			</div>
			<div class="white-box-body audit-pages-list">
				<div class="auditTable">
					<table>
						<thead>
							<tr>
								<th>
									<div class="ajax-loader">URL</div>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<div class="ajax-loader h-40"></div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="ajax-loader h-40"></div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="ajax-loader h-40"></div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="ajax-loader h-40"></div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="ajax-loader h-40"></div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="ajax-loader h-40"></div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="ajax-loader h-40"></div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="ajax-loader h-40"></div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="ajax-loader h-40"></div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="ajax-loader h-40"></div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="project-table-cover">
					<div class="project-table-foot">
						<div class="ajax-loader h-26" style="min-width: 200px;"></div>
						<div class="ajax-loader h-26" style="min-width: 300px;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection