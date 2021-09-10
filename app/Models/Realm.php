<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static Realm find(int $id, array $columns = ['*'])
 * @method static Realm findOrFail(int $id, array $columns = ['*'])
 * @method static Realm findOrNew(int $id, array $columns = ['*'])
 * @method static Realm create(array $values)
 * @method static Realm firstOrCreate(array $filter, array $values)
 * @method static Realm firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|Realm lockForUpdate()
 * @method static Builder|Realm where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|Realm whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|Realm whereNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|Realm whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|Realm orderBy(string $column, string $direction = 'asc')
 * @method static Builder|Realm with(array|string  $relations)
 *
 * @property int id
 * @property string realm_name
 * @property CarbonInterface created_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class Realm extends Model {
    protected $table = 'realm';
    protected $dateFormat = 'Y-m-d H:i:sO';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'immutable_datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
