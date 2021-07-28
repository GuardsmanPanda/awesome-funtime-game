<div class="flex gap-4">
    <div class="flex-grow">
        <div class="border-b-2 border-dashed pb-1">
            <div class=" font-bold text-xl">Countries</div>
            @include('_achievement.progress-bar', ['percent' => $country_count/240, 'label' => $country_count . ' / ' . 240])
        </div>
        <div class="flex flex-row flex-wrap gap-x-4 gap-y-6 pt-2 px-2">
            @foreach($countries as $country)
                <div data-tippy-content="{{t($country['country_name']) . ', guessed correctly: ' . $country['count']}}" @class(['opacity-10' => $country['count']=== 0]) data-tilt data-tilt-scale="1.05" data-tilt-reverse="true" data-tilt-max="12">
                    <img src="/static/img/flags/wavy/{{strtolower($country['country_code'])}}.png" width="100" alt="Wavy flag">
                </div>
            @endforeach
        </div>
    </div>
    <div class="grid gap-4">
        <div class="hidden">
            <div class="border-b-2 border-dashed font-bold text-xl">Medals</div>
            <div class="flex justify-center gap-4">
                <div class="flex flex-row items-center">
                    <div class="text-center font-medium text-blueGray-500 text-3xl w-10">&#x1f947</div>
                    <div class="font-bold text-3xl">1</div>
                </div>
                <div class="flex flex-row items-center">
                    <div class="text-center font-medium text-blueGray-500 text-3xl w-10">&#x1f948</div>
                    <div class="font-bold text-3xl">1</div>
                </div>
                <div class="flex flex-row items-center">
                    <div class="text-center font-medium text-blueGray-500 text-3xl w-10">&#x1f949</div>
                    <div class="font-bold text-3xl">1</div>
                </div>
            </div>
        </div>
        <div>
            <div class="border-b-2 border-dashed font-bold text-xl">Achievements</div>
            <div class="flex flex-col gap-4 px-2 py-2">
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
    </div>
</div>

<script>
    tippy('[data-tippy-content]');
    VanillaTilt.init(document.querySelectorAll("[data-tilt]"));
</script>
