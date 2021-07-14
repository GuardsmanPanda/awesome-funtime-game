@props(['title', 'icon' => ''])
<div {{ $attributes->merge(['class' => 'rounded flex-col flex bg-teal-300 p-0.5 from-teal-200 bg-gradient-to-bl shadow']) }}>
    <div class="px-2 pt-0.5 pb-1 flex items-center justify-between text-teal-600">
        <div class="flex gap-1 items-center">
            <x-icon name="{{$icon}}"></x-icon>
            <div class="font-bold text-xl leading-normal capitalize" style="font-family: 'Inkwell Sans',Verdana,sans-serif;">{{$title}}</div>
        </div>
        <div>{{$header ?? ''}}</div>
    </div>
    <div class="bg-gray-50  overflow-hidden rounded-b h-full">
        {{$slot}}
    </div>
</div>