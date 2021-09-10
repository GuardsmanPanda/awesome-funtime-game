<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static AchievementNotification find(int $id, array $columns = ['*'])
 * @method static AchievementNotification findOrFail(int $id, array $columns = ['*'])
 * @method static AchievementNotification findOrNew(int $id, array $columns = ['*'])
 * @method static AchievementNotification create(array $values)
 * @method static AchievementNotification firstOrCreate(array $filter, array $values)
 * @method static AchievementNotification firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|AchievementNotification lockForUpdate()
 * @method static Builder|AchievementNotification where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|AchievementNotification whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|AchievementNotification whereNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|AchievementNotification whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|AchievementNotification orderBy(string $column, string $direction = 'asc')
 * @method static Builder|AchievementNotification with(array|string  $relations)
 *
 * @property int id
 * @property int user_id
 * @property int achievement_id
 * @property string notification_message
 * @property CarbonInterface created_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class AchievementNotification extends Model {
    protected $table = 'achievement_notification';
    protected $dateFormat = 'Y-m-d H:i:sO';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'immutable_datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
