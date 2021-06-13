<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static Realm find(int $id, array $columns = ['*'])
 * @method static Realm findOrFail(int $id, array $columns = ['*'])
 * @method static Realm firstOrCreate(array $filter, array $values)
 * @method static Realm create(array $values)
 * @method static Realm firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder orderBy(string $column, string $direction = 'asc')
 * @method static Builder with(array|string  $relations)
 *
 * @property int id
 * @property string realm_name
 * @property Carbon created_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class Realm extends Model {
    protected $table = 'realm';
    protected $dateFormat = 'Y-m-d H:i:s P';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
