<?php

namespace App\Commands;

use FilesystemIterator;
use RecursiveIteratorIterator;
use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use Illuminate\Support\Facades\DB;

class GenerateTranslations extends Command {
    protected $signature = 'zz:translations';
    protected $description = 'Generate Database Translations';

    private string $view_dir;

    public function __construct() {
        parent::__construct();
        $this->view_dir =base_path('views/');
    }

    public function handle(): void {
        $need_translate = $this->getExtraWordsToTranslate();
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->view_dir, FilesystemIterator::SKIP_DOTS | FilesystemIterator::CURRENT_AS_PATHNAME)) as $x) {
            preg_match_all ("/[^a-zA-Z0-9]t\('(.+?)'\)/", file_get_contents($x), $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $need_translate[] = $match[1];
            }
        }
        $need_translate = array_unique($need_translate);
        foreach ($need_translate as $word) {
        }
    }

    private function getExtraWordsToTranslate(): array {
        $res = [];
        foreach (DB::select("SELECT map_style_name FROM map_style") as $map) {
            $res[] = $map->map_style_name;
        }
        return $res;
    }
}