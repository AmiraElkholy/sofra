@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Category
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('categories.show_fields')
                    <p><a style="margin-top: 1em;" href="{{ route('categories.index') }}" class="btn btn-default">Back</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
