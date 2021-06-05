@if(!$game->is_queued)
    <div hx-get="/game/{{$game->id}}/lobby-status" hx-target="#game-status" hx-trigger="load delay:10s">Waiting for
        <span class="text-emerald-400">{{\App\Models\User::find($game->created_by_user_id)->display_name}}</span> to
        press start
    </div>
@elseif($game->game_start_at)
    <div hx-get="/game/{{$game->id}}/lobby-status" hx-target="#game-status" hx-trigger="load delay:{{$to_start}}s">Starting in:</div>
    <script>
        countdownStart({{$to_start}});
    </script>
@else
    <div hx-get="/game/{{$game->id}}/lobby-status" hx-target="#game-status" hx-trigger="load delay:10s">Game is <span
            class="text-emerald-400">Queued</span> please wait..
    </div>
@endif
