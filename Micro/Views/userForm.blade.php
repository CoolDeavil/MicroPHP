
@extends('layout.master')

@section('styles')
<link rel="stylesheet" href="{{$style('register')}}">
@endsection

@section('navBar')
@parent
@endsection

@section('content')
    <div class="sectionSeparator"></div>
    <div class="title">
        Registration <small>User Register Form</small>
    </div>
    <hr>
    <form action="{{$url('AuthorService.store')}}" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-8">
                <div class="form-group">
                    <input type="text" class="form-control form-control-sm"
                           value="{{$data['name']}}"
                           name="name" aria-label="" placeholder="Enter name">

                    <div class="vError">
                        @if(isset($data['errors']['name'])) {{$data['errors']['name']}} @endif
                    </div>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control form-control-sm"
                           value="{{$data['email']}}"
                           name="email" aria-label="" placeholder="Enter email">
                    <div class="vError">
                        @if(isset($data['errors']['email'])) {{$data['errors']['email']}} @endif
                    </div>

                </div>
                <div class="form-group">
                    <input type="password" class="form-control form-control-sm"
                           value="{{$data['pass']}}"
                           name="pass" placeholder="Password" aria-label="">
                    <div class="vError">
                        @if(isset($data['errors']['pass'])) {{$data['errors']['pass']}} @endif
                    </div>

                </div>
                <div class="form-group">
                    <select class="form-control form-control-sm" name="language" aria-label="">
                        <option value="pt" @if($data['language'] == 'pt') selected @endif>Portuguese</option>
                        <option value="en" @if($data['language'] == 'en') selected @endif>English</option>
                        <option value="fr" @if($data['language'] == 'fr') selected @endif>French</option>
                    </select>
                </div>

            </div>
            <div class="col-4">
                <img src="{{$captcha}}" class="js_captcha" alt="" style="width: 100%">
                <hr>
               <div class="row">
                   <div class="input-group">
                       <input type="text" class="form-control form-control-sm" name="captcha" placeholder="Are You Human? Type the text above"
                              aria-label="inputGroupPrepend"
                       value="{{$data['captcha']}}">
                       <div class="input-group-prepend captcha_">
                           <span class="input-group-text">
                               <i class="fas fa-refresh"></i>
                           </span>
                       </div>
                   </div>
                   <div class="vError">
                       @if(isset($data['errors']['captcha'])) {{$data['errors']['captcha']}} @endif
                   </div>

               </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-success btn-sm">Register</button>
            </div>
        </div>
    </form>
    <div class="sectionSeparator"></div>

@endsection

@section('scripts')
{{--    <script src="{{$asset('js/register.min.js')}}"></script>--}}
    <script src="{{$script('register')}}"></script>
@endsection
