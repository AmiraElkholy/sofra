@inject('Restaurant', 'App\Models\Restaurant')


<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Image Field -->
<div class="form-group col-sm-6">
    {!! Form::label('image', 'Image:') !!}
    {!! Form::text('image', null, ['class' => 'form-control']) !!}
</div>

<!-- Restaurant Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('restaurant_id', 'Restaurant:') !!}
    {!! Form::select('restaurant_id', $Restaurant::all()->pluck('name', 'id')->toArray(), null, ['class' => 'custom-select']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('categories.index') }}" class="btn btn-default">Cancel</a>
</div>
