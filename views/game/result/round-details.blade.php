<div class="h-full flex flex-col">
    <div id="map" class="h-full">map</div>
    <div id="panorama" class="h-full">pano</div>
</div>

<script>
    pannellum.viewer('panorama', {
        "type": "equirectangular",
        "panorama": "https://funtime.gman.bot/static/files/sv-jpg/{{$round->file_name}}.jpg",
        "autoLoad": true
    });


    const marker_win = L.icon({
        iconUrl: '/static/img/markers/marker-win2.png',
        iconSize: [64, 64],
        iconAnchor: [32,  32],
    });
    const map = L.map('map', {
        center: [{{$round->y}}, {{$round->x}}],
        zoom: 5,
        worldCopyJump: true
    });
    L.tileLayer('/static/files/tile/{{\App\Tools\Auth::user()?->map_style_id ?? 1}}/{z}/{x}/{y}.png', {
        maxNativeZoom: 17,
        @if((\App\Tools\Auth::user()?->map_style_id ?? 1) !== 1)
        tileSize: 512,
        zoomOffset: -1
        @endif
    }).addTo(map);

    L.marker([{{$round->y}}, {{$round->x}}], {icon:marker_win, zIndexOffset:500}).addTo(map);

    const data = [
        @foreach($players as $player)
        {lat:{{$player->y}},lng:{{$player->x}},file_name:'{{$player->file_name}}',display_name:'{{$player->display_name}}'},
        @endforeach
    ];
    data.forEach(function (item) {
        L.marker([item.lat, (((item.lng+180)%360-180)-180)%360+180], {icon:L.icon({
                iconUrl: '/static/img/markers/'+item.file_name,
                iconSize: [64, 64],
                iconAnchor: [32, 64],
                tooltipAnchor: [0, -64],
            })})
            .addTo(map)
            .bindTooltip(item.display_name, {direction:'top', permanent:true, opacity:0.9})
            .openTooltip();
    });
</script>