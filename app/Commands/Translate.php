<?php

namespace App\Commands;

use FilesystemIterator;
use RecursiveIteratorIterator;
use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use Illuminate\Support\Facades\DB;
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
        $need_translate = [];

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
            preg_match_all ('/"(.+)?" => "(.+?)",/', file_get_contents($this->output_dir . $file), $matches, PREG_SET_ORDER);

            $current_translations = [];
            foreach ($matches as $match) {
                $current_translations[$match[1]] = $match[2];
            }

            foreach ($need_translate as $word) {
                if (array_key_exists($word, $current_translations)) {
                    continue;
                }
                $target = str_replace('.php', '', $file);
                $translated = GoogleCloudTranslation::translate($word, $target);
                $current_translations[$word] = $translated;
                $this->info("Translated [$word] to $target => $translated");
                usleep(200000);
            }

            $content = '<?php // AUTO GENERATE ONLY MODIFY EXISTING LINES ** Between " and " ON THE RIGHT **' . PHP_EOL;
            $content .= 'return [' . PHP_EOL;
            ksort($current_translations);
            foreach ($current_translations as $word => $translated) {
                $content .= '    "' . $word . '" => "' . $translated . '",' . PHP_EOL;
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
                file_put_contents($this->output_dir . $file->translation_code . '.php', '');
            }
        }
    }
}