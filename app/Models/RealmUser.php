<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\ArrayObject;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static RealmUser find(int $id, array $columns = ['*'])
 * @method static RealmUser findOrFail(int $id, array $columns = ['*'])
 * @method static RealmUser findOrNew(int $id, array $columns = ['*'])
 * @method static RealmUser create(array $values)
 * @method static RealmUser firstOrCreate(array $filter, array $values)
 * @method static RealmUser firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|RealmUser lockForUpdate()
 * @method static Builder|RealmUser where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|RealmUser whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|RealmUser whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|RealmUser orderBy(string $column, string $direction = 'asc')
 * @method static Builder|RealmUser with(array|string  $relations)
 *
 * @property int user_id
 * @property int realm_id
 * @property float elo_rating
 * @property ArrayObject elo_rating_history
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class RealmUser extends Model {
    protected $table = 'realm_user';
    protected $dateFormat = 'Y-m-d H:i:sO';
    protected $primaryKey = 'user_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'elo_rating_history' => AsArrayObject::class,
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
