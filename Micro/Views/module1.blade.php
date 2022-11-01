
@extends('layout.master')

@section('navBar')
@parent
@endsection

@section('content')
    <div class="sectionSeparator"></div>
    <h2>Module One Root</h2>

    <hr>
    <h3>Dependency Injected AppCrypt</h3>
    <strong>{{$crypt}}</strong>
@endsection