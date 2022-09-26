<div class="form-group face-label">
	<label uk-tooltip="Very Poor">
		<input type="radio" name="overall_rating" value="1" class="overall_rating" {{$data->overall_rating == '1'?'checked':''}}>
		<span></span>
		<img src="/public/vendor/internal-pages/images/face1.svg" alt="face1">
	</label>
	<label uk-tooltip="Poor">
		<input type="radio" name="overall_rating" value="2" class="overall_rating" {{$data->overall_rating == '2'?'checked':''}}>
		<span></span>
		<img src="/public/vendor/internal-pages/images/face2.svg" alt="face2">
	</label>
	<label uk-tooltip="Average">
		<input type="radio" name="overall_rating" value="3" class="overall_rating" {{$data->overall_rating == '3'?'checked':''}}>
		<span></span>
		<img src="/public/vendor/internal-pages/images/face3.svg" alt="face3">
	</label>
	<label uk-tooltip="Good">
		<input type="radio" name="overall_rating" value="4" class="overall_rating" {{$data->overall_rating == '4'?'checked':''}}>
		<span></span>
		<img src="/public/vendor/internal-pages/images/face4.svg" alt="face4">
	</label>
	<label uk-tooltip="Excellent">
		<input type="radio" name="overall_rating" value="5" class="overall_rating" {{$data->overall_rating == '5'?'checked':''}}>
		<span></span>
		<img src="/public/vendor/internal-pages/images/face5.svg" alt="face5">
	</label>
</div>

<div class="form-group" id="cancel_type">
	<label class="form-label">Please share why you are canceling your subscription?</label>
	<textarea class="form-control description" name="canceling-info" placeholder="Write here">{{$data->description}}</textarea>
</div>

<div class="form-group">
	<label class="form-label">Would you recommend Agencydashboard to your peers</label>					
	<div class="uk-flex">
		<label><input type="radio" name="recommend" value="Yes" class="recommend" {{$data->recommend == 'Yes'?'checked':''}}> Yes</label>
		<label><input type="radio" name="recommend" value="No" class="recommend" {{$data->recommend == 'No'?'checked':''}}> No</label>
	</div>
</div>

<div class="text-left btn-group start">           
	<input type="button" class="btn btn-border red-btn-border" value="Cancel" id="cancel-feedback-button">
</div>