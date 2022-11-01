<div class="flash {{$flash['type']}}">
    <div class="flashWrapper">
        <div class="flashClose" onclick="(()=>{
    this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);
  })()"></div>
        <div class="flashTitle">
            {{$flash['title']}}
        </div>
        <div class="flashContent">
            {!! $flash['message'] !!}
        </div>
    </div>
</div>
