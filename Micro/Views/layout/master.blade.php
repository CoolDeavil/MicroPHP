<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{APP_TITLE}}</title>
{{--    <link href="{{$asset('css/main.min.css')}}" rel="stylesheet">--}}
    <link href="{{$style('main')}}" rel="stylesheet">
    @yield('styles')
</head>
<body>
<form action="{{$url('Micro.switchLanguage')}}" method="POST" id="formSwitch" style="display: none">
    <input type="hidden" name="language" value="any">
</form>
@section('sideNavBar')
<div class="appSideNavOverlay">
    <div class="appSideNav">
    </div>
</div>
@show

<div class="cFab">&#9650;</div>
<div class="appHero">
{{--<div class="back parallax"></div>--}}
<div class="logo parallax">
    <div class="title main">
        MicroPHP <small>PHP 8 PSR-15 Framework</small>
    </div>
</div>
</div>
@section('navBar')
{!! $nav_bar !!}
@show
{!!  $flashMessage() !!}
<div class="container appContent">
@yield('content')
</div>

<div class="appFooter">
    <span>&#174;&emsp;Webing IT - {{$cur_time}} </span>
</div>
{{--<script src="{{$asset('js/main.min.js')}}"></script>--}}
<script src="{{$script('main')}}"></script>
@yield('scripts')

</body>
</html>



