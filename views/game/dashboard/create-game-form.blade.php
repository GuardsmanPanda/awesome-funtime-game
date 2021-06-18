<form method="dialog" id="round-create-form" class="grid gap-2" autocomplete="off" hx-post="/game/create">
    <select name="realm_id">
        Realm
        @foreach(\Illuminate\Support\Facades\DB::select("
            SELECT r.id, r.realm_name
            FROM realm_user ru
            LEFT JOIN realm r ON r.id = ru.realm_id
            WHERE ru.user_id = ?", [\App\Tools\Auth::$user_id]) as $realm)
            <option value="{{$realm->id}}">{{$realm->realm_name}}</option>
        @endforeach
    </select>
    <label>
        {{t('Rounds')}}
        <input name="round_count" required type="number" class="w-full" value="7" autofocus>
    </label>
    <fieldset class="flex gap-3">
        <legend> {{t('Round time')}} - {{t('Seconds')}}</legend>
        <label>30<input type="radio" name="round_time" value="30" class="ml-1"></label>
        <label>50<input type="radio" name="round_time" value="50" class="ml-1"></label>
        <label>70<input type="radio" name="round_time" value="70" class="ml-1" checked></label>
        <label>90<input type="radio" name="round_time" value="90" class="ml-1"></label>
    </fieldset>
</form>
<hr class="">
<div class="flex flex-row-reverse gap-2">
    <form method="dialog"><button class="button-gray">{{t('Cancel')}}</button></form>
    <button type="submit" class="button-blue" form="round-create-form">{{t('Create')}}</button>
</div>
