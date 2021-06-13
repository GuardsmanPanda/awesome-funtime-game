<?php

namespace App\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Test extends Command {
    protected $signature = 'test';
    protected $description = 'test';

    public function handle(): void {
        DB::select("
             select * FROM spatial_ref_sys
        ");

        DB::update("
            UPDATE country c1 SET
            population_rank = x.pop, area_rank = x.arr
            FROM (
                 SELECT
                        cc.country_code,
                        rank() OVER (ORDER BY cc.population DESC) as pop,
                        rank() OVER (ORDER BY cc.area DESC) as arr
                        FROM country cc
            ) as x
            WHERE c1.country_code =x.country_code
        ");
    }
}
