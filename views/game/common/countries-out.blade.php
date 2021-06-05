@if(count($out) > 0)
<div class="absolute bg-black bg-opacity-70 font-bold bottom-0 text-gray-300 text-2xl shadow-lg flex items-center overflow-hidden"
     style="font-family: 'Inkwell Sans',Verdana,sans-serif; z-index: 500;">
    <div class="px-2">{{t('Out')}}</div>
    @foreach($out as $o)
        <img class="h-11" src="/static/img/flags/iso-small/{{$o->country_code}}.png" alt="Country flag" data-tippy-content="{{$o->country_name}}">
    @endforeach
</div>
@endif
