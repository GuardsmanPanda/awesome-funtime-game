<div id="resp">Click to add</div>
<div id="map" class="w-full h-192"></div>
<script>
    const map = L.map('map', {
        center: [25, 0],
        zoom: 3,
        worldCopyJump: true
    });
    const map_icon = L.icon({
        iconUrl: '/static/img/markers/bobdino.png',
        iconSize: [48, 48],
        iconAnchor: [24, 48],
        tooltipAnchor: [0, -48],
    });
    const small_icon = L.icon({
        iconUrl: '/static/img/markers/standard.png',
        iconSize: [24, 24],
        iconAnchor: [12, 24],
    });


    L.tileLayer('/static/files/tile/{{\App\Tools\Auth::user()?->map_style_id ?? 4}}/{z}/{x}/{y}.png', {
        maxNativeZoom: 17,
        @if((\App\Tools\Auth::user()?->map_style_id ?? 4) !== 1)
            tileSize: 512,
            zoomOffset: -1
        @endif
    }).addTo(map);


    fetch('/admin/streetview/list')
        .then(resp => resp.json())
        .then(json => {
            const markLayer = L.markerClusterGroup({maxClusterRadius: 40});
            const tmp = [];
            json.forEach(item => {
                tmp.push(L.marker([item.lat, item.lng]))
            })
            markLayer.addLayers(tmp);
            map.addLayer(markLayer);
        });


    map.on('click', function (e) {
        fetch('/admin/streetview/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(e.latlng),
        })
            .then(resp => {
                if (!resp.ok) {
                    resp.text().then(text => {
                        document.getElementById("resp").innerHTML = text;
                    })
                } else {
                    resp.json().then(json => {
                        json.forEach(val => {
                            if (val.result) L.marker([val.lat, val.lng], {icon: map_icon}).addTo(map);
                            else L.marker([val.lat, val.lng], {icon: small_icon}).addTo(map);
                        });
                    })
                }
            });
    });
</script>
