<x-content-raw title="{{t('Languages')}}" icon="translate">
    <div id="language-table"></div>
</x-content-raw>

<script>
    const table = new Tabulator('#language-table', {
        ajaxURL: "/admin/language/list",
        height: "600px",
        layout:"fitDataStretch",
        cellVertAlign: "middle",
        cellEdited:function(cell) {
            fetch('/admin/language', {
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
            {title: "Name", field: "language_name"},
            {title: "Translation", field: "translation_code", formatter:"tickCross", hozAlign:"center", formatterParams:{allowTruthy:true}},
            {title: "Iso-639-1", field: "two_letter_code"},
            {title: "Iso-639-2", field: "three_letter_code"},
            {title: "Native Speakers", field: "native_speakers", formatter: "number", sorter:"number", editor:"number"},
            {title: "Total Speakers", field: "total_speakers", formatter: "number", sorter:"number", editor:"number"},
            {title: "Added by", field: "display_name"},
        ]
    })
</script>