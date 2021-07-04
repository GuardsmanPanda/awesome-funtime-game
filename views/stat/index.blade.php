<div class="flex gap-4 justify-center">
    <x-text-card top-text="{{$info->user_count}}" bot-text="{{t('Players')}}" class="w-40 bg-pink-700" bot-bg="bg-pink-800"></x-text-card>
    <x-text-card top-text="{{$info->game_count}}" bot-text="{{t('Games')}}" class="w-40 bg-blue-700" bot-bg="bg-blue-800"></x-text-card>
    <x-text-card top-text="{{$info->round_count}}" bot-text="{{t('Rounds')}}" class="w-40 bg-sky-700" bot-bg="bg-sky-800"></x-text-card>
    <x-text-card top-text="{{$info->guess_count}}" bot-text="{{t('Guesses')}}" class="w-40 bg-cyan-700" bot-bg="bg-cyan-800"></x-text-card>
    <x-text-card top-text="{{$info->panorama_count}}" bot-text="{{t('Panoramas')}}" class="w-40 bg-orange-700" bot-bg="bg-orange-800"></x-text-card>
</div>