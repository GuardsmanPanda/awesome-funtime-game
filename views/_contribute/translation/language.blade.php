<div class="text-center text-2xl">Translations For {{$lang->language_name}}</div>
<div id="translation-table"></div>

<script>
    const table = new Tabulator('#translation-table', {
        ajaxURL: "/contribute/translation/language/{{$lang->id}}/list",
        height: "800px",
        layout:"fitDataStretch",
        responsiveLayout:true,
        cellVertAlign: "middle",
        cellEdited: (cell) => editFn(cell, '/contribute/translation/language/{{$lang->id}}/'),
        initialSort:[
            {column:"translation_phrase", dir:"asc"},
        ],
        groupBy:"translation_group",
        columns: [
            {title: "ID", field: "id", width: 30},
            {title: "Translation Phrase", field: "translation_phrase"},
            {title: "Hint", field: "translation_hint"},
            {title: "Translated Phrase", field: "translated_phrase", editor:"input"},
        ]
    })
</script>