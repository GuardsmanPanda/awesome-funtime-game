<div class="absolute font-bold right-0 top-0 drop-shadow-lg filter pointer-events-none {{$class ?? ''}}" style="font-family: 'Inkwell Sans',Verdana,sans-serif; z-index: 500;">
    <div class="relative">
        <img src="/static/img/pengu-sign.png" class="h-52" style="transform: scaleX(-1)" alt="Cutest pengu around">
        <div class="absolute capitalize opacity-70 rotate-1 text-xl text-center top-1 transform w-full">{{$title_counter}}</div>
        <div id="countdown" class="text-blueGray-800 text-3xl leading-7 tabular-nums absolute top-6 w-full text-center">{{$countdown_seconds}}</div>
    </div>


</div>
<div hx-get="/game/{{$game->id}}/play" hx-target="body" hx-trigger="load delay:{{$countdown_seconds}}s"></div>
<script>
    countdownStart({{$countdown_seconds}});
</script>
