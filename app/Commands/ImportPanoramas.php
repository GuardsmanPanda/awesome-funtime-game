<?php

namespace App\Commands;

use App\Models\Panorama;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ImportPanoramas extends Command {
    protected $signature = 'zz:import';
    protected $description = 'Import panoramas';

    public function handle(): void {

        $this->withProgressBar(scandir(storage_path('app/public/raw')), function ($name) {
            if ($name === '.' || $name === '..') {
                return;
            }
            $this->info($name);
            do  {
                $target = strtolower(Str::random(10));
                $test = Panorama::firstWhere('jpg_name','=', 'target');
            } while ($test !== null);
            $panorama = Panorama::firstWhere('panorama_id', '=', substr($name, 0, -4));
            if ($panorama === null || $panorama->jpg_name !== null) {
                $this->error('Error on ' . $this->name);
                return;
            }
            File::move(storage_path('app/public/raw/') .$name, storage_path('app/public/sv-jpg/') . $target . '.jpg');
            $panorama->jpg_name = $target;
            $panorama->save();
        });
    }
}