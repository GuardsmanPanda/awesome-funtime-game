<div class="flex">
    <div>
        <div>&#x1f947</div>
    </div>
    <div class="flex-grow">
        <div>&#x1f947</div>
    </div>
     <div style="width: 27rem; z-index: 500" class="">
        <div class="grid gap-4 py-2 px-4">
            @foreach($players as $player)
                <div class="flex items-center px-4 py-2 rounded-md shadow-xl bg-blueGray-800">
                    <div class="text-center font-medium text-blueGray-500 text-2xl">{{$player->rank}}</div>
                    <img class="h-12 ml-1" src="/static/img/markers/{{$player->file_name}}" alt="Map Marker">
                    <img class="w-12 shadow-md mx-1" src="/static/img/flags/iso-small/{{$player->country_code}}.png" alt="Country Flag">
                    <div class="text-blueGray-300 ml-2 flex-grow">
                        <div class="font-bold">
                            {{$player->display_name}}
                        </div>
                        <div class="font-medium text-blueGray-400 flex items-center">
                            <div class="text-yellow-400">{{round($player->points_total,2)}} <span class="text-blueGray-500">{{t('points')}}</span></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

