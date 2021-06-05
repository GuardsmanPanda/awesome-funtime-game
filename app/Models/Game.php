<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static Game find(int $id, array $columns = ['*'])
 * @method static Game findOrFail(int $id, array $columns = ['*'])
 * @method static Game firstOrCreate(array $filter, array $values)
 * @method static Game create(array $values)
 * @method static Game firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder with(array|string  $relations)
 *
 * @property int id
 * @property int round_time
 * @property int round_count
 * @property int current_round
 * @property int current_round_id
 * @property int created_by_user_id
 * @property bool is_queued
 * @property bool is_round_active
 * @property Carbon ended_at
 * @property Carbon updated_at
 * @property Carbon created_at
 * @property Carbon game_start_at
 * @property Carbon next_round_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class Game extends Model {
    protected $table = 'game';
    protected $dateFormat = 'Y-m-d H:i:s P';

    protected $casts = [
        'created_at' => 'datetime',
        'ended_at' => 'datetime',
        'game_start_at' => 'datetime',
        'next_round_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
