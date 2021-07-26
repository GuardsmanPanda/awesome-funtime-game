<div class="grid gap-4 pt-4">
    <div>
        <div class="border-b-2 border-dashed font-bold text-2xl">Achievements</div>
        <div class="flex flex-row flex-wrap gap-4 px-4 py-2">
            @foreach($achievements as $a)
                <div class="bg-gray-200 border-2 flex pb-3 pt-1 rounded shadow-md w-72" data-tippy-content="{{$a->achievement_description}}">
                    <div class="text-3xl font-bold w-14 flex flex-col justify-center items-center text-emerald-700"><span>{{$a->current_level}}</span></div>
                    <div class="flex-grow">
                        <div class="font-bold text-center capitalize text-xl" style="font-family: 'Inkwell Sans',Verdana,sans-serif;">{{$a->achievement_name}}</div>
                        @include('_achievement.progress-bar', ['percent' => $a->current_score/$a->next_level_score, 'label' => $a->current_score . ' / ' . $a->next_level_score])
                    </div>
                    <div class="text-xs w-14 text-center flex flex-col justify-center">
                        <div>Rank</div>
                        <div>{{$a->user_rank}}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div>
    </div>
</div>

<script>
    tippy('[data-tippy-content]');
</script>
