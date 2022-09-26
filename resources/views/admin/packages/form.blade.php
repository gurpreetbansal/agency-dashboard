<strong>Package Information</strong>
<div class="row">
    <div class="col-md-6 form-group {{ $errors->has('name') ? 'has-error' : ''}}">
        <label for="name" class="control-label">{{ 'Name' }} <span class="asterick errorStyle ">*</span></label>
        <input class="form-control" name="name" type="text" id="name" value="{{ isset($package->name) ? $package->name : ''}}" >
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
 
     <div class="col-md-6 form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    <label for="description" class="control-label">{{ 'Description' }} <span class="asterick errorStyle ">*</span></label>
    <textarea class="form-control" rows="5" name="description" type="textarea" id="description" >{{ isset($package->description) ? $package->description : ''}}</textarea>
    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
</div>

</div>

<div class="form-group {{ $errors->has('image_tag') ? 'has-error' : ''}}">
    <label for="image_tag" class="control-label">{{ 'Image Tag' }}</label>
    <input class="form-control" name="image_tag" type="text" id="image_tag" value="{{ isset($package->image_tag) ? $package->image_tag : ''}}" >
    {!! $errors->first('image_tag', '<p class="help-block">:message</p>') !!}
</div>


<div class="row">
    <div class="col-md-6 form-group {{ $errors->has('number_of_projects') ? 'has-error' : ''}}">
        <label for="number_of_projects" class="control-label">{{ 'Number Of Projects' }} <span class="asterick errorStyle ">*</span></label>
        <input class="form-control" name="number_of_projects" type="number" id="number_of_projects" value="{{ isset($package->number_of_projects) ? $package->number_of_projects : ''}}" >
        {!! $errors->first('number_of_projects', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-6 form-group {{ $errors->has('number_of_keywords') ? 'has-error' : ''}}">
        <label for="number_of_keywords" class="control-label">{{ 'Number Of Keywords' }} <span class="asterick errorStyle ">*</span></label>
        <input class="form-control" name="number_of_keywords" type="number" id="number_of_keywords" value="{{ isset($package->number_of_keywords) ? $package->number_of_keywords : ''}}" >
        {!! $errors->first('number_of_keywords', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="row">
    <div class="col-md-6 form-group {{ $errors->has('free_trial') ? 'has-error' : ''}}">
        <label for="free_trial" class="control-label">{{ 'Free Trial' }}</label>
        <div class="radio">
            <label><input name="free_trial" type="radio" value="1" {{ (isset($package) && 1 == $package->free_trial) ? 'checked' : '' }}> Yes</label>
        </div>
        <div class="radio">
            <label><input name="free_trial" type="radio" value="0" @if (isset($package)) {{ (0 == $package->free_trial) ? 'checked' : '' }} @else {{ 'checked' }} @endif> No</label>
        </div>
        {!! $errors->first('free_trial', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-6 form-group {{ $errors->has('duration') ? 'has-error' : ''}}">
        <label for="duration" class="control-label">{{ 'Duration' }}</label>
        <input class="form-control" name="duration" type="number" id="duration" value="{{ isset($package->duration) ? $package->duration : ''}}" >
        {!! $errors->first('duration', '<p class="help-block">:message</p>') !!}
    </div>
</div>


<div class="row">
    <div class="col-md-6 form-group {{ $errors->has('site_audit_page') ? 'has-error' : ''}}">
        <label for="site_audit_page" class="control-label">{{ 'Site Audit Pages' }}</label>
        <input class="form-control" name="site_audit_page" type="number" id="site_audit_page" value="{{ isset($package->site_audit_page) ? $package->site_audit_page : ''}}" >
        {!! $errors->first('site_audit_page', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="row" >
    @if($formMode === 'edit')
    @if(isset($package->package_feature))
    @foreach($package->package_feature as $feature)
    <div class="existingFeatures col-md-9 form-group {{ $errors->has('features.*') ? 'has-error' : ''}}" id="existingFeatures">
        <label for="features" class="control-label">{{ 'Features Included' }} <span class="asterick errorStyle ">*</span></label>
        
        <input class="form-control" name="features[]" type="text" id="features" value="{{ isset($feature->feature) ? $feature->feature : ''}}" ></br>
       
                             
    </div>
    <div class="col-md-3 form-group">
        <button type="button" id="deletefeature" data-id="{{$feature->id}}"><i class="fa fa-times"></i></button>
    </div>
     @endforeach
      @endif
     @else

     <div class="col-md-9 form-group {{ $errors->has('features.*') ? 'has-error' : ''}}">
         <label for="features" class="control-label">{{ 'Features Included' }} <span class="asterick errorStyle ">*</span></label>
        <input class="form-control" name="features[]" type="text" id="features" value="{{ isset($package->features) ? $package->features : ''}}" >
        {!! $errors->first('features.*', '<p class="help-block">:message</p>') !!}
       
                             
    </div>
    
    @endif
    <div class="col-md-3 form-group">
        <button type="button" id="addfeature"><i class="fa fa-plus"></i></button>
    </div>
</div>
<div id="FeatureSection"></div>

<strong>Pricing</strong>
<div class="row">
    <div class="col-md-6 form-group {{ $errors->has('monthly_amount') ? 'has-error' : ''}}">
        <label for="monthly_amount" class="control-label">{{ 'Monthly Amount ($)' }} <span class="asterick errorStyle ">*</span></label>
        <input class="form-control" name="monthly_amount" type="number" id="monthly_amount" value="{{ isset($package->monthly_amount) ? $package->monthly_amount : ''}}" >
        {!! $errors->first('monthly_amount', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-6 form-group {{ $errors->has('yearly_amount') ? 'has-error' : ''}}">
        <label for="yearly_amount" class="control-label">{{ 'Yearly Amount ($)' }} <span class="asterick errorStyle ">*</span></label>
        <input class="form-control" name="yearly_amount" type="number" id="yearly_amount" value="{{ isset($package->yearly_amount) ? $package->yearly_amount : ''}}" >
        {!! $errors->first('yearly_amount', '<p class="help-block">:message</p>') !!}
    </div>
</div>





<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
