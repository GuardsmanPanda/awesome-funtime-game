<div class="h-full flex w-full">
    <div class="flex-grow">Rating graph... work in progress..</div>
    <x-content-raw title="Ladder" icon="users" class="w-96">
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
                                <div class="text-teal-600 font-bold"> {{$player->elo_rating}} <span class="text-teal-500">Rating</span></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-content-raw>
</div>