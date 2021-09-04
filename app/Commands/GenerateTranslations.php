<?php

namespace App\Commands;

use FilesystemIterator;
use App\Models\Country;
use App\Models\Language;
use App\Models\Translation;
use RecursiveIteratorIterator;
use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;
use Integrations\Translate\GoogleCloudTranslation;

class GenerateTranslations extends Command {
    protected $signature = 'zz:translations';
    protected $description = 'Generate Database Translations';

    private string $view_dir;

    public function __construct() {
        parent::__construct();
        $this->view_dir =base_path('views/');
    }

    public function handle(): void {
        DB::update("UPDATE translation SET in_use = false WHERE in_use");

        $this->info('Finding phrases');
        $need_translate = $this->getExtraWordsToTranslate();
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->view_dir, FilesystemIterator::SKIP_DOTS | FilesystemIterator::CURRENT_AS_PATHNAME)) as $x) {
            preg_match_all ("/[^a-zA-Z0-9]t\('(.+?)'\)/", file_get_contents($x), $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $need_translate[] = $match[1];
            }
        }
        $need_translate = array_unique($need_translate);
        $this->info('Adding phrases');
        foreach ($need_translate as $word) {
            $tr = Translation::firstWhere('translation_phrase', '=', $word);
            if ($tr === null) {
                $tr = new Translation();
                $tr->translation_phrase = $word;
            }
            $tr->in_use = true;
            $tr->save();
        }

        $this->info('Finding groups');
        $groups = $this->getTranslationGroups();
        $this->info('Adding groups');
        foreach ($groups as $group => $ww) {
            foreach ($ww as $w) {
                $tr = Translation::firstWhere('translation_phrase', '=', $w);
                if ($tr === null) {
                    $tr = new Translation();
                    $tr->translation_phrase = $w;
                }
                $tr->translation_group = $group;
                $tr->translation_hint = $group;
                $tr->in_use = true;
                $tr->save();
            }
        }

        $this->info('Processing languages...');
        $languages = Language::where('has_translation', '=', true)->get();
        foreach ($languages as $lang) {
            $this->info('Processing ' . $lang->language_name);
            $phrases = Translation::where('in_use', '=', true)->get();
            foreach ($phrases as $phrase) {
                $test = DB::selectOne("SELECT tl.translation_id FROM translation_language tl WHERE tl.translation_id = ? AND tl.language_id = ?", [$phrase->id, $lang->id]);
                if ($test === null) {
                    $translated = GoogleCloudTranslation::translate($phrase->translation_phrase, $lang->translation_code);
                    $group_info = $phrase->translation_group !== null ? '['. $phrase->translation_group .'] ' : '';
                    $this->info($group_info . $phrase->translation_phrase . ' -> ' . $translated);
                    DB::insert("
                        INSERT INTO translation_language (translation_id, language_id, translated_phrase )
                        VALUES (?, ?, ?)
                    ", [$phrase->id, $lang->id, $translated]);
                }
            }
        }
    }

    private function getExtraWordsToTranslate(): array {
        $res = [];
        foreach (DB::select("SELECT map_style_name FROM map_style") as $map) {
            $res[] = $map->map_style_name;
        }
        return $res;
    }

    #[ArrayShape(['currency' => "array", 'language' => "array", 'country' => "array"])]
    private function getTranslationGroups(): array {
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
}