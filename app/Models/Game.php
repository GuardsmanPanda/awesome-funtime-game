<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static Game find(int $id, array $columns = ['*'])
 * @method static Game findOrFail(int $id, array $columns = ['*'])
 * @method static Game findOrNew(int $id, array $columns = ['*'])
 * @method static Game create(array $values)
 * @method static Game firstOrCreate(array $filter, array $values)
 * @method static Game firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|Game lockForUpdate()
 * @method static Builder|Game where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|Game whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|Game whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|Game orderBy(string $column, string $direction = 'asc')
 * @method static Builder|Game with(array|string  $relations)
 *
 * @property int id
 * @property int realm_id
 * @property int round_time
 * @property int round_count
 * @property int current_round
 * @property int current_round_id
 * @property int created_by_user_id
 * @property bool is_queued
 * @property bool elo_calculated
 * @property bool is_round_active
 * @property bool should_override_user_ready
 * @property CarbonInterface ended_at
 * @property CarbonInterface updated_at
 * @property CarbonInterface created_at
 * @property CarbonInterface game_start_at
 * @property CarbonInterface next_round_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class Game extends Model {
    protected $table = 'game';
    protected $dateFormat = 'Y-m-d H:i:sO';

    protected $casts = [
        'created_at' => 'immutable_datetime',
        'ended_at' => 'immutable_datetime',
        'game_start_at' => 'immutable_datetime',
        'next_round_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
