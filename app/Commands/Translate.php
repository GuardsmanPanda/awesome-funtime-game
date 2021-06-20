<?php

namespace App\Commands;

use FilesystemIterator;
use App\Models\Country;
use App\Models\Language;
use RecursiveIteratorIterator;
use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;
use Integrations\Translate\GoogleCloudTranslation;

class Translate extends Command {
    protected $signature = 'zz:translate';
    protected $description = 'Translate text';

    private string $view_dir;
    private string $output_dir;

    public function __construct() {
        parent::__construct();
        $this->output_dir =base_path('config/language/');
        $this->view_dir =base_path('views/');
    }

    public function handle(): void {
        $this->createMissingConfig();

        $need_translate_extra = $this->getDBTranslations();
        $need_translate = $this->getExtraWordsToTranslate();

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->view_dir, FilesystemIterator::SKIP_DOTS | FilesystemIterator::CURRENT_AS_PATHNAME)) as $x) {
            preg_match_all ("/[^a-zA-Z0-9]t\('(.+?)'\)/", file_get_contents($x), $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $need_translate[] = $match[1];
            }
        }

        $need_translate = array_unique($need_translate);

        foreach (scandir($this->output_dir) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $target = str_replace('.php', '', $file);

            $current_translations_extra = [];
            $current_translations = [];

            foreach (include($this->output_dir . $file) as $key => $value) {
                if (is_string($value)) {
                    $current_translations[$key] = $value;
                } else {
                    $current_translations_extra[$key] = $value;
                }
            }

            foreach ($need_translate as $word) {
                if (array_key_exists($word, $current_translations)) {
                    continue;
                }
                $translated = GoogleCloudTranslation::translate($word, $target);
                $current_translations[$word] = $translated;
                $this->info("Translated [$word] to $target => $translated");
                usleep(20000);
            }

            $content = '<?php // AUTO GENERATE ONLY MODIFY EXISTING LINES ** Between " and " ON THE RIGHT **' . PHP_EOL;
            $content .= 'return [' . PHP_EOL;
            ksort($current_translations);
            foreach ($current_translations as $word => $translated) {
                if (str_contains($translated, '"')) {
                    $content .= '    "' . $word . '" => \'' . $translated . '\',' . PHP_EOL;
                } else {
                    $content .= '    "' . $word . '" => "' . $translated . '",' . PHP_EOL;
                }
            }

            ksort($need_translate_extra);
            foreach ($need_translate_extra as $group_name => $values) {
                $content .= PHP_EOL;
                $content .= PHP_EOL;
                $content .= PHP_EOL;
                $content .= "    \"$group_name\" => [" . PHP_EOL;
                $current_translations = $current_translations_extra[$group_name] ?? [];
                foreach ($values as $word) {
                    if (array_key_exists($word, $current_translations)) {
                        continue;
                    }
                    $translated = GoogleCloudTranslation::translate($word, $target);
                    $current_translations[$word] = $translated;
                    $this->info("Translated [$word] to $target => $translated");
                    usleep(20000);
                }
                ksort($current_translations);
                foreach ($current_translations as $word => $translated) {
                    if (str_contains($translated, '"')) {
                        $content .= '    "' . $word . '" => \'' . $translated . '\',' . PHP_EOL;
                    } else {
                        $content .= '    "' . $word . '" => "' . $translated . '",' . PHP_EOL;
                    }
                }
                $content .= "    ]," . PHP_EOL;
            }

            $content .= '];' . PHP_EOL;

            file_put_contents($this->output_dir . $file, $content);
        }
    }

    private function createMissingConfig(): void {
        $files = DB::select("SELECT translation_code FROM language WHERE translation_code IS NOT NULL");
        foreach ($files as $file) {
            if ($file->translation_code === 'en') {
                continue;
            }
            if (!file_exists($this->output_dir . $file->translation_code . '.php')) {
                $this->info($file->translation_code);
                file_put_contents($this->output_dir . $file->translation_code . '.php', '<?php' . PHP_EOL . 'return [];' . PHP_EOL);
            }
        }
    }

    #[ArrayShape(['currency' => "array", 'language' => "array", 'country' => "array"])]
    private function getDBTranslations(): array {
        $res = [
            'currency' => [],
            'language' => [],
            'country' => [],
        ];
        foreach (Language::all('language_name') as $lang) {
            $res['language'][] = $lang->language_name;
        }
        foreach (Country::all(['country_name', 'currency_name']) as $country) {
            $res['currency'][] = $country->currency_name;
            $res['country'][] = $country->country_name;
        }
        return $res;
    }

    private function getExtraWordsToTranslate(): array {
        $res = [];
        foreach (DB::select("SELECT map_style_name FROM map_style") as $map) {
            $res[] = $map->map_style_name;
        }
        return $res;
    }
}