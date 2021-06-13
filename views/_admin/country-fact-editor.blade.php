<div class="grid gap-4">
    <form class="flex gap-4" hx-post="/admin/country/{{$country->country_code}}/fact" hx-target="closest div">
        <label class="w-full">
            {{t('Fact')}}
            <input type="text" autofocus name="fact_text" class="w-full">
        </label>
        <button class="button-blue">Add</button>
    </form>
    <x-content-raw title="{{t('Facts')}}" icon="information-circle">
        <div id="fact-table"></div>
    </x-content-raw>
    <div class="flex flex-row-reverse gap-2">
        <form method="dialog">
            <button class="button-gray">{{t('Close')}}</button>
        </form>
    </div>
</div>
<div hx-swap-oob="true" id="pop-title">{{$country->country_name}} {{t('Facts')}}</div>

<script>
    const table = new Tabulator('#fact-table', {
        ajaxURL: "/admin/country/{{$country->country_code}}/fact/list",
        maxHeight: "400px",
        layout:"fitDataStretch",
        cellEdited:function(cell){
            fetch('/admin/country/{{$country->country_code}}/fact', {
                method:'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(cell.getData()),
            }).then(response => response.json())
                .then(data => {
                    table.replaceData(data);
                })
                .catch((error) => {
                    console.error('Error:', error);
                    table.replaceData(data);
                });
        },
        columns: [
            {title: "ID", field: "id"},
            {title: "Creator", field: "display_name"},
            {title: "Created", field: "created_at"},
            {title: "Text", field: "fact_text", editor:"input", width:600},
        ]
    })
</script>