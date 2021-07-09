<div hx-target="this">
    <div class="prose mb-4">
        <p>This feature allows you to select a number of countries in secret, the panoramas in the game will then be chosen from the pool of secret country selections.</p>
        <p>For each player who have chosen countries there is an 5% chance a player country will be chosen in a given round (up to 50%).</p>
    </div>
    <div class="grid grid-cols-4 gap-4 p-1">
        <div>
            <div class="text-xl font-bold">{{$user_data->country_name_1 ?? t('Unused')}}</div>
            @include('game.lobby.country-selector-list', ['country_code' => $user_data->country_code_1, 'input_name' => 'country_1'])
        </div>
        <div>
            <div class="text-xl font-bold">{{$user_data->country_name_2 ?? t('Unused')}}</div>
            @include('game.lobby.country-selector-list', ['country_code' => $user_data->country_code_2, 'input_name' => 'country_2'])
        </div>
        <div>
            <div class="text-xl font-bold">{{$user_data->country_name_3 ?? t('Unused')}}</div>
            @include('game.lobby.country-selector-list', ['country_code' => $user_data->country_code_3, 'input_name' => 'country_3'])
        </div>
        <div>
            <div class="text-xl font-bold">{{$user_data->country_name_4 ?? t('Unused')}}</div>
            @include('game.lobby.country-selector-list', ['country_code' => $user_data->country_code_4, 'input_name' => 'country_4'])
        </div>
    </div>
    <div hx-swap-oob="true" id="pop-title" class="capitalize">{{t('Choose my secret countries')}}</div>
    <datalist id="countries" >
        @foreach($countries as $country)
            <option value="{{$country->country_code}}" class="leading-4">
                {{\Illuminate\Support\Str::limit(t($country->country_name), 20)}}
            </option>
        @endforeach
    </datalist>
</div>
