@props(['botBg' => 'bg-teal-700', 'topText', 'botText'])
<div {{ $attributes->merge(['class' => 'pt-2 rounded shadow-md text-center text-white overflow-hidden']) }}>
    <div class="font-medium leading-7 mb-2 text-4xl">{{$topText}}</div>
    <div class="{{$botBg}}">{{$botText}}</div>
</div>