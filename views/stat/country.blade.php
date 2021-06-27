<x-content-raw title="{{t('Countries')}}" icon="globe">
    <div id="country-table"></div>
</x-content-raw>

<script>
    const table = new Tabulator('#country-table', {
        ajaxURL: "/stat/country/list",
        height: "600px",
        layout:"fitDataStretch",
        initialSort:[{column:"country_name", dir:"asc"},],
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
            {title: "{{t('Panoramas')}}", field: "panorama_count", sorter:"number"},
        ]
    })
</script>