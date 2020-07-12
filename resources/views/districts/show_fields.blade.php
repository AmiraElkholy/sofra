<!-- Name Field -->
<div class="input-group">
    {!! Form::label('name', 'Name:') !!}
    &nbsp;<p> {{ $district->name }}</p>
</div>


<!-- City Id Field -->
<div class="input-group">
    {!! Form::label('city_id', 'City:') !!}
    &nbsp;<p>{{ $district->city->name }}</p>
</div>

