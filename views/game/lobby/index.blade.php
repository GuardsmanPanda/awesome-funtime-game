<div class="flex border-b-2">
    <div class="flex-grow font-bold text-3xl text-center">{{$game->display_name}}'s Game, {{$game->round_count}} rounds
        total, {{$game->round_time}} seconds each
    </div>
    @if(\App\Tools\Auth::$user_id === $game->created_by_user_id && !$game->is_queued)
        <form hx-post="/game/{{$game->id}}/start" hx-target="this" class="flex">
            <label>
                Minutes
                <input name="countdown" type="number" value="2" class="inline w-16" min="1" max="9">
            </label>
            <button class="button-blue ml-2">Start Countdown</button>
        </form>
    @endif
</div>
<div class="flex gap-4 mt-4">
    <div id="player-table" class="w-80"></div>
    <div id="map-marker" class="w-80" hx-target="this">
        @include('game.lobby.map-marker')
    </div>
</div>

<script>
    const table = new Tabulator('#player-table', {
        ajaxURL: "/game/{{$game->id}}/player",
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

    htmx.ajax('GET', '/game/{{$game->id}}/lobby-status', '#game-status');
</script>
