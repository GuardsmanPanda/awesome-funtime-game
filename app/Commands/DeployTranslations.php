<?php

namespace App\Commands;

use App\Models\Language;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeployTranslations extends Command {
    protected $signature = 'deploy:translations';
    protected $description = 'Deploy Translations From Database';

    private string $output_dir;

    public function __construct() {
        parent::__construct();
        $this->output_dir = base_path('config/language/');
    }

    public function handle(): void {
        $this->info('Processing languages...');
        $languages = Language::where('has_translation', '=', true)->get();
        $groups = DB::select("SELECT DISTINCT translation_group FROM translation WHERE translation_group IS NOT NULL");
        foreach ($languages as $lang) {
            $data = ['none' => []];
            foreach ($groups as $group) {
                $data[$group->translation_group] = [];
            }
            $words = DB::select("
                SELECT
                    t.translation_group, t.translation_phrase, tl.translated_phrase
                FROM translation t
                LEFT JOIN translation_language tl on t.id = tl.translation_id AND tl.language_id = ?
                WHERE t.in_use
            ", [$lang->id]);
            foreach ($words as $word) {
                $data[$word->translation_group ?? 'none'][$word->translation_phrase] = $word->translated_phrase;
            }
            $content = '<?php // AUTO GENERATED DO NOT MODIFY**' . PHP_EOL;
            $content .= 'return [' . PHP_EOL;
            ksort($data['none']);
            foreach ($data['none'] as $w1 => $w2) {
                $w1 = str_replace("'", "\\'", $w1);
                $w2 = str_replace("'", "\\'", $w2);
                $content .= "    '$w1' => '$w2'," . PHP_EOL;
            }

            ksort($data);
            foreach ($data as $group => $arr) {
                if ($group === 'none') {
                    continue;
                }
                $content .= PHP_EOL . "    '$group' => [" . PHP_EOL;
                foreach ($arr as $w1 => $w2){
                    $w1 = str_replace("'", "\\'", $w1);
                    $w2 = str_replace("'", "\\'", $w2);
                    $content .= "        '$w1' => '$w2'," . PHP_EOL;
                }
                $content .= '    ],' . PHP_EOL;
            }
            $content .= '];' . PHP_EOL;
            file_put_contents($this->output_dir . $lang->translation_code . '.php', $content);
        }
    }
}