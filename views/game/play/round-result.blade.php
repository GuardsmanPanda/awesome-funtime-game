<div class="flex bg-blueGray-900 h-screen">
    <div id="map" class="flex-grow relative">
        @include('game.common.pengu-countdown', ['title_counter' => ($game->current_round === $game->round_count ? t('Game ends in') : t('Next round'))])
        <div class="w-full flex justify-center">
            <div class="absolute bg-black bg-opacity-80 font-bold px-4 py-1 rounded-b-md text-gray-500 text-2xl shadow-lg text-center flex items-center"
                 style="font-family: 'Inkwell Sans',Verdana,sans-serif;z-index: 500;">
                <img src="/static/img/flags/iso-small/{{$game->country_code}}.png" class="h-8 shadow-md" alt="Country flag">
                @if($game->city_name !== 'Unknown')
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;{{$game->city_name}}</div>
                @endif
                @if($game->state_name !== 'Unknown')
                    <div class="text-gray-400">&nbsp;&nbsp;&nbsp;&nbsp;{{$game->state_name}}</div>
                @endif
                @if($game->country_name !== 'Unknown')
                    <div class="text-gray-300">&nbsp;&nbsp;&nbsp;&nbsp;{{$game->country_name}}</div>
                @endif
            </div>
        </div>

        <div class="absolute bg-black bg-opacity-90 font-bold pl-4 pr-4 py-1 rounded-br-md text-green-500 text-xl shadow-lg leading-5 grid gap-2"
             style="font-family: 'Inkwell Sans',Verdana,sans-serif;z-index: 50000;">
            <div class="text-center text-3xl text-blue-400">{{$country->country_name}} </div>

          <div class="flex gap-3">
              <img src="https://flagcdn.com/128x96/gb.png" width="128" height="96" alt="South Africa">
              <div class="grid">
                  <div class="flex gap-2"><x-icon name="phone" class="text-gray-500"></x-icon><span>+{{$country->dialing_code}}</span></div>
                  <div class="flex gap-2"><x-icon name="globe-alt" class="text-gray-500"></x-icon><span>{{$country->tld}}</span></div>
                  <div class="flex gap-2"><x-icon name="cash" class="text-gray-500"></x-icon><span>+{{$country->dialing_code}}</span></div>
              </div>
          </div>
            @if($country->independent_status !== 'Yes')
                <div><span class="text-gray-500">{{t('Status')}}: </span>{{$country->independent_status}} </div>
            @endif
            <div><span class="text-gray-500">{{t('Currency')}}: </span>{{$country->currency_name}} <span class="text-gray-400">[{{$country->currency_code}}]</span></div>
            <div><span class="text-gray-500">{{t('Capital city')}}: </span>{{$country->capital}}</div>

            <div class="mt-1.5"><span class="text-gray-500">{{t('Population')}}: </span><span _="on load put ({{$country->population}}).toLocaleString() into me"></span> <span class="text-gray-400">#{{$country->population_rank}}</span></div>
            <div><span class="text-gray-500">{{t('Size')}}: </span><span _="on load put ({{$country->area}}).toLocaleString() into me"></span> <span class="text-gray-500">km<sup>2</sup></span> <span class="text-gray-400">#{{$country->area_rank}}</span></div>
        </div>

        @isset($game->fact_text)
            <div class="bottom-0 absolute w-full flex justify-center" style="font-family: 'Inkwell Sans',Verdana,sans-serif;z-index: 500;">
                <div class="bg-gray-800 font-medium px-3 text-gray-200 text-xl py-1">{{$game->fact_text}}</div>
            </div>
        @endisset
    </div>

    <div style="width: 27rem; z-index: 500" class="shadow-2xl">
        <div class="grid gap-3 py-2 px-4">
            @foreach($players as $player)
                <div class="flex items-center px-4 py-2 rounded-md shadow-xl bg-blueGray-800">
                    <div class="text-center font-medium text-blueGray-500 text-2xl">{{$player->rank}}</div>
                    <img class="h-12 ml-1" src="/static/img/markers/{{$player->file_name}}" alt="Map Marker">
                            <img class="w-12 shadow-md mx-1" src="/static/img/flags/iso-small/{{$player->country_code}}.png" alt="Country Flag">
                    <div class="text-blueGray-300 ml-2 flex-grow">
                        <div class="font-bold text-lg">
                            {{$player->display_name}}
                        </div>
                        <div class="font-medium text-blueGray-400 flex items-center">
                            <div>{{round($player->distance/1000,2)}} <span class="text-blueGray-500">km</span>,&nbsp;</div>
                            @if($player->is_correct_country)
                                <img src="/static/img/flags/iso-small/{{$game->country_code}}.png" class="mx-1 h-5 shadow-md" alt="Country flag">
                            @endif
                            <div class="text-yellow-400 font-bold">{{round($player->points/$game->round_count,2)}} <span class="text-blueGray-500">{{t('points')}}</span></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    const marker_win = L.icon({
        iconUrl: '/static/img/markers/marker-win2.png',
        iconSize: [64, 64],
        iconAnchor: [32,  32],
    });
    const map = L.map('map', {
        center: [{{$game->y}}, {{$game->x}}],
        zoom: 5
    });
    L.tileLayer('/static/files/tile/{{\App\Tools\Auth::user()?->map_style_id ?? 1}}/{z}/{x}/{y}.png', {
        maxNativeZoom: 16,
        @if((\App\Tools\Auth::user()?->map_style_id ?? 1) !== 1)
            tileSize: 512,
            zoomOffset: -1
        @endif
    }).addTo(map);

    L.marker([{{$game->y}}, {{$game->x}}], {icon:marker_win, zIndexOffset:500}).addTo(map);

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
    tippy('[data-tippy-content]');
</script>
