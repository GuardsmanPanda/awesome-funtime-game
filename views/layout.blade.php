<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Awesome Funtime Game</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Super Awesome Funtime Game">
    <meta name="author" content="Guardsman Bob">

    <link href="/static/fonts.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="/static/leaflet/leaflet.css" />
    <link rel="stylesheet" href="/static/pannellum.css">
    <link rel="stylesheet" href="{{mix('/static/dist/app.css')}}">

    <script src="/static/hyperscript.min.js"></script>
    <script src="/static/leaflet/leaflet.js"></script>
    <script src="/static/pannellum.js"></script>
    <script src="{{mix('/static/dist/app.js')}}"></script>

    <script>
        let targetTime = null;
        let countdownInterval;

        function countdownStart(seconds) {
            clearInterval(countdownInterval);
            targetTime = new Date(new Date().getTime() + seconds*1000);
            let ele = document.getElementById("countdown");
            ele.style.opacity = "100";
            writeCounter();
            countdownInterval = setInterval(countdownUpdate, 1000);
        }

        function countdownUpdate() {
            let value = Math.round((targetTime - new Date())/1000);
            writeCounter(value);
            if (value <= 0) {
                let ele = document.getElementById("countdown");
                ele.style.opacity = "0";
            }
            if (value < -5) {
                location.reload();
                clearInterval(countdownInterval);
            }
        }

        function writeCounter(value) {
            let countText = '';
            const s = Math.max(value, 0);
            const hours = Math.floor(s / 3600);
            const minutes = Math.floor((s % 3600)/60);
            const seconds = s % 60;
            if (hours > 0) countText += hours + ':';
            if (hours > 0 && minutes < 10) countText += '0';
            countText += minutes + ':';
            if (seconds < 10) countText += '0';
            countText += seconds;
            document.getElementById("countdown").innerText = countText;
        }
    </script>
</head>
<body hx-indicator="#loading-status" hx-target="#primary" class="subpixel-antialiased">
<nav class="absolute bg-gray-800 flex  h-10 items-center justify-between shadow-md w-full top-0"
     style="font-family: 'Inkwell Sans',Verdana,sans-serif; line-height: 1.3; font-size: 1.3rem;">
    <div class="flex items-center gap-2">
        <a href="/" class="flex items-center ">
            <img src="/static/img/icons/top.png" class="ml-4" alt="Logo">
        </a>
        @if(\App\Tools\Auth::is_admin())
            <a href="/admin/country" class="rounded text-orange-500 font-bold border-2 border-orange-500 px-2 text-2xl leading-6 hover:bg-orange-500 hover:text-gray-50">Admin</a>
        @endif
        <a href="/stat" class="rounded text-cyan-500 font-bold border-2 border-cyan-500 px-2 text-2xl leading-6 hover:bg-cyan-500 hover:text-gray-50">Stats</a>
        @if(\App\Tools\Auth::$user_id !== -1)
            <a href="/achievement" class="rounded text-yellow-500 font-bold border-2 border-yellow-500 px-2 text-2xl leading-6 hover:bg-yellow-500 hover:text-gray-50">Achievements</a>
        @endif
        @if(\App\Tools\Auth::has_permission('contribute'))
            <a href="/contribute" class="rounded text-sky-500 font-bold border-2 border-sky-500 px-2 text-2xl leading-6 hover:bg-sky-500 hover:text-gray-50">Contribute</a>
        @endif
        @if(\App\Tools\Auth::has_permission('dev'))
            <a href="/dev" class="rounded text-red-500 font-bold border-2 border-red-500 px-2 text-2xl leading-6 hover:bg-red-500 hover:text-gray-50">Dev</a>
        @endif
    </div>
    <div class="flex gap-2">
        <div id="game-status" class="font-bold text-3xl text-gray-500"></div>
        <div id="countdown" class="font-bold text-3xl text-emerald-400 tabular-nums" style="opacity: 0; transition: all ease-in-out 2s"></div>
    </div>

    <div class="flex items-center gap-2">
        @if (\App\Tools\Auth::$user_id === -1)
            <a href="https://id.twitch.tv/oauth2/authorize?client_id=q8q6jjiuc7f2ef04wmb7m653jd5ra8&redirect_uri={{urlencode(config('app.url') . '/auth/twitch-login')}}&response_type=code&scope=user:read:email" class="mr-1 px-2">
                <div class="hover:bg-emerald-600 bg-emerald-400 rounded text-black px-2">Login With Twitch</div>
            </a>
        @else
            <div class="font-bold mr-2 text-gray-400 relative group">{{\App\Tools\Auth::user()->display_name}}
                <div class="absolute bg-gray-800 group-hover:block hidden px-4 py-2 rounded-b-md z-40">
                    <a href="/auth/logout" class="hover:text-gray-200">
                        <div class="flex gap-2"><x-icon name="logout" class="text-gray-600"></x-icon>
                            <div >{{t('Logout')}}</div></div>
                    </a>
                </div>
            </div>
            <img src="/static/img/flags/iso-small/{{\App\Tools\Auth::user()->country_code}}.png" alt="country flag">
        @endif
    </div>
