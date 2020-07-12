@inject('City', 'App\Models\City')

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- City Id Field -->
<div class="form-group">
    {!! Form::label('city_id', 'City:') !!}
    {!! Form::select('city_id', $City::all()->pluck('name', 'id')->toArray(), null, ['class' => 'custom-select']) !!}
</div>

<!-- Submit Field -->
<div class="form-group ">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('districts.index') }}" class="btn btn-default">Cancel</a>
</div>
