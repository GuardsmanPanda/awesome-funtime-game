<div class="grid grid-cols-3 gap-5 p-1">
    @foreach(\App\Models\MapStyle::orderBy('id')->get() as $map)
        <button class="font-bold text-lg h-56 w-52 " hx-patch="/user/map-style/{{$map->id}}">
            <img src="/static/img/map-style/{{$map->preview_img}}" alt="Map Preview" class="object-none rounded hover:shadow-2xl duration-75 transform hover:scale-105">
           {{t($map->map_style_name)}}
        </button>
    @endforeach
</div>

<div hx-swap-oob="true" id="pop-title" class="capitalize">{{t('Chose map style')}}</div>