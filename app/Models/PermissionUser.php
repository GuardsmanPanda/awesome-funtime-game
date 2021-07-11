<?php

namespace App\Models;

use  Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static PermissionUser find(int $id, array $columns = ['*'])
 * @method static PermissionUser findOrFail(int $id, array $columns = ['*'])
 * @method static PermissionUser firstOrCreate(array $filter, array $values)
 * @method static PermissionUser create(array $values)
 * @method static PermissionUser firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder orderBy(string $column, string $direction = 'asc')
 * @method static Builder with(array|string  $relations)
 *
 * @property int user_id
 * @property int permission_id
 * @property Carbon created_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class PermissionUser extends Model {
    protected $table = 'permission_user';
    protected $dateFormat = 'Y-m-d H:i:s P';
    protected $primaryKey = 'user_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
