<div class="flex justify-between items-center">
    <img class="h-9" src="/static/img/markers/{{\App\Models\Marker::find(\App\Tools\Auth::user()?->map_marker_id)?->file_name ?? 'standard.png'}}" alt="Marker">
    <div class="font-bold text-xl leading-normal text-teal-600" style="font-family: 'Inkwell Sans',Verdana,sans-serif;">Choose Icon</div>
    <img class="h-9" src="/static/img/markers/{{\App\Models\Marker::find(\App\Tools\Auth::user()?->map_marker_id)?->file_name ?? 'standard.png'}}" alt="Marker">
</div>
<div class="flex flex-wrap bg-gray-50 py-2 justify-around">
    @foreach(\App\Models\Marker::all() as $marker)
        <a href="#" hx-post="/game/marker/{{$marker->id}}">
            <div style="width: 64px; height: 64px">
                <img src="/static/img/markers/{{$marker->file_name}}" data-tilt data-tilt-scale="1.20" data-tilt-reverse="true" data-tilt-max="20" alt="Marker">
            </div>
        </a>
    @endforeach
</div>
<script>
    VanillaTilt.init(document.querySelectorAll("[data-tilt]"));
</script>
