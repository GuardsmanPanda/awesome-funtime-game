<x-content-raw title="{{t('Countries')}}" icon="globe">
    <div id="country-table"></div>
</x-content-raw>

<script>
    const table = new Tabulator('#country-table', {
        ajaxURL: "/admin/country",
        height: "600px",
        index:"country_code",
        layout:"fitDataStretch",
        columns: [
            {title: "Code", field: "country_code"},
            {title: "Name", field: "country_name"},
        ]
    })
</script>