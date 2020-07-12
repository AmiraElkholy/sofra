<!-- Name Field -->
<div class="input-group">
    {!! Form::label('name', 'Name:') !!}
    &nbsp;<p>{{ $category->name }}</p>
</div>

<!-- Image Field -->
<div class="input-group">
    {!! Form::label('image', 'Image:') !!}
    &nbsp;<p>{{ $category->image }}</p>
</div>

<!-- Restaurant Id Field -->
<div class="input-group">
    {!! Form::label('restaurant_id', 'Restaurant:') !!}
    &nbsp;<p>{{ $category->restaurant->name }}</p>
</div>

