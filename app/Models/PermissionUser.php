<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static PermissionUser find(int $id, array $columns = ['*'])
 * @method static PermissionUser findOrFail(int $id, array $columns = ['*'])
 * @method static PermissionUser findOrNew(int $id, array $columns = ['*'])
 * @method static PermissionUser create(array $values)
 * @method static PermissionUser firstOrCreate(array $filter, array $values)
 * @method static PermissionUser firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|PermissionUser lockForUpdate()
 * @method static Builder|PermissionUser where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|PermissionUser whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|PermissionUser whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|PermissionUser orderBy(string $column, string $direction = 'asc')
 * @method static Builder|PermissionUser with(array|string  $relations)
 *
 * @property int user_id
 * @property int permission_id
 * @property CarbonInterface created_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class PermissionUser extends Model {
    protected $table = 'permission_user';
    protected $dateFormat = 'Y-m-d H:i:sO';
    protected $primaryKey = 'user_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'immutable_datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
