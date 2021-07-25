<?php

namespace App\Models;

use  Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static AchievementNotification find(int $id, array $columns = ['*'])
 * @method static AchievementNotification findOrFail(int $id, array $columns = ['*'])
 * @method static AchievementNotification firstOrCreate(array $filter, array $values)
 * @method static AchievementNotification create(array $values)
 * @method static AchievementNotification firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder orderBy(string $column, string $direction = 'asc')
 * @method static Builder with(array|string  $relations)
 *
 * @property int id
 * @property int user_id
 * @property int achievement_id
 * @property string notification_message
 * @property Carbon created_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class AchievementNotification extends Model {
    protected $table = 'achievement_notification';
    protected $dateFormat = 'Y-m-d H:i:s P';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
