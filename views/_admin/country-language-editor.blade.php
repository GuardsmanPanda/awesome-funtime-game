<div class="grid gap-4">
    <form class="flex gap-4" hx-post="/admin/country/{{$country->country_code}}/language" hx-target="closest div">
        <label>{{t('Language')}}
            <select name="language_id">
                @foreach(\App\Models\Language::orderBy('language_name')->get() as $lang)
                    <option value="{{$lang->id}}">{{$lang->language_name}} [{{$lang->two_letter_code}}]</option>
                @endforeach
            </select>
        </label>
        <label>
            {{t('Speaking')}} %
            <input type="number" min="0" max="100" autofocus name="percentage">
        </label>
        <button class="button-blue">Add</button>
    </form>
    <div>
        <fieldset>
            <legend>{{t('Languages')}}</legend>
            <table class="table-auto">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Percentage</th>
                        <th>Code-2</th>
                        <th>Code-3</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($country_lang as $lang)
                        <tr>
                            <td>{{$lang->id}}</td>
                            <td>{{$lang->language_name}}</td>
                            <td>{{$lang->percentage}}%</td>
                            <td>{{$lang->two_letter_code}}</td>
                            <td>{{$lang->three_letter_code}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </fieldset>
    </div>
    <div class="flex flex-row-reverse gap-2">
        <form method="dialog">
            <button class="button-gray">{{t('Done')}}</button>
        </form>
    </div>
</div>
<div hx-swap-oob="true" id="pop-title">{{$country->country_name}} {{t('Languages')}}</div>