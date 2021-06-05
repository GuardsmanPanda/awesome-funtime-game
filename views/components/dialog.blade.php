@props(['id', 'title', 'buttonText'])
<button id="{{$id}}-b" {!! $attributes !!}>{!! $buttonText !!}</button>
<dialog class="dialog" id="{{$id}}">
    <div class="flex bg-blueGray-600 text-gray-100 justify-between h-8 items-center font-medium">
        <div class="px-4">{{$title}}</div>
        <form method="dialog">
            <button class="w-8 h-8 hover:text-red-600 align-middle">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </form>
    </div>
    <div class="px-4 pb-4 pt-2 grid gap-2">
        {{$slot}}
    </div>
</dialog>

<script>
    const dia = document.getElementById('{{$id}}');
    Dialog.registerDialog(dia);
    document.getElementById('{{$id}}-b').onclick = function () {
        dia.showModal();
    };
</script>
