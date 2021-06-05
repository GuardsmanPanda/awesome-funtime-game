<div class="flex"><b class="self-center">Currently Chosen:</b><img class="h-10"
                                                                   src="/static/img/markers/{{\App\Models\Marker::find(\App\Tools\Auth::user()->map_marker_id)->file_name}}"
                                                                   alt="Marker"></div>
<div class="flex flex-wrap">
    @foreach (\App\Models\Marker::all() as $marker)
        <a href="#" hx-post="/game/marker/{{$marker->id}}">
            <div style="width: 64px; height: 64px">
                <img src="/static/img/markers/{{$marker->file_name}}"
                     data-tilt data-tilt-scale="1.20" data-tilt-reverse="true" data-tilt-max="20"
                     alt="Marker">
            </div>
        </a>
    @endforeach
</div>
<script>
    VanillaTilt.init(document.querySelectorAll("[data-tilt]"));
</script>
