<?php

namespace App\Commands;

use FilesystemIterator;
use RecursiveIteratorIterator;
use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use Illuminate\Support\Facades\Http;
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
        $need_translate = [];

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->view_dir, FilesystemIterator::SKIP_DOTS | FilesystemIterator::CURRENT_AS_PATHNAME)) as $x) {
            preg_match_all ("/[^a-zA-Z0-9]t\('(.+?)'\)/", file_get_contents($x), $matches, PREG_SET_ORDER);
            $this->info($x);
            foreach ($matches as $match) {
                $this->info($match[1]);
                $need_translate[] = $match[1];
            }
        }

        $need_translate = array_unique($need_translate);

        foreach (scandir($this->output_dir) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $this->info($file);
        }
    }
}