@extends('layouts.pdf_layout')
@section('content')
<input type="hidden" name="key" id="encriptkey" value="{{ $key }}">
<input type="hidden" class="campaign_id" name="campaign_id" value="{{ $campaign_id }}">
<input type="hidden" id="user_id" name="user_id" value="{{ $user_id }}">

<!-- Site Audit Detail PDF Content -->
<div id="SideAuditDetail">
	<div class="audit-summary box-boxshadow">
		<div class="elem-start">
			<div class="circle_percent">
				<div class="circle-donut" >
					<div class="circle_inbox"><span class="percent_text">{{ (int)$summaryTask['onpage_score'] }}</span> of 100</div>
					<input type="hidden" class="siteAudit-chart-data" value="{{ (int)$summaryTask['onpage_score'] }}">
					<canvas id="siteAudit-chart-data" width="180" height="180"></canvas>
				</div>
			</div>
			<div class="score-for">
				<p>Page score for</p>
				<h2>{{ $summaryTask['url'] }}</h2>
			</div>
		</div>
	</div>

	<div class="issue-overview">
		<div class="section-head">
			<h3>Issue Overview</h3>
		</div>

		@if(count($errorsListing['critical']) > 0)
		<section>
			<h4>Criticals</h4>
			<div class="audit-table">
				<table>
					<tbody>
						@foreach($errorsListing['critical'] as $keyName => $valueName)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ $auditLevel[$keyName] }}
								</div>
							</td>
							<!-- <td>2 tags</td> -->
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</section> 
		@endif
		@if(count($errorsListing['warning']) > 0)     	
		<section>
			<h4>Warnings</h4>
			<div class="audit-table">
				<table>
					<tbody>
						@foreach($errorsListing['warning'] as $keyName => $valueName)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/warning-icon.png')}}">
									{{ $auditLevel[$keyName] }}
								</div>
							</td>
							<!-- <td>7%</td> -->
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</section>
		@endif
	</div>


	<div class="issue-overview BreakBefore">
		<div class="section-head">
			<h3>Content optimization</h3>
		</div>
		<section>
			<h4>General</h4>
			<div class="audit-table">
				<table>
					<tbody>
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
									Status code
								</div>
							</td>
							<td><div class="text-success">{{ $summaryTask['status_code'].' OK' }}</div></td>
						</tr>
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
									HTML Size
								</div>
							</td>
							<td>{{ $summaryTask['size']/1000 }} KB</td>
						</tr>
					</tbody>
				</table>
			</div>
		</section>
		<section>
			<h4>
				Title check
				<small>{{ $summaryTask['meta']['title'] }}</small>
			</h4>
			<div class="audit-table">
				<table>
					<tbody>
						<tr>
							<td>
								<div class="d-flex">
									@if($summaryTask['meta']['title_length'] == 0)
									<img  src="{{ URL::asset('public/vendor/internal-pages/images/cross-icon.png') }}" class="sub-2 mr-1" alt="" />
									@elseif($summaryTask['meta']['title_length'] > 35)
									<img  src="{{ URL::asset('public/vendor/internal-pages/images/check-icon.png') }}" class="sub-2 mr-1" alt="" />
									@elseif($summaryTask['meta']['title_length'] < 35)
									<img  src="{{ URL::asset('public/vendor/internal-pages/images/warning-icon.png') }}" class="sub-2 mr-1" alt="" />

									@endif
									Title length
								</div>
							</td>
							<td>{{ $summaryTask['meta']['title_length'] }} characters (Recommended: 35-65 characters)</td>
						</tr>
					</tbody>
				</table>
			</div>
		</section>
		<section>
			<h4>
				Description check
				<small>{{ $summaryTask['meta']['description'] }}</small>
			</h4>
			<div class="audit-table">
				<table>
					<tbody>
						<tr>
							<td>
								<div class="d-flex">
									@if($summaryTask['meta']['description_length'] == 0)
									<img  src="{{ URL::asset('public/vendor/internal-pages/images/cross-icon.png') }}" class="sub-2 mr-1" alt="" />
									@elseif($summaryTask['meta']['description_length'] > 35)
									<img  src="{{ URL::asset('public/vendor/internal-pages/images/check-icon.png') }}" class="sub-2 mr-1" alt="" />
									@elseif($summaryTask['meta']['description_length'] < 35)
									<img  src="{{ URL::asset('public/vendor/internal-pages/images/warning-icon.png') }}" class="sub-2 mr-1" alt="" />

									@endif
									Description Length
								</div>
							</td>
							<td><?php 
							if($summaryTask['meta']['description_length'] > 0){
								echo $summaryTask['meta']['description_length'].' characters (Recomended: 70-320 characters)';
							}elseif($summaryTask['meta']['description_length'] == 0){
								echo "Description tag not found";
							}
							?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</section>
		<section>
			<h4>
				Google preview
			</h4>
			<div class="audit-table">
				<table>
					<tbody>
						<tr>
							<td>
								<article class="ads">
									<h6>
										<small>
											<a href="javascript:;">{{ str_replace('/',' > ',preg_replace( "#^[^:/.]*[:/]+#i", "", rtrim($summaryTask['url'],"/ "))) }}</a>
										</small>
										<a href="javascript:;"> {{ $summaryTask['meta']['title'] }}</a>   
									</h6>
									<p>{{ $summaryTask['meta']['description'] }}</p>
								</article>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</section>
		<section>
			<h4>
				H1 check
				<small>{{ @$summaryTask['meta']['htags']['h1'][0] }}</small>
			</h4>
			<div class="audit-table">
				<table>
					<tbody>
						<tr>
							<td>
								<div class="d-flex">
									@if(isset($summaryTask['meta']['htags']['h1']) && count($summaryTask['meta']['htags']['h1']) == 1)
									<img src=" {{ URL::asset('public/vendor/internal-pages/images/check-icon.png') }}" class="sub-2 mr-1" alt="" /> H1 count
									@else
									<img src="{{ URL::asset('public/vendor/internal-pages/images/cross-icon.png') }}"  class="sub-2 mr-1" alt="" /> H1 count
									@endif

								</div>
							</td>
							<td>{{ isset($summaryTask['meta']['htags']['h1']) ? count($summaryTask['meta']['htags']['h1']) :"0" }} tags (Recommended: 1 H1 tag) </td>
						</tr>
					</tbody>
				</table>
			</div>
		</section>
		<section>
			<h4>
				H1-H6 structure
			</h4>
			<div class="audit-table">
				<table class="tags-table">
					<thead>
						<tr>
							<th>H1</th>
							<th>H2</th>
							<th>H3</th>
							<th>H4</th>
							<th>H5</th>
							<th>H6</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td> {{ isset($summaryTask['meta']['htags']['h1']) ? count($summaryTask['meta']['htags']['h1']) : 0 }}</td>
							<td> {{ isset($summaryTask['meta']['htags']['h2']) ? count($summaryTask['meta']['htags']['h2']) : 0 }}</td>
							<td> {{ isset($summaryTask['meta']['htags']['h3']) ? count($summaryTask['meta']['htags']['h3']) : 0 }}</td>
							<td> {{ isset($summaryTask['meta']['htags']['h4']) ? count($summaryTask['meta']['htags']['h4']) : 0 }}</td>
							<td> {{ isset($summaryTask['meta']['htags']['h5']) ? count($summaryTask['meta']['htags']['h5']) : 0 }}</td>
							<td> {{ isset($summaryTask['meta']['htags']['h6']) ? count($summaryTask['meta']['htags']['h6']) : 0 }}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="ul-list">
				<ul>
					@foreach($summaryTask['meta']['htags'] as $htagKey => $htagValue)
					@if($htagValue <> null)
					@foreach($htagValue as $key => $value)
					<li><strong>{{ $htagKey }}</strong> {{ $value }}</li>
					@endforeach
					@endif
					@endforeach
				</ul>
			</div>
		</section>
		<section>
			<h4>
				Content check
			</h4>
			<div class="audit-table">
				<table>
					<tbody>
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
									Text Length
								</div>
							</td>
							<td>{{ $summaryTask['meta']['content']['plain_text_word_count'] }} characters (Recommended: more than 500 words)</td>
						</tr>
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
									Readability index
								</div>
							</td>
							<td>{{ round($summaryTask['meta']['content']['automated_readability_index'],'2') }} (Recommended: more than 10)</td>
						</tr>
					</tbody>
				</table>
			</div>
		</section>
	</div>

	<!--  Structured Data  -->

	<div class="issue-overview BreakBefore">
		<div class="section-head">
         	<h3>Structured Data</h3>
      	</div>
      	<section>
			@if(isset($summaryTask['meta']['social_media_tags']))
			@php
			echo '<div class="audit-table"><table><tbody>';
				@endphp
				@foreach($summaryTask['meta']['social_media_tags'] as $key => $value)
				<tr>
					<td>
						<div class="d-flex">
							<img src="{{ URL::asset('public/vendor/internal-pages/images/check-icon.png') }}" class="sub-2 mr-1" alt="" />
							{{ $key }}
						</div>
					</td>
					<td>{{ $value }}</td>
				</tr>
				@endforeach
				@php
			echo '</tbody></table></div>';
			@endphp
			@else
			<h4>
				Open Graph
				<small>Missing social media Open Graph</small>
			</h4>
			@endif
		</section>
  	</div>


	<div class="issue-overview">
		<div class="section-head">
			<h3>Search optimization</h3>
		</div>
		<section>
			<h4>Canonical link check</h4>
			<div class="audit-table">
				<table>
					<tbody>
						<tr>
							<td>
								<div class="d-flex">
									<a href="{{ $summaryTask['meta']['canonical'] }}">{{ $summaryTask['meta']['canonical'] }} </a>
								</div>
							</td>
							<!-- <td><div class="text-success">200 OK</div></td> -->
						</tr>
					</tbody>
				</table>
			</div>
		</section>
		<section>
			<h4>Alternate link check</h4>
			<div class="audit-table">
				<table>
					<tbody>
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									Hreflang tags
								</div>
							</td>
							<td>Hreflang tags not found</td>
						</tr>
					</tbody>
				</table>
			</div>
		</section>
	</div>

	<div class="issue-overview">
		<div class="section-head">
			<h3>Images</h3>
		</div>
		<section>
			<h4>Favicon</h4>
			<div class="audit-table">
				<table>
					<tbody>
						<tr>
							<td>
								<div class="d-flex">
									@if($summaryTask['checks']['no_favicon'] == false)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/check-icon.png') }} "
									class="sub-2 mr-1 " alt=" " />
									@else
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }} "
									class="sub-2 mr-1 " alt=" " />
									@endif
									Favicon
								</div>
							</td>
							<td><img src="{{ $summaryTask['meta']['favicon'] }} " class="sub-2 mr-1 " alt="favicon" type="image/x-icon" /></td>
						</tr>
					</tbody>
				</table>
			</div>
		</section>
		@if($images['items'] <> null)
		<section>
			<h4>Images (found {{ count($images['items']) }})</h4>
			<div class="audit-table">
				<table class="images-table">
					<thead>
						<tr>
							<th>Preview</th>
							<th>Alt Attribute</th>
							<th>Title Attribute</th>
						</tr>
					</thead>
					<tbody>

						@foreach($images['items'] as $key =>$imagvlaue)
						<tr>
							<td>
								<a href="{{ $imagvlaue['image_src'] }}" target="_blank">
									<span class="preview-img"><img src="{{ $imagvlaue['image_src'] }}" alt="" /></span>
								</a>
							</td>
							@if($imagvlaue['image_alt'] <> null)
							<td>{{$imagvlaue['image_alt']}}</td>
							@else
							<td class="text-danger">{{ '[Missed]' }}</td>
							@endif

							@if($imagvlaue['text'] <> null)
							<td>{{$imagvlaue['text']}}</td>
							@else
							<td class="text-danger">{{ '[Missed]' }} </td>
							@endif

						</tr>

						@endforeach

					</tbody>
				</table>
			</div>
		</section>
		@endif
	</div>

	<div class="issue-overview BreakBefore">
		<div class="section-head">
			<h3>Links</h3>
		</div>
		@if($externalLinks['items'] <> null)
		<section>
			<h4>External Links (found {{ count($externalLinks['items']) }})</h4>
			<div class="audit-table">
				<table class="links-table">
					<thead>
						<tr>
							<th>#</th>
							<th>Link </th>
							<th>Anchor </th>
							<th>Code</th>
						</tr>
					</thead>
					<tbody>
						@foreach($externalLinks['items'] as $key =>$imagvlaue)
						<tr>
							<td>{{ $key+1 }}</td>
							<td>
								@if($imagvlaue['link_to'] <> null)
								<a href="{{$imagvlaue['link_to']}}" target="_blank">
									{{substr($imagvlaue['link_to'], 0, 100) .((strlen($imagvlaue['link_to']) > 100) ? ' ...' : '')}}
								</a>
								@else
								{{'Missed'}}
								@endif
							</td>
							<td>{{ $imagvlaue['type']  }}</td>
							<td><div class="text-success">200 OK</div></td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</section>
		@endif

		@if($internalLinks['items'] <> null)
		<section>
			<h4>Internal Links (found {{ count($internalLinks['items']) }})</h4>
			<div class="audit-table">
				<table class="links-table">
					<thead>
						<tr>
							<th>#</th>
							<th>Link </th>
							<th>Anchor </th>
							<th>Code</th>
						</tr>
					</thead>
					<tbody>
						@foreach($internalLinks['items'] as $key =>$imagvlaue)
						<tr>
							<td>{{ $key+1 }}</td>
							<td>
								@if($imagvlaue['link_to'] <> null)
								<a href="{{$imagvlaue['link_to']}}" target="_blank">
									{{substr($imagvlaue['link_to'], 0, 100) .((strlen($imagvlaue['link_to']) > 100) ? ' ...' : '')}}
								</a>
								@else
								{{'Missed'}}
								@endif
							</td>
							<td>{{ $imagvlaue['type']  }}</td>
							<td><div class="text-success">200 OK</div></td>
						</tr>
						@endforeach

					</tbody>
				</table>
			</div>
		</section>
		@endif
	</div>


	<!-- Google PageSpeed Insights -->
	<div class="issue-overview BreakBefore">
		<div class="section-head">
			<h3>Google Page Speed (Desktop)</h3>
		</div>
		<div class="audit-summary box-boxshadow">
			<div class="elem-start">
				<div class="circle_percent">
					<div class="circle_inbox"><span class="percent_text">{{ (int)round((($valuesDesktop['lighthouseResult']['audits']['first-contentful-paint']['score'])*10) + (($valuesDesktop['lighthouseResult']['audits']['interactive']['score'])*10) + (($valuesDesktop['lighthouseResult']['audits']['total-blocking-time']['score'])*30) + (($valuesDesktop['lighthouseResult']['audits']['speed-index']['score'])*10) + (($valuesDesktop['lighthouseResult']['audits']['largest-contentful-paint']['score'])*15) + (($valuesDesktop['lighthouseResult']['audits']['cumulative-layout-shift']['score'])*15)) }}</span> of 100</div>
					<input type="hidden" class="dinsightsAuditChart" value="{{ (int)round((($valuesDesktop['lighthouseResult']['audits']['first-contentful-paint']['score'])*10) + (($valuesDesktop['lighthouseResult']['audits']['interactive']['score'])*10) + (($valuesDesktop['lighthouseResult']['audits']['total-blocking-time']['score'])*30) + (($valuesDesktop['lighthouseResult']['audits']['speed-index']['score'])*10) + (($valuesDesktop['lighthouseResult']['audits']['largest-contentful-paint']['score'])*15) + (($valuesDesktop['lighthouseResult']['audits']['cumulative-layout-shift']['score'])*15)) }}">
					<canvas id="dinsights-audit-chart" width="50" height="50"></canvas>
				</div>
		        <!-- <div class="score-for">
		        	<p>Page score for</p>
		            <h2>https://www.cbdmovers.com.au/</h2>
		        </div> -->
		    </div>
		</div>
		
		<section>
			<h4>
				Field Data
				<small>Over the previous 28-day collection period, field data shows that this page does not pass the Core Web Vitals assessment.</small>
			</h4>
			@if(isset($valuesDesktop['originLoadingExperience']))
			<div class="audit-table">
				<table>
					<tbody>
						
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/warning-icon.png')}}">
									First Contentful Paint (FCP)
								</div>
							</td>
							<td>
								<div class="color-bar">
									<span>{{ @$valuesDesktop['originLoadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile']/1000 }}s</span>
									<div class="color-bar-inner">
										@foreach($valuesDesktop['originLoadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS']['distributions'] as $key => $value)
										@if($key == 0)
										<span class="green-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@elseif($key == 1)
										<span class="orange-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@else
										<span class="red-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@endif
										@endforeach
									</div>
								</div>
							</td>
						</tr>


						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
									First Input Delay (FID)
								</div>
							</td>
							<td>
								<div class="color-bar">
									<span>{{@$valuesDesktop['originLoadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']['percentile']/1000}}s</span>
									<div class="color-bar-inner">
										@if(isset($valuesDesktop['originLoadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']))
										@foreach($valuesDesktop['originLoadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']['distributions'] as $key => $value)
										@if($key == 0)
										<span class="green-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@elseif($key == 1)
										<span class="orange-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@else
										<span class="red-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@endif
										@endforeach
										@endif
									</div>
								</div>
							</td>
						</tr> 

						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/warning-icon.png')}}">
									Largest Contentful Paint (LCP)
								</div>
							</td>
							<td>
								<div class="color-bar">
									<span>{{@$valuesDesktop['originLoadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['percentile']/1000}}s</span>
									<div class="color-bar-inner">
										@if(isset($valuesDesktop['originLoadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']))
										@foreach($valuesDesktop['originLoadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['distributions'] as $key => $value)
										@if($key == 0)
										<span class="green-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@elseif($key == 1)
										<span class="orange-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@else
										<span class="red-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@endif
										@endforeach
										@endif
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									Cumulative Layout Shift (CLS)
								</div>
							</td>
							<td>
								<div class="color-bar">
									<span>{{@$valuesDesktop['originLoadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['percentile']/1000}}s</span>
									<div class="color-bar-inner">
										@if(isset($valuesDesktop['originLoadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']))
										@foreach($valuesDesktop['originLoadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['distributions'] as $key => $value)
										@if($key == 0)
										<span class="green-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@elseif($key == 1)
										<span class="orange-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@else
										<span class="red-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@endif
										@endforeach
										@endif
									</div>
								</div>
							</td>
						</tr>

					</tbody>
				</table>
			</div>
			@endif
		</section>  

		<section>
			<h4>Lab Data</h4>
			<div class="audit-table">
				<table>
					<tbody>
						<tr>
							<td>
								<div class="d-flex">
									@if($valuesDesktop['lighthouseResult']['audits']['first-contentful-paint']['score'] <= 1.8)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/check-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@elseif($valuesDesktop['lighthouseResult']['audits']['first-contentful-paint']['score'] <= 3)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@else
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@endif
									Speed Index
								</div>
							</td>
							<td>{{@$valuesDesktop['lighthouseResult']['audits']['first-contentful-paint']['displayValue']}}</td>
						</tr>
						<tr>
							<td>
								<div class="d-flex">
									@if($valuesDesktop['lighthouseResult']['audits']['interactive']['score'] <= 3.8)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/check-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@elseif($valuesDesktop['lighthouseResult']['audits']['interactive']['score'] <= 7.3)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@else
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@endif
									{{ @$valuesDesktop['lighthouseResult']['audits']['interactive']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesDesktop['lighthouseResult']['audits']['interactive']['displayValue'] }}</td>
						</tr>
						<tr>
							<td>
								<div class="d-flex">
									@if($valuesDesktop['lighthouseResult']['audits']['total-blocking-time']['score'] <= 200)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/check-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@elseif($valuesDesktop['lighthouseResult']['audits']['total-blocking-time']['score'] <= 600)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@else
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@endif
									{{ @$valuesDesktop['lighthouseResult']['audits']['total-blocking-time']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesDesktop['lighthouseResult']['audits']['total-blocking-time']['displayValue'] }}</td>
						</tr>

						<tr>
							<td>
								<div class="d-flex">
									@if($valuesDesktop['lighthouseResult']['audits']['speed-index']['score'] <= 200)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/check-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@elseif($valuesDesktop['lighthouseResult']['audits']['speed-index']['score'] <= 600)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@else
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@endif
									{{ @$valuesDesktop['lighthouseResult']['audits']['speed-index']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesDesktop['lighthouseResult']['audits']['speed-index']['displayValue'] }}</td>
						</tr>

						<tr>
							<td>
								<div class="d-flex">
									@if($valuesDesktop['lighthouseResult']['audits']['largest-contentful-paint']['score'] <= 200)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/check-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@elseif($valuesDesktop['lighthouseResult']['audits']['largest-contentful-paint']['score'] <= 600)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@else
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@endif
									{{ @$valuesDesktop['lighthouseResult']['audits']['largest-contentful-paint']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesDesktop['lighthouseResult']['audits']['largest-contentful-paint']['displayValue'] }}</td>
						</tr>
						<tr>
							<td>
								<div class="d-flex">
									@if($valuesDesktop['lighthouseResult']['audits']['largest-contentful-paint']['score'] <= 200)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/check-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@elseif($valuesDesktop['lighthouseResult']['audits']['largest-contentful-paint']['score'] <= 600)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@else
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@endif
									{{ @$valuesDesktop['lighthouseResult']['audits']['largest-contentful-paint']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesDesktop['lighthouseResult']['audits']['largest-contentful-paint']['displayValue'] }}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="image-box">
				<p>Values are estimated and may vary. The performance score is calculated directly from these metrics.</p>

				<div class="data-img">
					<div class="uk-flex uk-flex-wrap uk-flex-wrap-around">
						@if(isset($valuesDesktop['lighthouseResult']['audits']['screenshot-thumbnails']['details']['items']))
						@foreach($valuesDesktop['lighthouseResult']['audits']['screenshot-thumbnails']['details']['items'] as $keyImg => $valueImg)
						<span><img src="{{ $valueImg['data'] }}" alt="" /></span>
						@endforeach
						@endif
					</div>
				</div>


			</div>
		</section>

		<section>
			<h4>Opportunities</h4>
			<div class="audit-table">
				<table>
					<tbody>
						@if((int) @$valuesDesktop['lighthouseResult']['audits']['server-response-time']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['server-response-time']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesDesktop['lighthouseResult']['audits']['server-response-time']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['modern-image-formats']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['modern-image-formats']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesDesktop['lighthouseResult']['audits']['modern-image-formats']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['unused-javascript']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['unused-javascript']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesDesktop['lighthouseResult']['audits']['unused-javascript']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['efficient-animated-content']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['efficient-animated-content']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesDesktop['lighthouseResult']['audits']['efficient-animated-content']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['render-blocking-resources']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['render-blocking-resources']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesDesktop['lighthouseResult']['audits']['render-blocking-resources']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['uses-optimized-images']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['uses-optimized-images']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesDesktop['lighthouseResult']['audits']['uses-optimized-images']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['uses-responsive-images']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['uses-responsive-images']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesDesktop['lighthouseResult']['audits']['uses-responsive-images']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['usesunused-css-rules']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['unused-css-rules']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesDesktop['lighthouseResult']['audits']['unused-css-rules']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['offscreen-images']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['offscreen-images']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesDesktop['lighthouseResult']['audits']['offscreen-images']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['uses-rel-preload']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['uses-rel-preload']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesDesktop['lighthouseResult']['audits']['uses-rel-preload']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['preload-lcp-image']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['preload-lcp-image']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesDesktop['lighthouseResult']['audits']['preload-lcp-image']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['preload-lcp-image']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['preload-lcp-image']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesDesktop['lighthouseResult']['audits']['preload-lcp-image']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

					</tbody>
				</table>
			</div>
		</section>
		
		<section>
			<h4>
				Diagnostics
				<small>These suggestions can help your page load faster. They don't directly affect the Performance score.</small>
			</h4>
			<div class="audit-table">
				<table>
					<tbody>
						@if((int) @$valuesDesktop['lighthouseResult']['audits']['uses-long-cache-ttl']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['uses-long-cache-ttl']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['font-display']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['font-display']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['uses-passive-event-listeners']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['uses-passive-event-listeners']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['unsized-images']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['unsized-images']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['total-byte-weight']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['total-byte-weight']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['mainthread-work-breakdown']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['mainthread-work-breakdown']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['critical-request-chains']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['critical-request-chains']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['user-timings']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['user-timings']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['resource-summary']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['resource-summary']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['third-party-facades']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['third-party-facades']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['largest-contentful-paint-element']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['largest-contentful-paint-element']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['layout-shift-elements']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['layout-shift-elements']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['long-tasks']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['long-tasks']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesDesktop['lighthouseResult']['audits']['non-composited-animations']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesDesktop['lighthouseResult']['audits']['non-composited-animations']['title'] }}
								</div>
							</td>
						</tr>
						@endif


					</tbody>
				</table>
			</div>
		</section>
	</div>

	<!-- Google PageSpeed Insights -->
	<div class="issue-overview BreakBefore">
		<div class="section-head">
			<h3>Google Page Speed (Mobile)</h3>
		</div>
		<div class="audit-summary box-boxshadow">
			<div class="elem-start">
				<div class="circle_percent">
					<div class="circle_inbox"><span class="percent_text">{{ (int)round((($valuesMobile['lighthouseResult']['audits']['first-contentful-paint']['score'])*10) + (($valuesMobile['lighthouseResult']['audits']['interactive']['score'])*10) + (($valuesMobile['lighthouseResult']['audits']['total-blocking-time']['score'])*30) + (($valuesMobile['lighthouseResult']['audits']['speed-index']['score'])*10) + (($valuesMobile['lighthouseResult']['audits']['largest-contentful-paint']['score'])*15) + (($valuesMobile['lighthouseResult']['audits']['cumulative-layout-shift']['score'])*15)) }}</span> of 100</div>
					<input type="hidden" class="minsightsAuditChart" value="{{ (int)round((($valuesMobile['lighthouseResult']['audits']['first-contentful-paint']['score'])*10) + (($valuesMobile['lighthouseResult']['audits']['interactive']['score'])*10) + (($valuesMobile['lighthouseResult']['audits']['total-blocking-time']['score'])*30) + (($valuesMobile['lighthouseResult']['audits']['speed-index']['score'])*10) + (($valuesMobile['lighthouseResult']['audits']['largest-contentful-paint']['score'])*15) + (($valuesMobile['lighthouseResult']['audits']['cumulative-layout-shift']['score'])*15)) }}">
					<canvas id="minsights-audit-chart" width="50" height="50"></canvas>
				</div>
		        <!-- <div class="score-for">
		        	<p>Page score for</p>
		            <h2>https://www.cbdmovers.com.au/</h2>
		        </div> -->
		    </div>
		</div>
		<section>
			<h4>
				Field Data
				<small>Over the previous 28-day collection period, field data shows that this page does not pass the Core Web Vitals assessment.</small>
			</h4>
			@if(isset($valuesMobile['originLoadingExperience']))
			<div class="audit-table">
				<table>
					<tbody>
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/warning-icon.png')}}">
									First Contentful Paint (FCP)
								</div>
							</td>
							<td>
								<div class="color-bar">
									<span>{{ @$valuesMobile['originLoadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS']['percentile']/1000 }}s</span>
									<div class="color-bar-inner">
										@foreach($valuesMobile['originLoadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS']['distributions'] as $key => $value)
										@if($key == 0)
										<span class="green-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@elseif($key == 1)
										<span class="orange-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@else
										<span class="red-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@endif
										@endforeach
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/check-icon.png')}}">
									First Input Delay (FID)
								</div>
							</td>
							<td>
								<div class="color-bar">
									<span>{{@$valuesMobile['originLoadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']['percentile']/1000}}s</span>
									<div class="color-bar-inner">
										@if(isset($valuesMobile['originLoadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']))
										@foreach($valuesMobile['originLoadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']['distributions'] as $key => $value)
										@if($key == 0)
										<span class="green-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@elseif($key == 1)
										<span class="orange-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@else
										<span class="red-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@endif
										@endforeach
										@endif
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/warning-icon.png')}}">
									Largest Contentful Paint (LCP)
								</div>
							</td>
							<td>
								<div class="color-bar">
									<span>{{@$valuesMobile['originLoadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['percentile']/1000}}s</span>
									<div class="color-bar-inner">
										@if(isset($valuesMobile['originLoadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']))
										@foreach($valuesMobile['originLoadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['distributions'] as $key => $value)
										@if($key == 0)
										<span class="green-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@elseif($key == 1)
										<span class="orange-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@else
										<span class="red-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@endif
										@endforeach
										@endif
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									Cumulative Layout Shift (CLS)
								</div>
							</td>
							<td>
								<div class="color-bar">
									<span>{{@$valuesMobile['originLoadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['percentile']/1000}}s</span>
									<div class="color-bar-inner">
										@if(isset($valuesMobile['originLoadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']))
										@foreach($valuesMobile['originLoadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['distributions'] as $key => $value)
										@if($key == 0)
										<span class="green-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@elseif($key == 1)
										<span class="orange-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@else
										<span class="red-light" style="flex-grow: {{ round($value['proportion'] * 100) }}">{{ round($value['proportion'] * 100) }}%</span>
										@endif
										@endforeach
										@endif
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			@endif
		</section>  

		<section>
			<h4>Lab Data</h4>
			<div class="audit-table">
				<table>
					<tbody>
						<tr>
							<td>
								<div class="d-flex">
									@if($valuesMobile['lighthouseResult']['audits']['first-contentful-paint']['score'] <= 1.8)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/check-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@elseif($valuesMobile['lighthouseResult']['audits']['first-contentful-paint']['score'] <= 3)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@else
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@endif
									Speed Index
								</div>
							</td>
							<td>{{@$valuesMobile['lighthouseResult']['audits']['first-contentful-paint']['displayValue']}}</td>
						</tr>
						<tr>
							<td>
								<div class="d-flex">
									@if($valuesMobile['lighthouseResult']['audits']['interactive']['score'] <= 3.8)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/check-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@elseif($valuesMobile['lighthouseResult']['audits']['interactive']['score'] <= 7.3)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@else
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@endif
									{{ @$valuesMobile['lighthouseResult']['audits']['interactive']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesMobile['lighthouseResult']['audits']['interactive']['displayValue'] }}</td>
						</tr>
						<tr>
							<td>
								<div class="d-flex">
									@if($valuesMobile['lighthouseResult']['audits']['total-blocking-time']['score'] <= 200)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/check-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@elseif($valuesMobile['lighthouseResult']['audits']['total-blocking-time']['score'] <= 600)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@else
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@endif
									{{ @$valuesMobile['lighthouseResult']['audits']['total-blocking-time']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesMobile['lighthouseResult']['audits']['total-blocking-time']['displayValue'] }}</td>
						</tr>

						<tr>
							<td>
								<div class="d-flex">
									@if($valuesMobile['lighthouseResult']['audits']['speed-index']['score'] <= 200)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/check-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@elseif($valuesMobile['lighthouseResult']['audits']['speed-index']['score'] <= 600)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@else
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@endif
									{{ @$valuesMobile['lighthouseResult']['audits']['speed-index']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesMobile['lighthouseResult']['audits']['speed-index']['displayValue'] }}</td>
						</tr>

						<tr>
							<td>
								<div class="d-flex">
									@if($valuesMobile['lighthouseResult']['audits']['largest-contentful-paint']['score'] <= 200)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/check-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@elseif($valuesMobile['lighthouseResult']['audits']['largest-contentful-paint']['score'] <= 600)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@else
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@endif
									{{ @$valuesMobile['lighthouseResult']['audits']['largest-contentful-paint']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesMobile['lighthouseResult']['audits']['largest-contentful-paint']['displayValue'] }}</td>
						</tr>
						<tr>
							<td>
								<div class="d-flex">
									@if($valuesMobile['lighthouseResult']['audits']['largest-contentful-paint']['score'] <= 200)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/check-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@elseif($valuesMobile['lighthouseResult']['audits']['largest-contentful-paint']['score'] <= 600)
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/warning-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@else
									<img src="{{ URL::asset('/public/vendor/internal-pages/images/cross-icon.png') }} " class="sub-2 mr-1 " alt=" " />
									@endif
									{{ @$valuesMobile['lighthouseResult']['audits']['largest-contentful-paint']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesMobile['lighthouseResult']['audits']['largest-contentful-paint']['displayValue'] }}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="image-box">
				<p>Values are estimated and may vary. The performance score is calculated directly from these metrics.</p>

				<div class="data-img">
					<div class="uk-flex uk-flex-wrap uk-flex-wrap-around">
						@if(isset($valuesMobile['lighthouseResult']['audits']['screenshot-thumbnails']['details']['items']))
						@foreach($valuesMobile['lighthouseResult']['audits']['screenshot-thumbnails']['details']['items'] as $keyImg => $valueImg)
						<span><img src="{{ $valueImg['data'] }}" alt="" /></span>
						@endforeach
						@endif
					</div>
				</div>


			</div>
		</section>

		<section>
			<h4>Opportunities</h4>
			<div class="audit-table">
				<table>
					<tbody>
						@if((int) @$valuesMobile['lighthouseResult']['audits']['server-response-time']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['server-response-time']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesMobile['lighthouseResult']['audits']['server-response-time']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['modern-image-formats']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['modern-image-formats']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesMobile['lighthouseResult']['audits']['modern-image-formats']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['unused-javascript']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['unused-javascript']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesMobile['lighthouseResult']['audits']['unused-javascript']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['efficient-animated-content']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['efficient-animated-content']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesMobile['lighthouseResult']['audits']['efficient-animated-content']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['render-blocking-resources']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['render-blocking-resources']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesMobile['lighthouseResult']['audits']['render-blocking-resources']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['uses-optimized-images']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['uses-optimized-images']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesMobile['lighthouseResult']['audits']['uses-optimized-images']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['uses-responsive-images']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['uses-responsive-images']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesMobile['lighthouseResult']['audits']['uses-responsive-images']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['usesunused-css-rules']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['unused-css-rules']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesMobile['lighthouseResult']['audits']['unused-css-rules']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['offscreen-images']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['offscreen-images']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesMobile['lighthouseResult']['audits']['offscreen-images']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['uses-rel-preload']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['uses-rel-preload']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesMobile['lighthouseResult']['audits']['uses-rel-preload']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['preload-lcp-image']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['preload-lcp-image']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesMobile['lighthouseResult']['audits']['preload-lcp-image']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['preload-lcp-image']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['preload-lcp-image']['title'] }}
								</div>
							</td>
							<td>{{ @$valuesMobile['lighthouseResult']['audits']['preload-lcp-image']['displayValue'] }} <span class="highlight-color red-light mr-2 uk-width-expand"></span></td>
						</tr>
						@endif

					</tbody>
				</table>
			</div>
		</section>

		<section>
			<h4>
				Diagnostics
				<small>These suggestions can help your page load faster. They don't directly affect the Performance score.</small>
			</h4>
			<div class="audit-table">
				<table>
					<tbody>
						@if((int) @$valuesMobile['lighthouseResult']['audits']['uses-long-cache-ttl']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['uses-long-cache-ttl']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['font-display']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['font-display']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['uses-passive-event-listeners']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['uses-passive-event-listeners']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['unsized-images']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['unsized-images']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['total-byte-weight']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['total-byte-weight']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['mainthread-work-breakdown']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['mainthread-work-breakdown']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['critical-request-chains']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['critical-request-chains']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['user-timings']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['user-timings']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['resource-summary']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['resource-summary']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['third-party-facades']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['third-party-facades']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['largest-contentful-paint-element']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['largest-contentful-paint-element']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['layout-shift-elements']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['layout-shift-elements']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['long-tasks']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['long-tasks']['title'] }}
								</div>
							</td>
						</tr>
						@endif

						@if((int) @$valuesMobile['lighthouseResult']['audits']['non-composited-animations']['numericValue'] >= 600)
						<tr>
							<td>
								<div class="d-flex">
									<img src="{{URL::asset('public/vendor/internal-pages/images/cross-icon.png')}}">
									{{ @$valuesMobile['lighthouseResult']['audits']['non-composited-animations']['title'] }}
								</div>
							</td>
						</tr>
						@endif


					</tbody>
				</table>
			</div>
		</section>
	</div>



</div>

@endsection