@if($pdfStatus == 1)  <div class="inner fbreviews-listing" > @endif
@php $i=0; @endphp
@forelse ($results as $key => $value)
	<?php 		
		$recommendation = 'Recommended';
	    $class = 'green';
	    if($value['recommendation'] == 'negative'){
	        $recommendation = 'Not Recommended';
	        $class = 'red';
	    }
	?>
	<div class="single-review">
		<div class="inner-head">
			<figure class="reviewerImage ajax-loader"><img src="{{$value['reviewerimage'] ?? ''}}" alt></figure>
			<h6 class="reviewerName ajax-loader">{{$value['reviewer']}}<span class="{{$class}}">{{$recommendation}}</span></h6>
			<p class="reviewDate ajax-loader">{{date('M d, Y',strtotime($key))}}</p>
		</div>
		<div class="body"><p class="reviewText ajax-loader">{{$value['review_text']}}</p></div></div>
	@php 
        if($pdfStatus == 1){
            $i++;
            if($i >= 6){
                break;
            }
        }
    @endphp
@empty
<div class="single-review social-empty review-empty">
	<img src="{{url('public/vendor/internal-pages/images/no-review-icon.png')}}" alt>
	<p>No Reviews Found</p>
</div>
@endforelse
</div>
@if($pdfStatus == 1)
@if(isset($results) && !empty($results) && ($results->total() > 0))
@if($results->total() > 6)
<div class="uk-text-center pa-20">
	<p class="mb-0">
		<a href="{{url('/project-detail/'.$campaign->share_key)}}" target="_blank" class="btn blue-btn">To view more
		Click here <i class="fa fa-external-link"></i></a>
	</p>
</div>
@endif
@endif
@endif