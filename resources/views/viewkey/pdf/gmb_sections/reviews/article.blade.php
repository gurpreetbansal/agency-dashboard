@if(isset($results) && count($results) > 0)
@foreach($results as $key=>$value)
<article class="<?php if($value->review_reply!=''){ echo 'hasReply';}?>">
	<figure>
		<img src="{{$value->reviewer_profile_photo}}"  alt="reviewer-photo" />
	</figure>
	<h3>{{$value->reviewer_display_name}}</h3>
	<div class="rating">
		@if($value->rating == 'ONE')
		<i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
		@elseif($value->rating == 'TWO')
		<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
		@elseif($value->rating == 'THREE')
		<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
		@elseif($value->rating == 'FOUR')
		<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i>
		@elseif($value->rating == 'FIVE')
		<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
		@else
		<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
		@endif
		{{App\GmbLocation::calculate_weeks($value->create_time)}}
	</div>
	<p class="readmore"><?php 
	if(strlen($value->comment) > 200){
		echo substr($value->comment,0,200).'<span class="ellipsis">...</span><span class="moreText">'.substr($value->comment,200).'</span>' ;
	}else{
		echo $value->comment;
	}
	?></p>
</article>
@endforeach

@else
<p>No Reviews yet.</p>
@endif