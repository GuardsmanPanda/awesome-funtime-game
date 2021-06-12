<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static GameUser find(int $id, array $columns = ['*'])
 * @method static GameUser findOrFail(int $id, array $columns = ['*'])
 * @method static GameUser firstOrCreate(array $filter, array $values)
 * @method static GameUser create(array $values)
 * @method static GameUser firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder orderBy(string $column, string $direction = 'asc')
 * @method static Builder with(array|string  $relations)
 *
 * @property int game_id
 * @property int user_id
 * @property float points_total
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class GameUser extends Model {
    protected $table = 'game_user';
    protected $dateFormat = 'Y-m-d H:i:s P';
    protected $primaryKey = 'user_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
