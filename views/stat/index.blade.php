<div class="grid gap-4">
    <div class="flex gap-4 justify-center flex-row flex-wrap">
        <x-text-card top-text="{{$info->user_count}}" bot-text="{{t('Players')}}" class="w-40 bg-pink-700" bot-bg="bg-pink-800"></x-text-card>
        <x-text-card top-text="{{$info->game_count}}" bot-text="{{t('Games')}}" class="w-40 bg-blue-700" bot-bg="bg-blue-800"></x-text-card>
        <x-text-card top-text="{{$info->round_count}}" bot-text="{{t('Rounds')}}" class="w-40 bg-sky-700" bot-bg="bg-sky-800"></x-text-card>
        <x-text-card top-text="{{$info->guess_count}}" bot-text="{{t('Guesses')}}" class="w-40 bg-cyan-700" bot-bg="bg-cyan-800"></x-text-card>
        <x-text-card top-text="{{$info->panorama_count}}" bot-text="{{t('Panoramas')}}" class="w-40 bg-orange-700" bot-bg="bg-orange-800"></x-text-card>
    </div>
    <x-content-raw title="{{t('User panoramas')}}" icon="users">
        <div id="user-panorama-table"></div>
    </x-content-raw>
</div>

<script>
    const table = new Tabulator('#user-panorama-table', {
        ajaxURL: "/stat/user-panorama",
        minHeight: "100%",
        layout:"fitDataStretch",
        dataLoaded: function(data) {
            tippy('[data-tippy-content]');
        },
        columns: [
            {
                title:"{{t('Panoramas')}}", columns: [
                    {
                        title: "{{t('Flag')}}", field: "country_code", headerSort: false, formatter:function(cell, formatterParams, onRendered){
                            return `<img src="/static/img/flags/iso-small/${cell.getValue()}.png" data-tippy-content="${cell.getData().country_name}" alt="Country flag" width="39">`;
                        }
                    },
                    {title: "{{t('Name')}}", field: "display_name"},
                    {title: "{{t('Panoramas')}}", field: "count", sorter:"number", hozAlign:"right"},
                ]
            },
            {
                title:"{{t('Votes')}}", columns: [
                    {title: "{{t('Wow')}}", field: "wow", sorter:"number", hozAlign:"right"},
                    {title: "%", field: "wow_percent", sorter:"number", hozAlign:"right"},
                    {title: "{{t('Great')}}", field: "great", sorter:"number", hozAlign:"right"},
                    {title: "%", field: "great_percent", sorter:"number", hozAlign:"right"},
                    {title: "{{t('Good')}}", field: "good", sorter:"number", hozAlign:"right"},
                    {title: "%", field: "good_percent", sorter:"number", hozAlign:"right"},
                    {title: "{{t('Total')}}", field: "total", sorter:"number", hozAlign:"right"},
                    {title: "{{t('Good or better')}}", field: "good_or_better_percent", sorter:"number", hozAlign:"right", formatter:"progress"},
                ]
            }
        ]
    })
</script>