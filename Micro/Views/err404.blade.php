<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Err404</title>
</head>
<body>
<div class="container appContent">
    <div style="height: 50px"></div>
    <div class="row">
        <div class="col-12">
           <h2> <strong style="color: crimson">404 Not Found:</strong>&emsp;{{$uri}}</h2>
            <hr>
            <a href="{{$url('Micro.routes')}}" style="text-decoration: none"><strong>[Registered Routes]</strong></a>
        </div>
    </div>

</div>
</body>
</html>