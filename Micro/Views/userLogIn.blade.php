
@extends('layout.master')

@section('navBar')
@parent
@endsection

@section('content')
    <div class="sectionSeparator"></div>
    <div class="row">
        <div class="col-6">
            <div class="title">
                Log In <small>User Log Form</small>
            </div>

        </div>
        <div class="col-6">
            <div class="testUser" style="display: flex">
                <div><i class="fa fa-user fa-3x"></i>&emsp;</div>
                <ul style="list-style: none; padding: 0; margin: 0">
                    <li><strong>Email</strong>: <code>john@mail.com</code></li>
                    <li><strong>Pass</strong>: <code>John0000</code></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="sectionSeparator"></div>
    <div class="row">
        <div class="col-12">
            <form action="{{$url('AuthorService.authorizeUser')}}" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="email" class="form-control" name="email" aria-label="" placeholder="Enter email"
                    value="{{$data['email']}}">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="pass" placeholder="Password" aria-label=""
                    value="{{$data['pass']}}">
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="rme" @if($data['rme']) checked @endif>
                    <label class="form-check-label" for="exampleCheck1">Remember Me</label>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Log In</button>
            </form>
        </div>
    </div>
@endsection