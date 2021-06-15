<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static RealmUser find(int $id, array $columns = ['*'])
 * @method static RealmUser findOrFail(int $id, array $columns = ['*'])
 * @method static RealmUser firstOrCreate(array $filter, array $values)
 * @method static RealmUser create(array $values)
 * @method static RealmUser firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder orderBy(string $column, string $direction = 'asc')
 * @method static Builder with(array|string  $relations)
 *
 * @property int user_id
 * @property int realm_id
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class RealmUser extends Model {
    protected $table = 'realm_user';
    protected $dateFormat = 'Y-m-d H:i:s P';
    protected $primaryKey = 'user_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
