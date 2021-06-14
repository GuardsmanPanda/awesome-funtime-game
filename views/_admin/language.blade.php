<x-content-raw title="{{t('Languages')}}" icon="translate">
    <x-slot name="header">
        <x-dialog class="outline-button-lightTeal" id="language-create" title="{{t('Add a new language')}}" button-text="{{t('Add language')}}">
           <form method="dialog" id="language-form" class="grid gap-2" autocomplete="off" hx-post="/admin/language">
               <label>
                   Language Name
                   <input name="language_name" required type="text" autofocus class="w-full">
               </label>
               <fieldset class="flex gap-2">
                   <legend>Letter code (lowercase)</legend>
                   <label>
                       Two
                       <input name="two_letter_code" type="text" pattern="[a-z]{2}" class="w-32">
                   </label>
                   <label>
                       Three
                       <input name="three_letter_code" type="text" pattern="[a-z]{3}" class="w-32">
                   </label>
               </fieldset>

               <fieldset class="flex gap-2">
                   <legend>Speakers</legend>
                   <label>
                       Native
                       <input name="native_speakers" type="number" class="w-32" value="-1">
                   </label>
                   <label>
                       Total
                       <input name="total_speakers" type="number" class="w-32" value="-1">
                   </label>
               </fieldset>
           </form>
            <div class="flex flex-row-reverse gap-2 pt-2">
                <form method="dialog">
                    <button class="button-gray">{{t('Cancel')}}</button>
                </form>
                <button class="button-blue" form="language-form">{{t('Add')}}</button>
            </div>
        </x-dialog>
    </x-slot>
    <div id="language-table"></div>
</x-content-raw>

<script>
    const table = new Tabulator('#language-table', {
        ajaxURL: "/admin/language/list",
        height: "600px",
        layout:"fitDataStretch",
        cellVertAlign: "middle",
        cellEdited:function(cell){
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