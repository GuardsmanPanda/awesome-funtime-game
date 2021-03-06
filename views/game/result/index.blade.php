<div class="flex gap-4 pt-12 px-4 h-screen pb-4">
    <div class="flex flex-col gap-4 w-80 max-h-full">
        <x-content-raw title="{{t('Rounds')}}" class="flex-grow min-h-0" icon="users">
            <div class="px-2 pb-1 overflow-y-auto h-full">
                @foreach($rounds as $round)
                    @if(!$loop->first)
                        <hr class="border-teal-400">
                    @endif
                    <div class="pb-3 pt-1 transform hover:scale-105 hover:cursor-pointer duration-50" hx-get="/game/{{$game->id}}/result/round/{{$round->id}}" hx-target="#round-details">
                        <div class="text-center font-bold text-xl truncate">{{$round->country_name}}</div>
                        <div class="flex">
                            <img src="/static/img/flags/wavy/{{strtolower($round->country_code)}}.png" width="80" alt="Wavy flag">
                            <div class="pl-2 flex flex-col justify-between leading-4">
                                @foreach(\Illuminate\Support\Facades\DB::select("
                                    SELECT u.display_name FROM round_user ru
                                    LEFT JOIN users u ON u.id = ru.user_id
                                    WHERE ru.round_id = ? ORDER BY ru.points DESC LIMIT 3", [$round->id]) as $user)
                                    <div class="flex gap-1">
                                        <img width="20" alt="rank icon" src="/static/img/icons/{{$loop->index === 0 ? '1st' : ($loop->index === 1 ? '2nd' :'3rd')}}.webp">
                                        <div class="truncate">{{$user->display_name}}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-content-raw>
        <x-content-raw title="{{t('Active games')}}" icon="users">
            <div id="active-games"></div>
        </x-content-raw>
    </div>

    <x-content-raw title="{{t('Round Details')}}" icon="users" style="flex-grow: 1">
        <div id="round-details" class="h-full">
            {{t('Click on a round to see details')}}
        </div>
    </x-content-raw>


    <x-content-raw title="{{t('Game Result')}}" icon="users" class="w-96">
        <div class="h-full overflow-y-auto">
            <div class="grid gap-2 py-1 px-2">
                @foreach($players as $player)
                    <div class="bg-gradient-to-r flex items-center px-2 py-2 rounded-md to-gray-800">
                        @if($player->rank === 1)
                            <img src="/static/img/icons/1st-place.webp" alt="1st place" width="50" height="50">
                        @elseif($player->rank === 2)
                            <img src="/static/img/icons/2nd-place.webp" alt="2nd place" width="50" height="50">
                        @elseif($player->rank === 3)
                            <img src="/static/img/icons/3rd-place.webp" alt="3rd place" width="50" height="50">
                        @else
                            <div class="text-center font-medium text-blueGray-500 text-2xl w-12">{{$player->rank}}</div>
                        @endif
                        <img class="h-12 ml-0.5" src="/static/img/markers/{{$player->file_name}}" alt="Map Marker">
                        <img class="w-12 shadow-md mx-1" src="/static/img/flags/iso-small/{{$player->country_code}}.png" alt="Country Flag">
                        <div class="ml-2 flex-grow">
                            <div class="font-bold text-lg truncate">
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
