<?php

namespace App\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Ramsey\Collection\Set;

class GenerateModels extends Command {
    protected $signature = 'zz:models';
    protected $description = 'Generate Database Models';

    private string $namespace = "namespace App\\Models;\n\n";
    private string $output_dir;

    public function __construct() {
        parent::__construct();
        $this->output_dir = base_path('app/Models/');
    }

    public function handle(): void {
        $models = config('zz.database.generator_models');

        $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'afg' AND table_type = 'BASE TABLE'");
        foreach ($tables as $table) {
            if (!array_key_exists($table->table_name, $models)) {
                $models[$table->table_name] = ['class' => implode('', array_map(static function ($elem) {
                    return ucfirst($elem);
                }, explode('_', $table->table_name)))];
            }
            $cc = [];
            $cols = DB::select("SELECT * FROM information_schema.columns WHERE table_name = ?", [$table->table_name]);
            foreach ($cols as $col) {
                $cc[$col->column_name] = [$col->data_type, $col->column_default];
            }
            $models[$table->table_name]['col'] = $cc;
        }

        $constraints = DB::select("
            SELECT tc.table_name, tc.constraint_name, tc.constraint_type, kcu.column_name, ccu.table_name as foreign_table, ccu.column_name as foreign_key
            FROM information_schema.table_constraints tc
            JOIN information_schema.key_column_usage kcu ON kcu.constraint_name = tc.constraint_name AND tc.table_schema = kcu.table_schema
            JOIN information_schema.constraint_column_usage AS ccu ON ccu.constraint_name = tc.constraint_name AND ccu.table_schema = tc.table_schema
        ");
        foreach ($constraints as $constraint) {
            if ($constraint->constraint_type === 'PRIMARY KEY') {
                $models[$constraint->table_name]['key'] = $constraint->column_name;
            } else if ($constraint->constraint_type === 'FOREIGN KEY') {
                $models[$constraint->table_name]['col'][$constraint->column_name][] = $constraint->foreign_table;
                $models[$constraint->table_name]['col'][$constraint->column_name][] = $constraint->foreign_key;
            }
        }

        foreach ($models as $table_name => $model) {
            if ($table_name === 'migrations' || str_starts_with($table_name, 'z_')) {
                continue; //skip system tables
            }

            $headers = new Set('string');
            $headers->add('use Illuminate\Database\Eloquent\Builder;');
            $headers->add('use Illuminate\Database\Eloquent\Model;');

            if (array_key_exists('deleted_at', $model['col'])) {
                $headers->add('use Illuminate\Database\Eloquent\SoftDeletes;');
            }

            $class_name = $model['class'];
            $casts = [];
            $cols = [];
            foreach ($model['col'] as $col_name => $col_val) {
                if ($col_val[0] === 'text' || $col_val[0] === 'inet') {
                    if (str_starts_with($col_name, 'encrypted_')) {
                        $casts[] = [$col_name, "'encrypted'"];
                    }
                    $cols[] = [$col_name, 'string', 3];
                } else if ($col_val[0] === 'integer' || $col_val[0] === 'bigint') {
                    $cols[] = [$col_name, 'int', 0];
                } else if ($col_val[0] === 'boolean') {
                    $cols[] = [$col_name, 'bool', 1];
                } else if ($col_val[0] === 'double precision') {
                    $cols[] = [$col_name, 'float', 2];
                } else if ($col_val[0] === 'jsonb') {
                    $headers->add('use Illuminate\Database\Eloquent\Casts\AsArrayObject;');
                    $headers->add('use Illuminate\Database\Eloquent\Casts\ArrayObject;');
                    $casts[] = [$col_name, "AsArrayObject::class"];
                    $cols[] = [$col_name, 'ArrayObject', 5];
                } else if ($col_val[0] === 'timestamp with time zone') {
                    $headers->add('use Carbon\\CarbonInterface;');
                    $casts[] = [$col_name, "'immutable_datetime'"];
                    $cols[] = [$col_name, 'CarbonInterface', 10];
                } else if ($col_val[0] === 'date') {
                    $headers->add('use Carbon\\CarbonInterface;');
                    $casts[] = [$col_name, "'immutable_date '"];
                    $cols[] = [$col_name, 'CarbonInterface', 9];
                } else {
                    $this->output->writeln("***Did not understand: " . $col_val[0] . ' ' . $col_val[1]);
                }
            }
            sort($casts);

            $content = "<?php" . PHP_EOL . PHP_EOL . $this->namespace;
            foreach (array_unique(array_map(static function ($ele) { return trim($ele); }, $headers->toArray())) as $header) {
                $content .= $header . PHP_EOL;
            }
            $content .= PHP_EOL;

            $content .= "/**" . PHP_EOL;
            $content .= " * AUTO GENERATED FILE DO NOT MODIFY" . PHP_EOL;
            $content .= " *" . PHP_EOL;

            $content .= " * @method static $class_name find(int \$id, array \$columns = ['*'])" . PHP_EOL;
            $content .= " * @method static $class_name findOrFail(int \$id, array \$columns = ['*'])" . PHP_EOL;
            $content .= " * @method static $class_name findOrNew(int \$id, array \$columns = ['*'])" . PHP_EOL;
            $content .= " * @method static $class_name create(array \$values)" . PHP_EOL;
            $content .= " * @method static $class_name firstOrCreate(array \$filter, array \$values)" . PHP_EOL;
            $content .= " * @method static $class_name firstWhere(string \$column, string \$operator = null, string \$value = null, string \$boolean = 'and')" . PHP_EOL;
            $content .= " * @method static Builder|$class_name lockForUpdate()" . PHP_EOL;
            $content .= " * @method static Builder|$class_name where(string \$column, string \$operator = null, string \$value = null, string \$boolean = 'and')" . PHP_EOL;
            $content .= " * @method static Builder|$class_name whereIn(string \$column, \$values, \$boolean = 'and', \$not = false)" . PHP_EOL;
            $content .= " * @method static Builder|$class_name whereNull(string|array \$columns, bool \$boolean = 'and')" . PHP_EOL;
            $content .= " * @method static Builder|$class_name whereNotNull(string|array \$columns, bool \$boolean = 'and')" . PHP_EOL;
            $content .= " * @method static Builder|$class_name orderBy(string \$column, string \$direction = 'asc')" . PHP_EOL;
            $content .= " * @method static Builder|$class_name with(array|string  \$relations)" . PHP_EOL;
            $content .= " *" . PHP_EOL;

            usort($cols, static function ($a, $b) {
                if ($a[2] === $b[2]) return strlen($a[0]) - strlen($b[0]);
                return $a[2] - $b[2];
            });
            foreach ($cols as $col) {
                $content .= " * @property " . $col[1] . " " . $col[0] . PHP_EOL;
            }

            $content .= " *" . PHP_EOL;
            $content .= " * AUTO GENERATED FILE DO NOT MODIFY" . PHP_EOL;
            $content .= " */" . PHP_EOL;

            $content .= "class " . $model['class'] . " extends Model {" . PHP_EOL;
            if (array_key_exists('deleted_at', $model['col'])) {
                $content .= "    use SoftDeletes;" . PHP_EOL . PHP_EOL;
            }
            $content .= "    protected \$table = '$table_name';" . PHP_EOL;
            $content .= "    protected \$dateFormat = 'Y-m-d H:i:sO';" . PHP_EOL;
            if ($model['key'] !== 'id') {
                $content .= "    protected \$primaryKey = '" . $model['key'] . "';" . PHP_EOL;
                $content .= "    protected \$keyType = 'string';" . PHP_EOL;
                $content .= "    public \$incrementing = false;" . PHP_EOL;
            }
            if (!array_key_exists('updated_at', $model['col'])) {
                $content .= "    public \$timestamps = false;" . PHP_EOL;
            }
            $content .= "" . PHP_EOL;

            if (count($casts) > 0) {
                $content .= "    protected \$casts = [" . PHP_EOL;
                foreach ($casts as $cast) {
                    $content .= "        '" . $cast[0] . "' => " . $cast[1] . "," . PHP_EOL;
                }
                $content .= "    ];" . PHP_EOL;
                $content .= PHP_EOL;
            }

            $content .= "    protected \$guarded = ['id','updated_at','created_at','deleted_at'];" . PHP_EOL.  "}" . PHP_EOL;
            file_put_contents($this->output_dir . $model['class'] . '.php', $content);
            $this->output->writeln(sprintf('Model %s generated', $model['class']));
        }
    }
}
