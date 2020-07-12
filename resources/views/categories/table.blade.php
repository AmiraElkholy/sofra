<div class="table-responsive">
    <table class="table table-hover table-striped" id="categories-table">
        <thead>
            <tr>
                <th>Name</th>
        <th>Image</th>
        <th>Restaurant</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
            <td>{{ $category->image }}</td>
            <td>{{ $category->restaurant->name }}</td>
                <td>
                    {!! Form::open(['route' => ['categories.destroy', $category->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('categories.show', [$category->id]) }}" class='btn btn-default btn-xs'><i class="fas fa-eye"></i></a>
                        <a href="{{ route('categories.edit', [$category->id]) }}" class='btn btn-default btn-xs'><i class="fas fa-pen"></i></a>
                        {!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
