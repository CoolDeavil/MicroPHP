
@extends('layout.master')


@section('sideNavBar')
@parent
@endsection
@section('navBar')
@parent
@endsection

@section('content')
    <div class="sectionSeparator"></div>
    <div class="title">MicroPHP <small>Website Root</small></div>
    <hr>
    <div class="sectionSeparator"></div>
    <a href="{{$url('module2')}}">Route View</a>
    <br>




@endsection
@section('scripts')
@endsection


