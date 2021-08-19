<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\ArrayObject;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static AchievementUser find(int $id, array $columns = ['*'])
 * @method static AchievementUser findOrFail(int $id, array $columns = ['*'])
 * @method static AchievementUser findOrNew(int $id, array $columns = ['*'])
 * @method static AchievementUser create(array $values)
 * @method static AchievementUser firstOrCreate(array $filter, array $values)
 * @method static AchievementUser firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|AchievementUser lockForUpdate()
 * @method static Builder|AchievementUser where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|AchievementUser whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|AchievementUser whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|AchievementUser orderBy(string $column, string $direction = 'asc')
 * @method static Builder|AchievementUser with(array|string  $relations)
 *
 * @property int id
 * @property int user_id
 * @property int user_rank
 * @property int current_level
 * @property int current_score
 * @property int achievement_id
 * @property int next_level_score
 * @property ArrayObject achievement_data
 * @property CarbonInterface updated_at
 * @property CarbonInterface created_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class AchievementUser extends Model {
    protected $table = 'achievement_user';
    protected $dateFormat = 'Y-m-d H:i:sO';

    protected $casts = [
        'achievement_data' => AsArrayObject::class,
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
