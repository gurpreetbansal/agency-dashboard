@php $i=0; @endphp
@if($pdfStatus == 1)
    <div uk-grid class="uk-grid BreakBefore">
        <div class="uk-width-1-1">
            <div class="single">
                <h2><img src="{{url('public/vendor/internal-pages/images/social-posts-icon.png')}}" alt> Posts</h2>                         
                <div class="white-box post-listing">
                    <div class="inner fbpost-listing">
                    
@endif
@forelse ($results as $value)
   
    @if($loop->index == 4)
    @if($pdfStatus == 1)
                    </div>
                </div>
            </div>
        </div>  
    </div>

    <div uk-grid class="uk-grid BreakBefore">
        <div class="uk-width-1-1">
            <div class="single">
                <div class="white-box post-listing">
                    <div class="inner fbpost-listing">
    
    @endif
    @endif
    <div class="single-post">
        @php $link = explode('_',$value['id']) @endphp
        <div class="post-head">
            <figure class="ajax-loader fromImage">
                <img src="{{$value['fromImage']}}" alt="posted-by">
            </figure>
            <h6>
                <big class="ajax-loader fromName">{{$value['fromName']}}</big>
                <!-- <small>hjjsdfj</small> -->
                <span class="ajax-loader datePost">{{date('M d, Y',strtotime($value['date']))}}</span>
            </h6>
        </div>
        <p class="ajax-loader postMessage">{{$value['message']}}
        </p>
    	<figure class="ajax-loader full_picture">
    		<img src="{{($value['full_picture'] <> null) ? $value['full_picture'] : url('public/vendor/internal-pages/images/no-image.jpg')}}" alt="post-image">
            <figcaption><a href="https://www.facebook.com/{{$link[0]}}/posts/{{$link[1]}}/" class="btn blue-btn" target="_blank"> <i class="fa fa-external-link"></i> View Post</a></figcaption>
    	</figure>    		
		<ul class="ajax-loader fb_post_ul">
			<li class="postReach"><span><img src="{{url('public/vendor/internal-pages/images/social-reach-icon-small.png')}}" alt="social-reach-icon-small"> {{shortNumbers($value['post_impressions_fan_unique'])}}</span> Reach</li>
			<li class="postLikes"><span><img src="{{url('public/vendor/internal-pages/images/social-likes-icon-small.png')}}" alt="social-likes-icon-small"> {{shortNumbers($value['likes'])}}</span> Likes</li>
			<li class="postClicks"><span><img src="{{url('public/vendor/internal-pages/images/social-click-icon.png')}}" alt="social-click-icon"> {{shortNumbers($value['post_engaged_users'])}}</span> Clicks</li>
			<li class="postComments"><span><img src="{{url('public/vendor/internal-pages/images/social-comment-icon.png')}}" alt="social-comment-icon"> {{shortNumbers($value['comments'])}}</span> Comments</li>
		</ul>
    </div>
    @php 
        if($pdfStatus == 1){
            $i++;
            if($i >= 6){
                break;
            }
        }
    @endphp
@empty
    <div class="single-post social-empty post-empty">
        <img src="{{url('public/vendor/internal-pages/images/no-post-icon.png')}}" alt="no-post-icon">
        <p>No Posts Found</p>
    </div>
@endforelse
@if($pdfStatus == 1) 
                    </div>
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
                </div>
            </div>
        </div>  
    </div>

@endif