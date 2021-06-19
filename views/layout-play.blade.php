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

    <script src="https://unpkg.com/hyperscript.org@0.0.9"></script>
    <script src="/static/leaflet/leaflet.js"></script>
    <script src="/static/pannellum.js"></script>

    <link rel="stylesheet" href="{{mix('/static/dist/app.css')}}">
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
            countText += s;
            document.getElementById("countdown").innerText = countText;
        }
    </script>
</head>
<body id="primary" {!!$primary_hx!!} hx-trigger="load" class="w-full flex-grow min-w-0 flex-shrink min-h-screen dark:bg-gray-800 text-gray-800 bg-gray-100 dark:text-gray-200 relative" style="min-height: 100vh;">
</body>
</html>
