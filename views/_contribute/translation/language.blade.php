<div class="text-center text-2xl">Translations For {{$lang->language_name}}</div>
<div id="translation-table"></div>

<script>
    let editFn = function(cell, url) {
        console.dir(cell);
        //fetch(url, {
        //    method:'PATCH',
        //    headers: {
        //        'Content-Type': 'application/json',
        //    },
        //    body: JSON.stringify(cell.getData()),
        //}).then(response => response.json())
        //    .then(data => {
        //        table.replaceData(data);
        //    })
        //    .catch((error) => {
        //        console.error('Error:', error);
        //        table.replaceData(data);
        //    });
    }

    const table = new Tabulator('#translation-table', {
        ajaxURL: "/contribute/translation/language/{{$lang->id}}/list",
        height: "800px",
        layout:"fitDataStretch",
        cellVertAlign: "middle",
        cellEdited: (cell) => editFn(cell, '/test7'),
        initialSort:[
            {column:"translation_phrase", dir:"asc"},
        ],
        groupBy:"translation_group",
        columns: [
            {title: "ID", field: "id"},
            {title: "Translation Phrase", field: "translation_phrase"},
            {title: "Hint", field: "translation_hint"},
            {title: "Translated Phrase", field: "translated_phrase"},
        ]
    })
</script>