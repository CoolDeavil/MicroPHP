<li>
    <div class="dropdown">
        <a class="lnk dropBtn"><span class="drop_language {{$app_lang}}"></span><i class="fas fa-caret-down"></i></a>
        <div class="dropdown-content" style="right:0;">
            <a class="dropLnk @if($app_lang=='pt')active @endif" href="">
                <div class="language pt"> Portuguese</div>
            </a>
            <a class="dropLnk  @if($app_lang=='en')active @endif" href="">
                <div class="language en"> English</div>
            </a>
            <a class="dropLnk  @if($app_lang=='fr')active @endif" href="">
                <div class="language fr"> French</div>
            </a>
        </div>
    </div>
</li>