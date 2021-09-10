<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static Permission find(int $id, array $columns = ['*'])
 * @method static Permission findOrFail(int $id, array $columns = ['*'])
 * @method static Permission findOrNew(int $id, array $columns = ['*'])
 * @method static Permission create(array $values)
 * @method static Permission firstOrCreate(array $filter, array $values)
 * @method static Permission firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|Permission lockForUpdate()
 * @method static Builder|Permission where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|Permission whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|Permission whereNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|Permission whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|Permission orderBy(string $column, string $direction = 'asc')
 * @method static Builder|Permission with(array|string  $relations)
 *
 * @property int id
 * @property string permission_name
 * @property string permission_slug
 * @property CarbonInterface created_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class Permission extends Model {
    protected $table = 'permission';
    protected $dateFormat = 'Y-m-d H:i:sO';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'immutable_datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
