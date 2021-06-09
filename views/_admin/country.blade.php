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
                .catch((error) => {
                    console.error('Error:', error);
                });
        },
        columns: [
            {title: "Code", field: "country_code", frozen:true},
            {title: "Name", field: "country_name", frozen:true},
            {title: "Capital", field: "capital"},
            {title: "Population", field: "population", sorter:"number", editor:"number"},
            {title: "Area", field: "area", sorter:"number", editor:"number"},
            {title: "Languages", field: "language_count", sorter:"number"},
            {title: "Facts", field: "fact_count", sorter:"number"},
            {title: "Independence Date", field: "independence_date", sorter:"text"},
        ]
    })
</script>