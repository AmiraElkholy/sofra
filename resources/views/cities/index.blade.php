@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="float-left">Cities</h1>
        <br>
        <h1 class="float-right">
           <a class="btn btn-success float-right" style="margin-top: -10px;margin-bottom: 20px" href="{{ route('cities.create') }}"><i class="fas fa-plus-circle"></i> &nbsp; Add New</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('cities.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
    <br>
@endsection

