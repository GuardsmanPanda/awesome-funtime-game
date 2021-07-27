<?php

namespace App\Models;

use  Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static AchievementUser find(int $id, array $columns = ['*'])
 * @method static AchievementUser findOrFail(int $id, array $columns = ['*'])
 * @method static AchievementUser firstOrCreate(array $filter, array $values)
 * @method static AchievementUser create(array $values)
 * @method static AchievementUser firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder orderBy(string $column, string $direction = 'asc')
 * @method static Builder with(array|string  $relations)
 *
 * @property int id
 * @property int user_id
 * @property int user_rank
 * @property int current_level
 * @property int current_score
 * @property int achievement_id
 * @property int next_level_score
 * @property ArrayObject achievement_data
 * @property Carbon updated_at
 * @property Carbon created_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class AchievementUser extends Model {
    protected $table = 'achievement_user';
    protected $dateFormat = 'Y-m-d H:i:s P';

    protected $casts = [
        'achievement_data' => AsArrayObject::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
