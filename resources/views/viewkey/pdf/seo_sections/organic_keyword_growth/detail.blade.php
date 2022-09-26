@extends('layouts.vendor_internal_pages')
@section('content')
<input type="hidden" class="campaignID" value="{{$campaign_id}}">
<div class="project-detail-body">
	<!-- Project Tabs Content -->
	<div class="tab-content ">
		<!-- Search Console Row -->
		<div class="white-box pa-0 mb-40 white-box-handle">
			<div class="white-box-head">
				<div class="left">
					<div class="loader h-33 half-px"></div>
					<div class="heading">
						<img src="{{URL::asset('public/vendor/internal-pages/images/organic-keywords-img.png')}}">
						<h2>Organic Keyword Growth
							<span uk-tooltip="title: Organic Keyword Growth Data; pos: top-left"
							class="fa fa-info-circle"></span></h2>
						</div>
					</div>
					<div class="right">
						<div class="loader h-33 half-px"></div>
						<div class="btn-group">
            				<a href="{{url('/generate_organic_keyword_excel/'.$campaign_id)}}"  target="_blank" class="btn icon-btn color-green" uk-tooltip="title: Generate Excel File; pos: top-center" aria-expanded="false"><img src="{{URL::asset('public/vendor/internal-pages/images/excel-icon.png')}}"></a>
           
        				</div>
					</div>
				</div>

				<div class="white-box-body">
					<div class="loader h-300-table"></div>
					<div class="chart h-360 mb-40">
						<canvas id="new-keywordsCanvas"></canvas>
					</div>

					<div class="project-table-cover">

						<div class="project-table-head">
							<div class="project-entries">
								<label>
									Show
									<select id="extra-organic-limit">
										<option value="100">100</option>
										<option value="200">200</option>
										<option value="300">300</option>
									</select>
									entries
								</label>
							</div>
							<div class="project-search">
								<form>
									<input type="text" placeholder="Search..." class="organic-keyword-search">
									<button type="submit"><span uk-icon="icon: search"></span></button>
								</form>
							</div>
						</div>

						<div class="project-table-body">
							<table id="extra-organix">
								<thead>
									<tr>
										<th class="organic_sorting" data-sorting_type="asc" data-column_name="keywords">
											<span uk-icon="arrow-up"></span>
											<span uk-icon="arrow-down"></span>
											Keyword
										</th>
										<th class="organic_sorting" data-sorting_type="asc" data-column_name="position">
											<span uk-icon="arrow-up"></span>
											<span uk-icon="arrow-down"></span>
											Position
										</th>
										<th class="organic_sorting" data-sorting_type="asc" data-column_name="search_volume">
											<span uk-icon="arrow-up"></span>
											<span uk-icon="arrow-down"></span>
											Volume
										</th>
										<th class="organic_sorting" data-sorting_type="asc" data-column_name="cpc">
											<span uk-icon="arrow-up"></span>
											<span uk-icon="arrow-down"></span>
											CPC(USD)
										</th>
										<th class="organic_sorting" data-sorting_type="asc" data-column_name="traffic">
											<span uk-icon="arrow-up"></span>
											<span uk-icon="arrow-down"></span>
											Traffic %
										</th>
									</tr>
								</thead>

								<tbody>
									@include('vendor.seo_sections.organic_keyword_growth.detail_data')
								</tbody>
							</table>
						</div>

						<input type="hidden" name="hidden_page" id="hidden_page" value="1" />
						<input type="hidden" name="hidden_column_name" id="hidden_column_name" value="position" />
						<input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
						<input type="hidden" name="extraOrganiclimit" id="extraOrganiclimit" value="100" />
						<input type="hidden" name="search" id="extraOrganicSearch" value="" />

						<div class="project-table-foot extra-organix-foot">
							@include('vendor.seo_sections.organic_keyword_growth.detail_pagination')

						</div>
					</div>

				</div>

			</div>

			<!-- Search Console Row End -->
		</div>
		<!-- Tab 1 SEO End -->
	</div>
</div>
<!-- Project Tabs Content End -->
</div>
@endsection