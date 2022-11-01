@php
    $isArray = function($link){
      return is_array($link)  ;
    };
@endphp

<li class="{{$active}}">
    <div class="dropdown">
        <a class="lnk dropBtn"> {{$urlDecode($label)}} <i class="fas fa-caret-down"></i></a>
        <div class="dropdown-content" style="left:0;">

            @foreach ($dropLinks as $link)

                @if($isArray($link))
                    <a class="dropLnk {{$link['active']}}" href="{{$link['route']}}"><i class="fas fa-desktop"></i> {{$urlDecode($link['label'])}}</a>

                @else
                    <div class="dropdown-divider"></div>
                @endif

            @endforeach
        </div>
    </div>
</li>