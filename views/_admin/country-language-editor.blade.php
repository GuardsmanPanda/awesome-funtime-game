<div class="grid gap-4" hx-target="this">
    <form class="flex gap-4" hx-post="/admin/country/{{$country->country_code}}/language">
        <label>{{t('Language')}}
            <select name="language_id">
                @foreach(\App\Models\Language::orderBy('language_name')->get() as $lang)
                    <option value="{{$lang->id}}" @if($lang->language_name === 'Spanish') selected @endif>{{$lang->language_name}} [{{$lang->two_letter_code}}]</option>
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
                        <th>{{t('Name')}}</th>
                        <th>{{t('Percentage')}}</th>
                        <th>Code-2</th>
                        <th>Code-3</th>
                        <th>{{t('Delete')}}</th>
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
                            <td><button class="small-scale-button-red" hx-delete="/admin/country/{{$country->country_code}}/language/{{$lang->id}}">{{t('Delete')}}</button></td>
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