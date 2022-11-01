
@extends('layout.master')
@section('styles')
<style>
    table {
        width: 100%;
    }
    table thead {
        font-weight: bolder;
        color: black;
        background-color: whitesmoke;
    }
    .method {
        color: #006d6d;;
        font-weight: bolder;
        background-color: whitesmoke;
    }
</style>
@endsection
@section('sidebar')
@parent
@endsection

@section('content')

    <div class="sectionSeparator"></div>
    <div class="title">MicroPHP<small>Registered Routes</small></div>

    <hr>
    <div class="row">
        <div class="col-12">
            <table>
                <thead>
                <tr>
                    <td>Route</td>
                    <td>Params</td>
                    <td>Controller</td>
                    <td>Action</td>
                    <td>Name</td>
                </tr>
                </thead>
                <tbody>
               {!! $tableBody !!}
                </tbody>
            </table>
        </div>
    </div>
    <div class="sectionSeparator"></div>
@endsection