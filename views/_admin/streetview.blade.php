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


    L.tileLayer('/static/files/tile/{{\App\Tools\Auth::user()?->map_style_id ?? 1}}/{z}/{x}/{y}.png', {
        maxNativeZoom: 17,
        @if((\App\Tools\Auth::user()?->map_style_id ?? 1) !== 1)
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
                        console.log(json)
                        if ('lat' in json) {
                            L.marker([json.lat, json.lng], {icon: map_icon}).addTo(map);
                        }
                        document.getElementById("resp").innerHTML = json;
                    })
                }
            });
    });
</script>
