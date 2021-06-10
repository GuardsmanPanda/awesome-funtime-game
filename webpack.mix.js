const mix = require('laravel-mix');

mix.postCss('resources/app.css', 'public/static/dist/app.css', [require('tailwindcss')]).version()
mix.js('resources/app.js', 'public/static/dist/app.js').version()
