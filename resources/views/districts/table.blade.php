<div class="table-responsive">
    <table class="table table-hover table-striped" id="districts-table">
        <thead>
            <tr>
                <th>Name</th>
        <th>City</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($districts as $district)
            <tr>
                <td>{{ $district->name }}</td>
            <td>{{ $district->city->name }}</td>
                <td>
                    {!! Form::open(['route' => ['districts.destroy', $district->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('districts.show', [$district->id]) }}" class='btn btn-default btn-xs'><i class="fas fa-eye"></i></a>
                        <a href="{{ route('districts.edit', [$district->id]) }}" class='btn btn-default btn-xs'><i class="fas fa-pen"></i></a>
                        {!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
