
@extends('layout.master')

@section('styles')
    <link rel="stylesheet" href="{{$style('profile')}}">
@endsection

@section('navBar')
@parent
@endsection

@section('content')
    <div class="sectionSeparator"></div>

    <div class="row">
        <div class="col-12">
            <div class="title">
                Users <small>Registered Users List (AdminMiddleware)</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div id="root"></div>
        </div>
    </div>

    <div class="sectionSeparator"></div>

@endsection

@section('scripts')
    <script>
        window['userList'] = @json($jsonUsers)
    </script>
    <script src="{{$script('demo')}}"></script>
@endsection

