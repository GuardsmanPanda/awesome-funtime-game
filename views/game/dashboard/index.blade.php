<div class="flex">
    <div class="flex-grow">
        <div class="border-b flex justify-between">
            <div class="font-bold">{{t('Active games')}}</div>
            <div>
                @if(\App\Tools\Auth::user()?->can_create_games)
                    <x-dialog id="create-game" button-text="{{t('Create')}}" title="{{t('Create a new game')}}"
                              class="small-button-blue">
                        @include('game.dashboard.create-game-form')
                    </x-dialog>
                @endif
            </div>
        </div>
        <div id="active-games"></div>

        <div class="border-b mt-6">
            <div class="font-bold">{{t('Recent games')}}</div>
            <div id="recent-games"></div>
        </div>
    </div>

    <div class="h-80 overflow-hidden w-80">
        <div class="relative">
            <img class="absolute" style="animation: spin 160s linear infinite; animation-direction: reverse"
                 src="/static/img/logo_part2.png" alt="Text Logo"/>
            <div id="tilt-logo" class="absolute" data-tilt data-tilt-reverse="true" data-tilt-max="8"
                 data-tilt-glare="true" data-tilt-max-glare="0.2">
                <img src="/static/img/logo_part1.png" alt="Globe Logo"/>
            </div>
        </div>
    </div>
</div>

<script>
    const active = new Tabulator('#active-games', {
        ajaxURL: '/game/active',
        columns: [
            {title:"ID", field: "id", headerSort:false},
            {title:"{{t('Created by')}}", field: "display_name", headerSort:false},
            {title:"{{t('Rounds')}}", field: "round_count", headerSort:false},
            {title:"{{t('Round Time')}}", field: "round_time", headerSort:false},
            {title:"{{t('Started')}}", field: "current_round", headerSort:false, formatter: function (cell, formatterParams, onRendered ) {
                    return cell.getValue() === 0 ? '-' : 'Yes';
                }
            },
            {title:"{{t('Join')}}", field: "id", headerSort:false, formatter: function (cell, formatterParams, onRendered ) {
                    const elem = document.createElement('a');
                    elem.setAttribute('href', '/game/' + cell.getValue() + '/lobby');
                    elem.setAttribute('class', 'small-button-blue');
                    elem.text = "{{t('Join')}}";
                    return elem;
                }
            },
        ]
    });

    const recent = new Tabulator('#recent-games', {
        ajaxURL: '/game/recent',
        columns: [
            {title:"ID", field: "id", headerSort:false},
            {title:"{{t('Created by')}}", field: "display_name", headerSort:false},
            {title:"{{t('Rounds')}}", field: "round_count", headerSort:false},
            {title:"{{t('Round Time')}}", field: "round_time", headerSort:false},
            {title:"{{t('Ending time')}}", field: "ended_at", headerSort:false},
            {title:"{{t('Result')}}", field: "id", headerSort:false, formatter: function (cell, formatterParams, onRendered ) {
                    const elem = document.createElement('a');
                    elem.setAttribute('href', '/game/' + cell.getValue() + '/result');
                    elem.setAttribute('class', 'small-button-blue');
                    elem.text = "{{t('Result')}}";
                    return elem;
                }
            },
        ]
    });
</script>
