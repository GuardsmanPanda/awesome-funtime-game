<div class="flex gap-4 justify-center">
    <x-text-card top-text="{{$info->user_count}}" bot-text="{{t('Players')}}" class="w-40 bg-pink-700" bot-bg="bg-pink-800"></x-text-card>
    <x-text-card top-text="{{$info->game_count}}" bot-text="{{t('Games')}}" class="w-40 bg-blue-700" bot-bg="bg-blue-800"></x-text-card>
    <x-text-card top-text="{{$info->round_count}}" bot-text="{{t('Rounds')}}" class="w-40 bg-sky-700" bot-bg="bg-sky-800"></x-text-card>
    <x-text-card top-text="{{$info->guess_count}}" bot-text="{{t('Guesses')}}" class="w-40 bg-cyan-700" bot-bg="bg-cyan-800"></x-text-card>
    <x-text-card top-text="{{$info->panorama_count}}" bot-text="{{t('Panoramas')}}" class="w-40 bg-orange-700" bot-bg="bg-orange-800"></x-text-card>
</div>

<button id="map-selector" class="transform hover:shadow-xl duration-75 hover:scale-105 rounded bg-orange-300 px-2 from-orange-200 bg-gradient-to-bl shadow flex gap-2 items-center py-1 justify-between">
    <x-icon name="globe" class="text-orange-500 {{\App\Tools\Auth::user()?->country_code_1 ? '': 'animate-pulse'}}"></x-icon>
    <div class="font-bold text-xl leading-normal text-orange-600 capitalize truncate" style="font-family: 'Inkwell Sans',Verdana,sans-serif;">{{t('Click to select countries')}}</div>
    <x-icon name="globe" class="text-orange-500 {{\App\Tools\Auth::user()?->country_code_1 ? '': 'animate-pulse'}}"></x-icon>
</button>

<script>
    document.getElementById('map-selector').onclick = function () {
        htmx.ajax('GET','/game/lobby/country-selector/48', htmx.find('#pop'))
            .then(res => pop.showModal());
    }
</script>