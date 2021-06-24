<x-content-raw title="{{t('Countries')}}" icon="globe">
    <div id="country-table"></div>
</x-content-raw>

<script>
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
                .catch(error => {
                    console.error('Error:', error);
                    table.replaceData(data);
                });
        },
        columns: [
            {title: "Code", field: "country_code", frozen:true, headerFilter:"input"},
            {title: "Name", field: "country_name", frozen:true, headerFilter:"input"},
            {title: "Flag", field: "country_code", headerSort: false, formatter: "image", formatterParams: {
                    height: "26px",
                    width: "39px",
                    urlPrefix: "/static/img/flags/iso-small/",
                    urlSuffix: ".png",
                }
            },
            {title: "Capital city", field: "capital", headerFilter:"input"},
            {title: "Population", field: "population", sorter:"number", editor:"number"},
            {title: "Area", field: "area", sorter:"number", editor:"number"},
            {title: "GPD per capita", field: "gdp_per_capita", sorter:"number", editor:"number"},
            {title: "{{t('Languages')}}", field: "language_count", sorter:"number"},
            {title: "{{t('Languages')}}", field: "country_code", formatter: function (cell, formatterParams, onRendered ) {
                    const elem = document.createElement('button');
                    elem.setAttribute('class', 'small-button-blue');
                    elem.innerText = "{{t('Languages')}}";
                    elem.onclick = function () {
                        htmx.ajax('GET','/admin/country/'+cell.getValue()+'/language-editor', htmx.find('#pop'))
                            .then(res => pop.showModal());
                    };
                    return elem;
                }},
            {title: "{{t('Facts')}}", field: "fact_count", sorter:"number"},
            {title: "{{t('Facts')}}", field: "country_code", formatter: function (cell, formatterParams, onRendered ) {
                    const elem = document.createElement('button');
                    elem.setAttribute('class', 'small-button-blue');
                    elem.innerText = "{{t('Facts')}}";
                    elem.onclick = function () {
                        htmx.ajax('GET','/admin/country/'+cell.getValue()+'/fact-editor', htmx.find('#pop'))
                            .then(res => pop.showModal());
                    };
                    return elem;
                }},
            {title: "Independence Date (ISO)", field: "independence_date", sorter:"string", editor:"input", editorParams:{mask:"9999-99-99"}},
        ]
    })
</script>