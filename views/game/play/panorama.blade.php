<div class="filter drop-shadow-xl z-30 absolute h-64 hover:h-2/3 hover:opacity-100 hover:w-2/3 opacity-75 right-0 rounded-bl overflow-hidden w-96">
    <div id="map-container" style="clip-path: polygon(0 0, 100% 0, 100% 100%, 55% 100%);"
         class="w-full h-full z-10">
        <div id="map" class="h-full w-full"></div>
    </div>
</div>
@include('game.common.pengu-countdown', ['title_counter' => t('Round ends in'), 'class' => 'top-64 -right-3'])
@include('game.common.countries-out')

<div class="absolute bg-black bg-opacity-70 font-bold left-9 px-4 py-0.5 rounded-b-md text-gray-400 text-2xl z-20 shadow-lg"
     style="font-family: 'Inkwell Sans',Verdana,sans-serif;"><span class="text-gray-300"  _="on load put df({{$game->captured_date}}, 'LLLL y') into me"></span></div>

<div class="absolute font-bold bottom-7 drop-shadow-lg filter pointer-events-none" style="font-family: 'Inkwell Sans',Verdana,sans-serif; z-index: 500;">
    <div class="relative">
        <img src="/static/img/pengu-sign.png" class="h-52" alt="Cutest pengu around">
        <div class="absolute capitalize opacity-70 rotate-1 text-xl text-center top-1 transform w-full">{{t('Round')}}</div>
        <div id="countdown" class="text-blueGray-800 text-3xl leading-7 tabular-nums absolute top-6 w-full text-center">{{$game->current_round}}/{{$game->round_count}}</div>
    </div>
</div>
<script>
    pannellum.viewer('primary', {
        "type": "equirectangular",
        "panorama": "https://funtime.gman.bot/static/files/sv/{{$game->file_name}}.webp",
        "autoLoad": true
    });
</script>
<script>
    const map = L.map('map', {
        center: [25, 0],
        zoom: 1
    });
    const map_icon = L.icon({
        iconUrl: '/static/img/markers/{{$marker}}',
        iconSize: [48, 48],
        iconAnchor: [24, 48],
        tooltipAnchor: [0, -48],
    });

    const marker = L.marker([20, 20], {icon: map_icon}).addTo(map);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    map.on('click', function (e) {
        fetch('/game/{{$game->id}}/guess', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(e.latlng),
        })
            .then(resp => {
                if (!resp.ok) {
                    console.log('RESP ERROR');
                } else marker.setLatLng(e.latlng);
            });
    });

    const map_ele = document.getElementById('map-container');
    map_ele.addEventListener('mouseenter', _ => {
        map_ele.style.clipPath = '';
        map.zoomIn(1, {animate:false});
        map.invalidateSize();
    });
    map_ele.addEventListener('mouseleave', _ => {
        map_ele.style.clipPath = 'polygon(0 0, 100% 0, 100% 100%, 55% 100%)';
        map.zoomOut(1, {animate:false});
        map.invalidateSize();
    });
    tippy('[data-tippy-content]');
</script>
