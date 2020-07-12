@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            City
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('cities.show_fields')

                    <p><a style="margin-top: 1em;" href="{{ route('cities.index') }}" class="btn btn-default float-left">Back</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
