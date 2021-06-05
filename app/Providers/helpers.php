<?php
// Create translation helper
use App\Tools\Translator;

if (!function_exists('t')) {
    function t(string $key): string
    {
        return Translator::translate($key);
    }
}
