
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


    <a href="{{$url('View.module2')}}">Route View</a>
    <br>

@endsection
@section('scripts')
@endsection


