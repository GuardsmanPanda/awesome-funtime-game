<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static RoundUser find(int $id, array $columns = ['*'])
 * @method static RoundUser findOrFail(int $id, array $columns = ['*'])
 * @method static RoundUser firstOrCreate(array $filter, array $values)
 * @method static RoundUser create(array $values)
 * @method static RoundUser firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder orderBy(string $column, string $direction = 'asc')
 * @method static Builder with(array|string  $relations)
 *
 * @property int user_id
 * @property int round_id
 * @property bool is_correct_country
 * @property float points
 * @property float distance
 * @property string closest_panorama_id
 * @property Carbon created_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class RoundUser extends Model {
    protected $table = 'round_user';
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
