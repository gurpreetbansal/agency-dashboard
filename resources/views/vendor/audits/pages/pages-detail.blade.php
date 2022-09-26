@extends('layouts.vendor_internal_pages')
@section('content')
<div class="sAudit-section audit-page-details">
	<input type="hidden" class="audit-id" value="{{ @$summaryAuditPages->id }}">
	<input type="hidden" class="pageAudit" value="{{ @$summaryAuditPages->id }}">
	<div class="inner">
		<div class="top-flex">
			<ul class="breadcrumb-list">
			    <li class="breadcrumb-item">
			    	<a href="{{ url('/dashboard') }}">Home</a>
			    </li>
		    	@if($summaryAuditPages->summary->campaign_id)
			    	<li class="breadcrumb-item">
			    		<a href="{{ url('/campaign-detail/').'/'.$summaryAuditPages->summary->campaign_id }}">{{ $summaryAuditPages->project }}</a>
			    	</li>
			    	<li class="breadcrumb-item">
				    	<a href="{{ url('/audit/detail/').'/'.$summaryAuditPages->summary->campaign_id }}">Site Audit</a>
				    </li>	
		    	@else
		    		<li class="breadcrumb-item">
				    	<a href="{{ url('/audit/detail/').'/'.$summaryAuditPages->audit_id }}">{{ $summaryAuditPages->project }}</a>
				    </li>
		    	@endif
			    <li class="uk-active breadcrumb-item">Page Audit </li>
			</ul>
			<div class="right-icons">
				<nav class="btn-group">
					<a href="javascript:;" class="btn individual-refresh icon-btn color-orange" data-type="detail-page" audit-id="{{ $summaryAuditPages->id }}" uk-tooltip="title: Refresh; pos: top-center">
						<img src="{{URL::asset('public/vendor/internal-pages/images/refresh-yellow-icon.png')}}">
					</a>
					<a target="_blank" href="{{url('/project-settings/'.$summaryAuditPages->summary->campaign_id)}}" class="btn icon-btn color-blue" uk-tooltip="title:Project Setting; pos: top-center">
						<img src="{{ URL::asset('/public/vendor/internal-pages/images/setting-icon.png') }} ">
					</a>
					<a  target="_blank" href="{{ url('/download/pdf/'.$key.'/audit-detail') }}" class="btn icon-btn overviewBox color-red" uk-tooltip="title: Download pdf; pos: top-center">
						<img src="{{URL::asset('public/vendor/internal-pages/images/pdf-icon.png')}}">
					</a>
					<a href="javascript:;" id="ShareKey" data-id="{{ $summaryAuditPages->summary->campaign_id }}" data-share-key="{{ @$campaignData->share_key }}" class="btn icon-btn color-purple" uk-tooltip="title: Generate Shared Key; pos: top-center" aria-expanded="false" >
						<img src="{{ URL::asset('/public/vendor/internal-pages/images/share-key-icon.png') }}">
					</a>
				</nav>		
			</div>
		</div>
		<nav class="sAudit-nav detail-page-overview">
			<a>
				<span class="ajax-loader">Overview</span>
			</a>
			<a>
				<span class="ajax-loader">SEO</span>				                
			</a>
			<a>
				<span class="ajax-loader">Performance</span>		        		
			</a>
			<a>
				<span class="ajax-loader">Security</span>
		    </a>
			<a>
				<span class="ajax-loader">Miscellaneous</span>
		    </a>
		</nav>
		<div id="Overview" class="white-box detail-page-summary overviewBox p-0">
			<div class="white-box-head">
				<h5>Overview</h5>
				<p class="ajax-loader">5 days ago</p>
			</div>
			<div class="white-box-body">
				<div class="left">
					<div class="circle_percent ajax-loader" style="display:block;"></div>
				</div>
				<div class="right">
					<div class="score-for page2">
						<h1 class="ajax-loader">cpeguide.com</h1>
						<h5 class="ajax-loader">Enterprise Subscriptions - CPE Guide</h5>
						<p class="ajax-loader">Quickly satisfy your entire firms CPE credit needs in ONE place with our CPE unlimited offering. Our 24/7 accessible online library offers relevant and cutting edge materials, technology that caters to busy professionals with a personalized tracker that helps you complete the classes quickly and of course ready to print…</p>
						<p><a class="ajax-loader">https://cpeguide.com/enterprise-subscriptions/</a></p>
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
			<div class="white-box-foot">
				<div class="uk-child-width-expand@s uk-grid">
				    <div>
				        <div class="dataSingle">
			    			<svg class="ajax-loader"></svg>
			        		<p class="ajax-loader">0.58 seconds</p>
				        </div>
				    </div>
				    <div>
				        <div class="dataSingle">
			    			<svg class="ajax-loader"></svg>
			        		<p class="ajax-loader">18.89 kB</p>
				        </div>
				    </div>
				    <div>
				        <div class="dataSingle">
			    			<svg class="ajax-loader"></svg>
			        		<p class="ajax-loader">22 resources</p>
				        </div>
				    </div>
				    <div>
				        <div class="dataSingle">
			    			<svg class="ajax-loader"></svg>
			    			<p class="ajax-loader">Secure</p>
				        </div>
				    </div>
				</div>
			</div>
		</div>
		<div class="detail-page-reports">
			<div class="white-box sAudit-detail p-0">
				<div class="white-box-head">
					<h5>SEO</h5>
				</div>
				<div class="white-box-body p-0">
					<ul>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
					</ul>
				</div>
			</div>
			<div class="white-box sAudit-detail p-0">
				<div class="white-box-head">
					<h5>Performance</h5>
				</div>
				<div class="white-box-body p-0">
					<ul>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
					</ul>
				</div>
			</div>
			<div class="white-box sAudit-detail p-0">
				<div class="white-box-head">
					<h5>Security</h5>
				</div>
				<div class="white-box-body p-0">
					<ul>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
					</ul>
				</div>
			</div>
			<div class="white-box sAudit-detail p-0">
				<div class="white-box-head">
					<h5>Miscellaneous</h5>
				</div>
				<div class="white-box-body p-0">
					<ul>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
			        	<li>
							<div class="uk-grid">
						        <div class="uk-width-1-4">
						        	<h5>
						            	<svg class="ajax-loader"></svg>
						            	<span class="ajax-loader">HTTPS encryption</span>
						            </h5>
						        </div>
						        <div class="uk-width-3-4">
						        	<p class="ajax-loader">The webpage uses HTTPS encryption.</p>
						        	<p><small class="ajax-loader">The webpage uses HTTPS encryption.</small></p>
						        </div>
						    </div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection