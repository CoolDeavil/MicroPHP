
@extends('layout.master')
@section('styles')
<link rel="stylesheet" href="{{$style('profile')}}">
@endsection

@section('sideNavBar')
@parent
@parent
@endsection
@section('navBar')
@parent
@endsection

@section('content')
    <div class="sectionSeparator"></div>


    <div class="row">
        <div class="col-12">
            <div class="title">DashBoard <small>User Profile Page</small></div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table>
                <thead>
                <tr>
                    <td> Created </td>
                    <td> Last time Edited </td>
                    <td> Last time Logged </td>
                </tr>
                </thead>
                <tr>
                    <td class="created">
                        {{$user->getTimeLine()===''?'Moments ago...':$user->getTimeLine()  }}
                    </td>
                    <td class="LastEdit">
                        {{$user->getLastEdited()===''?'Moments ago...':$user->getLastEdited()  }}
                    </td>
                    <td class="lastLog">
                        {{$user->getLastLogged()===''?'Moments ago...':$user->getLastLogged() }}
                    </td>
                </tr>

            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <form action="{{$url('AuthorService.updateProfile',['user' => $user->getId()])}}" method="POST" class="profileForm">
                <div class="form-group">
                    <input type="text" class="form-control  form-control-sm" name="name" aria-label="emailHelp" value="{{$user->getName()}}">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control form-control-sm" name="email" aria-label="emailHelp" value="{{$user->getEmail()}}">
                </div>
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="pass" value="{{$user->getPass()}}">
                <div class="row">
                    <div class="col-9">
                        <div class="form-group">
                    <textarea class="form-control form-control-sm" name="about" rows="3" aria-label="" placeholder="About me">
@if($user->getAbout() !== '')
{{$user->getAbout()}}
@endif
                    </textarea>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <select class="form-control form-control-sm" name="language" aria-label="">
                                <option value="pt"@if($user->getLang() === 'pt') selected @endif>Portuguese</option>
                                <option value="en"@if($user->getLang() === 'en') selected @endif>English</option>
                                <option value="fr"@if($user->getLang() === 'fr') selected @endif>French</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success btn-sm">Change My Profile Data</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{$script('profile')}}"></script>
@endsection
