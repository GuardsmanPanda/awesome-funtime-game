<div class="flex gap-4 mt-4 flex-row flex-wrap">
    <x-content-raw title="{{t('Players')}}" icon="users">
        <x-slot name="header">
            @if(\App\Tools\Auth::$user_id === $game->created_by_user_id && !$game->is_queued)
                <form hx-post="/game/{{$game->id}}/start" hx-target="this" class="flex">
                    <label>
                        Min
                        <input name="countdown" type="number" value="2" class="inline w-16" min="1" max="9">
                    </label>
                    <button class="outline-button-lightTeal ml-2">Start Countdown</button>
                </form>
            @endif
        </x-slot>
        <div id="player-table" class="w-96"></div>
    </x-content-raw>
    <div id="map-marker" style="width: 22rem;" class="rounded flex-col bg-teal-300 p-0.5 from-teal-200 bg-gradient-to-bl shadow" hx-target="this">
        @include('game.lobby.map-marker')
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
                title: "Flag", field: "country_code", headerSort: false, formatter: "image", formatterParams: {
                    height: "26px",
                    width: "39px",
                    urlPrefix: "/static/img/flags/iso-small/",
                    urlSuffix: ".png",
                }
            },
            {
                title: "Icon", field: "file_name", headerSort: false, formatter: "image", formatterParams: {
                    height: "30px",
                    width: "30px",
                    urlPrefix: "/static/img/markers/",
                }
            },
            {title: "Name", field: "display_name", headerSort: false},
        ]
    })

    setInterval(function () {
        table.replaceData();
    }, 20000);

    htmx.ajax('GET', '/game/{{$game->id}}/lobby-status', '#game-status');
</script>
