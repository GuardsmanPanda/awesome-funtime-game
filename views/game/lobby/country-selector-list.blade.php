<div class="">
    <img src="/static/img/flags/iso-small/{{$country_code ?? 'XX'}}.png" alt="Country flag" class="mt-1 ml-1 mb-4 shadow-md">
    <form autocomplete="off">
        <label>
            Select Country
            <input list="countries" hx-patch="/game/lobby/country-selector/{{$game_id}}" name="{{$input_name}}" class="border-2 border-gray-500 p-1">
        </label>
    </form>
</div>
