<?php

namespace App\Models;

use  Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static Achievement find(int $id, array $columns = ['*'])
 * @method static Achievement findOrFail(int $id, array $columns = ['*'])
 * @method static Achievement firstOrCreate(array $filter, array $values)
 * @method static Achievement create(array $values)
 * @method static Achievement firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder orderBy(string $column, string $direction = 'asc')
 * @method static Builder with(array|string  $relations)
 *
 * @property int id
 * @property int sort_order
 * @property string achievement_name
 * @property string achievement_type
 * @property string achievement_description
 * @property Carbon created_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class Achievement extends Model {
    protected $table = 'achievement';
    protected $dateFormat = 'Y-m-d H:i:s P';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
