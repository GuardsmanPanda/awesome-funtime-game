<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Awesome Funtime Game</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Super Awesome Funtime Game">

    <link href="/static/fonts.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="/static/leaflet/leaflet.css" />
    <link rel="stylesheet" href="/static/pannellum.css">
    <link rel="stylesheet" href="{{mix('/static/dist/app.css')}}">

    <script src="https://unpkg.com/hyperscript.org@0.0.9"></script>
    <script src="/static/leaflet/leaflet.js"></script>
    <script src="/static/pannellum.js"></script>
    <script src="{{mix('/static/dist/app.js')}}"></script>

    <script>
        let targetTime = null;
        let countdownSeconds = 0;
        let countdownInterval;

        function countdownStart(seconds) {
            clearInterval(countdownInterval);
            targetTime = new Date(new Date().getTime() + seconds*1000);
            countdownSeconds = seconds;
            let ele = document.getElementById("countdown");
            ele.style.opacity = "100";
            writeCounter();
            countdownInterval = setInterval(countdownUpdate, 1000);
        }

        function countdownUpdate() {
            countdownSeconds--;
            writeCounter();
            if (Math.round((targetTime - new Date())/1000) <= 0) {
                let ele = document.getElementById("countdown");
                ele.style.opacity = "0";
                clearInterval(countdownInterval);
            }
        }

        function writeCounter() {
            let countText = '';
            const s = Math.max(Math.round((targetTime - new Date())/1000), 0);
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
<body hx-indicator="#loading-status" hx-target="#primary" class="">
<nav class="absolute bg-gray-800 flex  h-10 items-center justify-between shadow-md w-full top-0"
     style="font-family: 'Inkwell Sans',Verdana,sans-serif; line-height: 1.3; font-size: 1.3rem;">
    <div class="flex items-center gap-4">
        <a href="/" class="flex items-center ">
            <img src="/static/img/icons/top.png" class="ml-4" alt="Logo">
        </a>
        @if(\App\Tools\Auth::is_admin())
            <a href="/admin/country" class="rounded text-orange-600 font-bold border-2 border-orange-600 px-2 text-2xl leading-6 hover:bg-orange-600 hover:text-gray-50">Admin</a>
        @endif
        <a href="/stats" class="rounded text-cyan-600 font-bold border-2 border-cyan-600 px-2 text-2xl leading-6 hover:bg-cyan-600 hover:text-gray-50">Stats</a>
    </div>
    <div class="flex gap-2">
        <div id="game-status" class="font-bold text-3xl text-gray-500"></div>
        <div id="countdown" class="font-bold text-3xl text-emerald-400 tabular-nums" style="opacity: 0; transition: all ease-in-out 2s"></div>
    </div>

    <div class="flex items-center gap-4">
        <div class="items-center text-lightBlue-400 flex">
            <x-icon name="translate" class="text-lightBlue-600"></x-icon>
            <div class="font-bold">English</div>
        </div>
        @if (\App\Tools\Auth::$user_id === -1)
            <a href="https://id.twitch.tv/oauth2/authorize?client_id=q8q6jjiuc7f2ef04wmb7m653jd5ra8&redirect_uri={{urlencode(config('app.url') . '/auth/twitch-login')}}&response_type=code&scope=user:read:email">
                <div class="hover:bg-emerald-600 bg-emerald-400 mr-1 px-2 rounded text-black">Login With Twitch</div>
            </a>
        @else
            <div class="font-bold mr-2 text-gray-400">{{\App\Tools\Auth::user()->display_name}}</div>
            <img src="/static/img/flags/iso-small/{{\App\Tools\Auth::user()->country_code}}.png" alt="country flag">
        @endif
    </div>
</nav>
@if($area === 'admin')
    <div class="w-full min-w-0  min-h-screen text-gray-800 bg-gray-100 ">
        <div class="pt-10 bg-gray-100 flex justify-center pb-4">
            <div class="bg-orange-600 text-gray-100 flex gap-4" hx-boost="true" hx-target="#primary">
                <a href="/admin/country">Countries</a>
                <a href="/admin/language">Languages</a>
            </div>
        </div>
        <div id="primary" {!!$primary_hx!!} hx-trigger="load" class="px-4"></div>
    </div>
@else
    <div id="primary" {!!$primary_hx!!} hx-trigger="load" class="pt-12 px-4 w-full min-w-0 min-h-screen text-gray-800 bg-gray-100"></div>
@endif
</body>
</html>
