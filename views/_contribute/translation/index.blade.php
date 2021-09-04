<div class="flex flex-row gap-4">
    <div class="w-32 flex flex-col gap-4" hx-target="#translation-editor">
        <div class="text-lg font-bold">Languages</div>
        @foreach($languages as $lang)
            <button hx-get="/contribute/translation/language/{{$lang->id}}" class="hover:scale-105 transform duration-50">{{$lang->language_name}}</button>
        @endforeach
    </div>
    <div id="translation-editor" class="flex-grow">Select a language</div>
</div>