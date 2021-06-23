<div id="resp">Click to add</div>
<div id="map" class="w-full h-192"></div>
<script>
    const map = L.map('map', {
        center: [25, 0],
        zoom: 3
    });
    const map_icon = L.icon({
        iconUrl: '/static/img/markers/bobdino.png',
        iconSize: [48, 48],
        iconAnchor: [24, 48],
        tooltipAnchor: [0, -48],
    });

    const marker = L.marker([20, 20], {icon: map_icon}).addTo(map);
    L.tileLayer('/static/files/tile/{{\App\Tools\Auth::user()?->map_style_id ?? 1}}/{z}/{x}/{y}.png', {
        maxNativeZoom: 17,
        @if((\App\Tools\Auth::user()?->map_style_id ?? 1) !== 1)
            tileSize: 512,
            zoomOffset: -1
        @endif
    }).addTo(map);
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
                    resp.text().then(text => {
                        document.getElementById("resp").innerHTML = text;
                        marker.setLatLng(e.latlng);
                    })
                }
            });
    });
</script>
