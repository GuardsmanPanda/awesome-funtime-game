<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static Translation find(int $id, array $columns = ['*'])
 * @method static Translation findOrFail(int $id, array $columns = ['*'])
 * @method static Translation findOrNew(int $id, array $columns = ['*'])
 * @method static Translation create(array $values)
 * @method static Translation firstOrCreate(array $filter, array $values)
 * @method static Translation firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|Translation lockForUpdate()
 * @method static Builder|Translation where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|Translation whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|Translation whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|Translation orderBy(string $column, string $direction = 'asc')
 * @method static Builder|Translation with(array|string  $relations)
 *
 * @property int id
 * @property bool in_use
 * @property string translation_hint
 * @property string translation_group
 * @property string translation_phrase
 * @property CarbonInterface created_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class Translation extends Model {
    protected $table = 'translation';
    protected $dateFormat = 'Y-m-d H:i:sO';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'immutable_datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
