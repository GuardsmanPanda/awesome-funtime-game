<div class="grid grid-cols-3 gap-4">
    @foreach(\App\Models\MapStyle::orderBy('id')->get() as $map)
        <button class="transform hover:scale-105 font-bold text-2xl " hx-patch="/user/map-style/{{$map->id}}">
            <img src="/static/img/map-style/{{$map->preview_img}}" alt="Map Preview" class="h-48 object-cover">
           {{t($map->map_style_name)}}
        </button>
    @endforeach
</div>

<div hx-swap-oob="true" id="pop-title" class="capitalize">{{t('Chose map style')}}</div>