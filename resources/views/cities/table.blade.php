<div class="table-responsive">
    <table class="table table-hover table-striped" id="cities-table" >
        <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($cities as $city)
            <tr>
                <td>{{ $city->name }}</td>
                <td>
                    {!! Form::open(['route' => ['cities.destroy', $city->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('cities.show', [$city->id]) }}" class='btn btn-default btn-xs'><i class="fas fa-eye"></i></a>
                        <a href="{{ route('cities.edit', [$city->id]) }}" class='btn btn-default btn-xs'><i class="fas fa-pen"></i></a>
                        {!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
