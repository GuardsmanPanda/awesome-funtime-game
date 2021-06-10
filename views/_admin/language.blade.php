<x-content-raw title="{{t('Languages')}}" icon="translate">
    <x-slot name="header">
        <x-dialog class="outline-button-lightTeal" id="language-create" title="{{t('Add a new language')}}" button-text="{{t('Add language')}}">
           <form method="dialog" id="language-form" class="grid gap-2" autocomplete="off" hx-post="/admin/language">
               <label>
                   Language Name
                   <input name="language_name" required type="text" autofocus>
               </label>
               <label>
                   Two Letter Code (lowercase)
                   <input name="two_letter_code" type="text" pattern="[a-z]{2}">
               </label>
               <label>
                   Three Letter Code (lowercase)
                   <input name="three_letter_code" type="text" pattern="[a-z]{3}">
               </label>
           </form>
            <div class="flex flex-row-reverse gap-2">
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
        columns: [
            {title: "ID", field: "id"},
            {title: "Name", field: "language_name"},
            {title: "Translation", field: "has_translation", formatter:"tickCross", hozAlign:"center"},
            {title: "Iso-639-1", field: "two_letter_code"},
            {title: "Iso-639-2", field: "three_letter_code"},
            {title: "Added by", field: "display_name"},
        ]
    })
</script>