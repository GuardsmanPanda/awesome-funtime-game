<div class="flex gap-4 pt-12 px-4 h-screen pb-4">
    <div class="flex flex-col gap-4">
        <x-content-raw title="{{t('Rounds')}}" icon="users">
            <div>
                <div>&#x1f947</div>
            </div>
            <div class="flex-grow">
                <div>&#x1f947</div>
            </div>
        </x-content-raw>
        <x-content-raw title="{{t('Active Games')}}" icon="users">
            <div id="active-games"></div>
        </x-content-raw>
    </div>

    <x-content-raw title="{{t('Round Details')}}" icon="users" style="flex-grow: 1">
        Not implemented yet..
    </x-content-raw>


    <x-content-raw title="{{t('Game Result')}}" icon="users">
        <div style="width: 27rem; z-index: 500" class="h-full overflow-auto">
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
                            <div class="font-bold text-lg">
                                {{$player->display_name}}
                            </div>
                            <div class="font-medium text-blueGray-400 flex items-center">
                                <div class="text-teal-600 font-bold">{{round($player->points_total/$game->round_count,2)}} <span class="text-teal-500">{{t('points')}}</span></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-content-raw>
</div>


<script>
    const active = new Tabulator('#active-games', {
        ajaxURL: '/game/active',
        columns: [
            {title:"ID", field: "id", headerSort:false},
            {title:"{{t('Created by')}}", field: "display_name", headerSort:false},
            {title:"{{t('Players')}}", field: "player_count", headerSort:false},
            {title:"{{t('Play')}}", field: "id", headerSort:false, formatter: function (cell, formatterParams, onRendered ) {
                    const elem = document.createElement('a');
                    elem.setAttribute('href', '/game/' + cell.getValue() + '/lobby');
                    elem.setAttribute('class', 'small-button-blue');
                    elem.text = "{{t('Play')}}";
                    return elem;
                }
            },
        ]
    });

    setInterval(function () {
        active.replaceData();
    }, 20000);
</script>
