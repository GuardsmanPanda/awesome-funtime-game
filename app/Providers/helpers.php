<?php
// Create translation helper
use App\Tools\Translator;
use Illuminate\Support\Str;

function t(string $key): string {
    return Translator::translate($key);
}

function idempotency(string $value = null): string {
    return '<input hidden name="_idempotency" value="'. ($value ?? Str::random()) .'">';
}

