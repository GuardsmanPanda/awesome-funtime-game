<div class="flex gap-2">
    <label class="flex items-center gap-2">
        Google Maps URL
        <input type="text" class="w-192" id="map-url">
    </label>
    <button class="small-button-blue" id="map-add-button">Add</button>
</div>
<div id="map" class="w-full h-192 mt-2"></div>
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

    const addGuesses = function (guesses) {
        guesses.forEach(val => {
            if (val.result) L.marker([val.lat, val.lng], {icon: map_icon}).addTo(map);
            else L.marker([val.lat, val.lng], {icon: small_icon}).addTo(map);
        });
    }

    document.getElementById("map-add-button").addEventListener("click", function() {
        const text = document.getElementById('map-url').value.split('@')[1].split(',');

        fetch('/admin/streetview/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({lat:text[0], lng:text[1], curated:true}),
        })
            .then(resp => {
                if (!resp.ok) {
                    resp.text().then(text => {
                        document.getElementById("resp").innerHTML = text;
                    })
                } else {
                    map.panTo([text[0], text[1]]);
                    document.getElementById('map-url').value = '';
                    resp.json().then(json => addGuesses(json));
                }
            });
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
                } else resp.json().then(json => addGuesses(json))
            });
    });
</script>
