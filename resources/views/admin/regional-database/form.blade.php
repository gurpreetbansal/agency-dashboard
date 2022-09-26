<div class="form-group {{ $errors->has('short_name') ? 'has-error' : ''}}">
    <label for="short_name" class="control-label">{{ 'Short Name' }}</label>
    <input class="form-control" name="short_name" type="text" id="short_name" value="{{ isset($database->short_name) ? $database->short_name : ''}}" >
    {!! $errors->first('short_name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('long_name') ? 'has-error' : ''}}">
    <label for="long_name" class="control-label">{{ 'Long Name' }}</label>
    <input class="form-control" name="long_name" type="text" id="long_name" value="{{ isset($database->long_name) ? $database->long_name : ''}}" >
    {!! $errors->first('long_name', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('flag') ? 'has-error' : ''}}">
    <label for="flag" class="control-label">{{ 'Flag' }}</label>
    <input class="form-control" name="flag" type="file" id="flag">
    @if($formMode === 'edit')
    <img src="{{URL::asset('/public/storage/database_flags/'.$database->flag)}}"  />
    @endif
    {!! $errors->first('flag', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
