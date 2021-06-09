<x-content-raw title="{{t('Languages')}}" icon="translate">
    <div id="language-table"></div>
</x-content-raw>

<script>
    const table = new Tabulator('#language-table', {
        ajaxURL: "/admin/language/list",
        height: "600px",
        layout:"fitDataStretch",
        columns: [
            {title: "ID", field: "id"},
            {title: "Name", field: "language_name"},
            {title: "Translation", field: "has_translation"},
            {title: "Iso-639-1", field: "two_letter_code"},
            {title: "Iso-639-2", field: "three_letter_code"},
        ]
    })
</script>