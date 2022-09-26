<div class="popup-inner image-slide">
	<div id="progressPopup">	
		<div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slider="center: true; finite: true">
	    	@if($taskActivity <> null)
		    <ul class="uk-slider-items uk-grid">
		    	<?php 
		    	$explode = explode(',',$taskActivity->file_name);
		    	?>
		    	@if(count($explode) == 1)
		    	 <li class="uk-width-1-1">
		            <div class="uk-panel">
		                <img src="{{ url('public/storage/').$explode[0] }}" alt="">
		            </div>
		        </li>
		        @else
		        @foreach($explode as $image)
		        <li class="uk-width-3-4">
		            <div class="uk-panel">
		                <img src="{{ url('public/storage/').$image }}" alt="">
		            </div>
		        </li>
		        @endforeach
		        @endif
		    </ul>
		    @else
				Progress attachment not available
			@endif	
		    <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
		    <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>

		    <ul class="uk-slider-nav uk-dotnav">
		        <li uk-slider-item="0"><a href="#">...</a></li>
		        <li uk-slider-item="1"><a href="#">...</a></li>
		        <li uk-slider-item="2"><a href="#">...</a></li>
		        <li uk-slider-item="3"><a href="#">...</a></li>
		        <li uk-slider-item="4"><a href="#">...</a></li>
		    </ul>
		</div>
	</div>
	<a class="popup-close" data-pd-popup-close="checkProgress" href="#"></a>
</div>