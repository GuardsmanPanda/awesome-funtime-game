<div class="grid gap-4">
    <form class="flex gap-4" hx-post="/admin/country/{{$country->country_code}}/fact" hx-target="closest div">
        <label>
            {{t('Fact')}}
            <input type="text" autofocus name="fact_text">
        </label>
        <button class="button-blue">Add</button>
    </form>
    <div>
        <fieldset>
            <legend>{{t('Facts')}}</legend>
            <table class="table-auto">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>{{t('Fact')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($country_fact as $fact)
                    <tr>
                        <td>{{$fact->id}}</td>
                        <td>{{$fact->fact_text}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </fieldset>
    </div>
    <div class="flex flex-row-reverse gap-2">
        <form method="dialog">
            <button class="button-gray">{{t('Close')}}</button>
        </form>
    </div>
</div>
<div hx-swap-oob="true" id="pop-title">{{$country->country_name}} {{t('Facts')}}</div>