</nav>
<dialog id="general-dialog" class="p-0">
    <div class="flex bg-blueGray-600 text-gray-100 justify-between h-8 items-center font-medium pl-4 gap-4">
        <div id="pop-title" class="capitalize">{{t('Dialog')}}</div>
        <form method="dialog">
            <button class="w-8 h-8 hover:text-red-600 align-middle">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </form>
    </div>
    <div class="px-4 pb-4 pt-2 grid gap-2" id="pop">Please report system error</div>
</dialog>
@if($area === 'admin')
    <div class="w-full min-w-0  min-h-screen text-gray-800 bg-gray-100 ">
        <div class="pt-10 bg-gray-100 flex justify-center pb-4">
            <div class="bg-gray-800 flex gap-4 px-4 py-2 rounded-b-md shadow-lg" hx-boost="true" hx-target="#primary">
                <a href="/admin/country" class="small-button-blue">Countries</a>
                <a href="/admin/language" class="small-button-blue">Languages</a>
                <a href="/admin/streetview" class="small-button-blue">Streetview</a>
            </div>
        </div>
        <div id="primary" {!!$primary_hx!!} hx-trigger="load" class="px-4"></div>
    </div>
@elseif($area === 'stat')
    <div class="w-full min-w-0  min-h-screen text-gray-800 bg-gray-100 ">
        <div class="pt-10 bg-gray-100 flex justify-center pb-4">
            <div class="bg-gray-800 flex gap-4 px-4 py-2 rounded-b-md shadow-lg" hx-boost="true" hx-target="#primary">
                <a href="/stat/country" class="small-button-blue">Countries</a>
            </div>
        </div>
        <div id="primary" {!!$primary_hx!!} hx-trigger="load" class="px-4"></div>
    </div>
@elseif($area === 'achievement')
    <div class="w-full min-w-0  min-h-screen text-gray-800 bg-gray-100">
        <div class="pt-10 bg-gray-100 flex justify-center">
            <div class="bg-gray-800 flex gap-4 px-4 py-2 rounded-b-md shadow-lg" hx-boost="true" hx-target="#primary">
                <a href="/achievement/accuracy" class="small-button-blue">Accuracy</a>
                <a href="/achievement/ladder" class="small-button-blue">Ladder</a>
            </div>
        </div>
        <div id="primary" {!!$primary_hx!!} hx-trigger="load" class="px-4"></div>
    </div>
@elseif($area === 'contribute')
    <div class="w-full min-w-0  min-h-screen text-gray-800 bg-gray-100 ">
        <div class="pt-10 bg-gray-100 flex justify-center pb-4">
            <div class="bg-gray-800 flex gap-4 px-4 py-2 rounded-b-md shadow-lg" hx-boost="true" hx-target="#primary">
                <a href="/contribute/panorama" class="small-button-blue">Panoramas</a>
            </div>
        </div>
        <div id="primary" {!!$primary_hx!!} hx-trigger="load" class="px-4"></div>
    </div>
@elseif($area === 'dev')
    <div class="w-full min-w-0  min-h-screen text-gray-800 bg-gray-100 ">
        <div class="pt-10 bg-gray-100 flex justify-center pb-4">
            <div class="bg-gray-800 flex gap-4 px-4 py-2 rounded-b-md shadow-lg" hx-boost="true" hx-target="#primary">
                <a href="/dev/download" class="small-button-blue">Download</a>
                <a href="/dev/finder" class="small-button-blue">Finder</a>
            </div>
        </div>
        <div id="primary" {!!$primary_hx!!} hx-trigger="load" class="px-4"></div>
    </div>
@else
    <div id="primary" {!!$primary_hx!!} hx-trigger="load" class="w-full min-w-0 min-h-screen text-gray-800 bg-gray-100"></div>
@endif

<script>
    Dialog.registerDialog(document.getElementById('general-dialog'));
    tippy('[data-tippy-content]');
</script>
</body>
</html>
