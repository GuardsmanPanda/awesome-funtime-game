<?php

namespace Integrations\Translate;

use RuntimeException;
use Illuminate\Support\Facades\Http;

class GoogleCloudTranslation {
    private static string $base_url = 'https://translation.googleapis.com/language/translate/v2';

    public static function getLanguages(): array {
        return self::query('/languages');
    }

    public static function translate(string $text, string $target, string $source = 'en'): string {
        if ($target === $source) {
            return $text;
        }
        $resp = self::post('',  [
            'format' => 'text',
            'source' => $source,
            'target' =>$target,
            'q' => $text,
        ]);
        return $resp['data']['translations'][0]['translatedText'];
    }

    private static function post(string $path, array $query = []): array {
        $resp = Http::asJson()->post(self::$base_url .$path . '?key=' . config('settings.cloud_translate_api'), $query);
        if ($resp->failed()) {
            throw new RuntimeException("Failed translate request: $path ". $resp->body());
        }
        return $resp->json();
    }

    private static function query(string $path, array $query = []): array {
        $resp = Http::get(self::$base_url .$path, ['key' => config('settings.cloud_translate_api')] + $query);
        if ($resp->failed()) {
            throw new RuntimeException("Failed translate request: $path ". $resp->body());
        }
        return $resp->json();
    }
}