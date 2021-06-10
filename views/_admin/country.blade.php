<x-content-raw title="{{t('Countries')}}" icon="globe">
    <div id="country-table"></div>
</x-content-raw>

<dialog class="dialog" id="country-language">
    <div class="flex bg-blueGray-600 text-gray-100 justify-between h-8 items-center font-medium">
        <div class="px-4">{{t('Edit the languages')}}</div>
        <form method="dialog">
            <button class="w-8 h-8 hover:text-red-600 align-middle">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </form>
    </div>
    <div class="px-4 pb-4 pt-2 grid gap-2" id="language-editor">
        test
    </div>
</dialog>


<script>
    const dia = document.getElementById('country-language');
    Dialog.registerDialog(dia);

     const table = new Tabulator('#country-table', {
        ajaxURL: "/admin/country/list",
        height: "600px",
        layout:"fitDataStretch",
        cellEdited:function(cell){
            fetch('/admin/country', {
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
            {title: "Code", field: "country_code", frozen:true},
            {title: "Name", field: "country_name", frozen:true},
            {title: "Flag", field: "country_code", headerSort: false, formatter: "image", formatterParams: {
                    height: "26px",
                    width: "39px",
                    urlPrefix: "/static/img/flags/iso-small/",
                    urlSuffix: ".png",
                }
            },
            {title: "Capital", field: "capital"},
            {title: "Population", field: "population", sorter:"number", editor:"number"},
            {title: "Area", field: "area", sorter:"number", editor:"number"},
            {title: "Languages", field: "language_count", sorter:"number"},
            {title: "Languages", field: "country_code", formatter: function (cell, formatterParams, onRendered ) {
                    const elem = document.createElement('button');
                    elem.setAttribute('class', 'small-button-blue');
                    elem.innerText = "{{t('Languages')}}";
                    elem.onclick = function () {
                        htmx.ajax('GET','/admin/country/'+cell.getValue()+'/language-editor', htmx.find('#language-editor'))
                            .then(res => dia.showModal());
                    };
                    return elem;
                }},
            {title: "Facts", field: "fact_count", sorter:"number"},
            {title: "Independence Date (ISO)", field: "independence_date", sorter:"string", editor:"input", editorParams:{mask:"9999-99-99"}},
        ]
    })
</script>