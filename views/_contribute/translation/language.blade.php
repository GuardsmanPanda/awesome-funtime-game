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
            {title: "Translation Phrase", field: "translation_phrase", width: 400},
            {title: "Hint", field: "translation_hint"},
            {title: "Status", field: "translation_status"},
            {title: "Verify", field: "translation_status", headerSort:false, formatter: c => c.getValue() === 'VERIFIED' ?   '' : updateButton(c, '/contribute/translation/language/{{$lang->id}}/', 'translation_status', 'VERIFIED', 'bg-green-700') },
            {title: "Unverify", field: "translation_status", headerSort:false, formatter: c => c.getValue() === 'UNVERIFIED' ?   '' : updateButton(c, '/contribute/translation/language/{{$lang->id}}/', 'translation_status', 'UNVERIFIED',  'bg-gray-500') },
            {title: "Ambiguous", field: "translation_status", headerSort:false, formatter: c => c.getValue() === 'AMBIGUOUS' ?   '' : updateButton(c, '/contribute/translation/language/{{$lang->id}}/', 'translation_status', 'AMBIGUOUS',  'bg-blue-700') },
            {title: "Translated Phrase", field: "translated_phrase", editor:"input"},
        ]
    })
</script>