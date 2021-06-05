<div class="absolute bg-black bg-opacity-70 font-bold px-4 pb-1 left-1/2 rounded-b-full text-gray-300 text-2xl  top-0 shadow-lg text-center"
     style="font-family: 'Inkwell Sans',Verdana,sans-serif; z-index: 500;">
    <div class="capitalize">{{$title_counter}}</div>
    <div id="countdown" class="text-emerald-400 text-4xl leading-7 tabular-nums">{{$countdown_seconds}}</div>
</div>
<div hx-get="/game/{{$game->id}}/play" hx-target="body" hx-trigger="load delay:{{$countdown_seconds}}s"></div>
<script>
    countdownStart({{$countdown_seconds}});
</script>
