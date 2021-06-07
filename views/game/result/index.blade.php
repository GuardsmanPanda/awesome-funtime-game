<div class="flex gap-4">
    <x-content-raw title="{{t('Rounds')}}" icon="users">
        <div>
            <div>&#x1f947</div>
        </div>
        <div class="flex-grow">
            <div>&#x1f947</div>
        </div>
    </x-content-raw>

    <x-content-raw title="{{t('Round Details')}}" icon="users" style="flex-grow: 1">
        Not implemented yet..
    </x-content-raw>


    <x-content-raw title="{{t('Game Result')}}" icon="users">
        <div style="width: 27rem; z-index: 500" class="">
            <div class="grid gap-2 py-1 px-2">
                @foreach($players as $player)
                    <div class="bg-gradient-to-r flex items-center px-2 py-2 rounded-md to-gray-800">
                        @if($player->rank === 1)
                            <div class="text-center font-medium text-blueGray-500 text-4xl w-10">&#x1f947</div>
                        @elseif($player->rank === 2)
                            <div class="text-center font-medium text-blueGray-500 text-4xl w-10">&#x1f948</div>
                        @elseif($player->rank === 3)
                            <div class="text-center font-medium text-blueGray-500 text-4xl w-10">&#x1f949</div>
                        @else
                            <div class="text-center font-medium text-blueGray-500 text-2xl w-10">{{$player->rank}}</div>
                        @endif
                        <img class="h-12 ml-2" src="/static/img/markers/{{$player->file_name}}" alt="Map Marker">
                        <img class="w-12 shadow-md mx-1" src="/static/img/flags/iso-small/{{$player->country_code}}.png" alt="Country Flag">
                        <div class="ml-2 flex-grow">
                            <div class="font-bold">
                                {{$player->display_name}}
                            </div>
                            <div class="font-medium text-blueGray-400 flex items-center">
                                <div class="text-teal-600 font-bold">{{round($player->points_total,2)}} <span class="text-teal-500">{{t('points')}}</span></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-content-raw>
</div>

