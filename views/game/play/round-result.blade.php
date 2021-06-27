<div class="flex bg-blueGray-900 h-screen">
    <div id="map" class="flex-grow relative">
        @include('game.common.pengu-countdown', ['title_counter' => ($game->current_round === $game->round_count ? t('Game ends in') : t('Next round'))])

        <div class="absolute bg-black bg-opacity-90 font-bold pl-4 pr-4 pt-1 pb-3 rounded-br-md text-emerald-500 text-lg shadow-lg grid"
             style="font-family: 'Inkwell Sans',Verdana,sans-serif;z-index: 50000;max-width: 19rem;">
            <div class="text-center text-3xl text-blue-500">{{$country->country_name}}</div>
            <div class="text-center text-lg text-cyan-400 leading-4">{{$game->state_name}}</div>
            <div class="text-center text-lg text-cyan-500  leading-6">{{$game->city_name}}</div>

            <div class="flex gap-3 justify-center py-2">
                <img src="/static/img/flags/wavy/{{strtolower($country->country_code)}}.png" width="140" alt="South Africa">
                <div class="grid">

                    <div class="flex gap-2 items-center">
                        <x-icon name="globe" class="text-gray-500"></x-icon>
                        <span>{{$country->country_code}}/{{$country->iso_3}}</span>
                    </div>
                    <div class="flex gap-2 items-center">
                        <x-icon name="globe-alt" class="text-gray-500"></x-icon>
                        <span>{{$country->tld}}</span>
                    </div>
                    <div class="flex gap-2 items-center">
                        <x-icon name="cash" class="text-gray-500"></x-icon>
                        <span>{{$country->currency_code}}</span>
                    </div>
                    <div class="flex gap-2 items-center">
                        <x-icon name="phone" class="text-gray-500"></x-icon>
                        <span>+{{$country->dialing_code}}</span>
                    </div>
                </div>
                @if($country->independent_status !== 'Yes')
                    <div><span class="text-gray-500">{{t('Status')}}: </span>{{$country->independent_status}} </div>
                @endif
            </div>

            <div class="flex gap-4 leading-4 mt-2 text-emerald-400">
                <div class="flex flex-col gap-2">
                    <div>
                        <div class="text-gray-500">{{t('Capital city')}}</div>
                        <div class="ml-2">{{$country->capital}}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">{{t('Currency')}}</div>
                        <div class="ml-2">{{$country->currency_name}}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">{{t('Driving side')}}</div>
                        <div class="ml-2">{{t($country->is_right_handed_driving ? 'Right' : 'Left')}}</div>
                    </div>
                    @foreach($languages as $lang)
                        @if($loop->first)<div><div class="text-gray-500">{{t('Languages')}}</div> @endif
                            <div class="ml-2">
                                <span >{{$lang->language_name}}</span>
                                <span class="text-gray-500"> - </span>
                                <span class="text-lightBlue-400">{{$lang->percentage}}</span>
                                <span class="text-lightBlue-600">%</span>
                            </div>
                        @if($loop->last)</div>@endif
                    @endforeach
                </div>

                <div class="flex flex-col gap-2">
                    <div>
                        <div class="text-gray-500">{{t('Population')}}</div>
                        <div class="ml-2 flex justify-between gap-2">
                            <span _="on load put ({{$country->population}}).toLocaleString() into me"></span>
                            <span class="text-amber-400">#{{$country->population_rank}}</span>
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-500">{{t('GDP per capita')}}</div>
                        <div class="ml-2 flex justify-between gap-2">
                            <span _="on load put (400).toLocaleString() into me"></span>
                            <span class="text-amber-400">#{{$country->area_rank}}</span>
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-500">{{t('Size')}} - km<sup>2</sup></div>
                        <div class="ml-2 flex justify-between gap-2">
                            <span _="on load put ({{$country->area}}).toLocaleString() into me"></span>
                            <span class="text-amber-400">#{{$country->area_rank}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div></div>
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
        maxNativeZoom: 17,
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
