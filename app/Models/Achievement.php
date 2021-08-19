<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static Achievement find(int $id, array $columns = ['*'])
 * @method static Achievement findOrFail(int $id, array $columns = ['*'])
 * @method static Achievement findOrNew(int $id, array $columns = ['*'])
 * @method static Achievement create(array $values)
 * @method static Achievement firstOrCreate(array $filter, array $values)
 * @method static Achievement firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|Achievement lockForUpdate()
 * @method static Builder|Achievement where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|Achievement whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|Achievement whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|Achievement orderBy(string $column, string $direction = 'asc')
 * @method static Builder|Achievement with(array|string  $relations)
 *
 * @property int id
 * @property int sort_order
 * @property string achievement_name
 * @property string achievement_type
 * @property string achievement_description
 * @property CarbonInterface created_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class Achievement extends Model {
    protected $table = 'achievement';
    protected $dateFormat = 'Y-m-d H:i:sO';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'immutable_datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
