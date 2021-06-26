<div class="flex gap-4 flex-row flex-wrap pt-12 px-4">
    <x-content-raw title="{{t('Players')}}" icon="users">
        <x-slot name="header">
            @if(\App\Tools\Auth::$user_id === $game->created_by_user_id && !$game->is_queued)
                <form hx-post="/game/{{$game->id}}/start" hx-target="this" class="flex">
                    <label>
                        Min
                        <input name="countdown" type="number" value="2" class="inline w-16" min="1" max="9">
                    </label>
                    <button class="outline-button-lightTeal ml-2 capitalize">{{t('Start countdown')}}</button>
                </form>
            @endif
        </x-slot>
        <div id="player-table" class="w-96"></div>
    </x-content-raw>
    <div class="flex flex-col gap-4">
        <button id="map-selector" class="transform hover:shadow-xl duration-75 hover:scale-105 rounded bg-orange-300 px-2 from-orange-200 bg-gradient-to-bl shadow flex gap-2 items-center py-1 justify-between" >
            <x-icon name="map" class="text-orange-500 {{\App\Tools\Auth::user()?->map_style_id ? '': 'animate-pulse'}}"></x-icon>
            <div class="font-bold text-xl leading-normal text-orange-600 capitalize truncate" style="font-family: 'Inkwell Sans',Verdana,sans-serif;">{{t('Click to select map')}}</div>
            <x-icon name="map" class="text-orange-500 {{\App\Tools\Auth::user()?->map_style_id ? '': 'animate-pulse'}}"></x-icon>
            </button>
        <div id="map-marker" style="width: 22rem;" class="rounded flex-col bg-teal-300 p-0.5 from-teal-200 bg-gradient-to-bl shadow" hx-target="this">
            @include('game.lobby.map-marker')
        </div>
    </div>

    <div class="flex flex-col gap-4 w-96 flex-grow">
        <div class="grid grid-cols-2 gap-4">
            <x-text-card top-text="{{$game->round_count}}" bot-text="{{t('Rounds')}}"></x-text-card>
            <x-text-card top-text="{{$game->round_time}}" bot-text="{{t('Seconds')}}"></x-text-card>
        </div>
        <div>
            <x-content-raw title="{{t('Find panorama')}}" icon="globe">
                <div class="px-4 py-1 grid gap-4">Not implemented yet...</div>
            </x-content-raw>
        </div>
    </div>
    <x-content-raw title="{{t('Information')}}" icon="information-circle">
        <div class="px-4 py-1 grid gap-4 prose">
            <div>
                <div class="font-bold">Unique Countries</div>
                <div>There will not be a panorama from the same country twice in the same game.</div>
            </div>
            <div>
                <div class="font-bold">Scoring</div>
                <div>
                    Each round is scored by awarding the closest guess 100 points, reduced by 10% for each player further away.
                    <br>Such that rank 1 gets 100 points, rank 2 gets 90, rank 3 gets 81, and so forth.
                    <br>20 extra points being awarded for guessing in the correct country.
                    <br>This value is then divided by the total rounds in the game, such that if the winning player would get 100 points in a 5 round game, he would instead get 20 points.
                    <br>Thus the highest possible score in a game regardless of round count is 120.
                </div>
            </div>
        </div>
    </x-content-raw>
</div>

<script>
    const table = new Tabulator('#player-table', {
        ajaxURL: "/game/{{$game->id}}/player",
        minHeight: "100%",
        layout:"fitDataStretch",
        columns: [
            {
                title: "{{t('Flag')}}", field: "country_code", headerSort: false, formatter: "image", formatterParams: {
                    height: "26px",
                    width: "39px",
                    urlPrefix: "/static/img/flags/iso-small/",
                    urlSuffix: ".png",
                }
            },
            {
                title: "{{t('Icon')}}", field: "file_name", headerSort: false, formatter: "image", formatterParams: {
                    height: "30px",
                    width: "30px",
                    urlPrefix: "/static/img/markers/",
                }
            },
            {title: "{{t('Name')}}", field: "display_name", headerSort: false},
        ]
    })

    setInterval(function () {
        table.replaceData();
    }, 20000);

    htmx.ajax('GET', '/game/{{$game->id}}/lobby-status', '#game-status');

    document.getElementById('map-selector').onclick = function () {
        htmx.ajax('GET','/game/lobby/map-selector', htmx.find('#pop'))
            .then(res => pop.showModal());

    }
</script>
