<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static User find(int $id, array $columns = ['*'])
 * @method static User findOrFail(int $id, array $columns = ['*'])
 * @method static User findOrNew(int $id, array $columns = ['*'])
 * @method static User create(array $values)
 * @method static User firstOrCreate(array $filter, array $values)
 * @method static User firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|User lockForUpdate()
 * @method static Builder|User where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|User whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|User whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|User orderBy(string $column, string $direction = 'asc')
 * @method static Builder|User with(array|string  $relations)
 *
 * @property int id
 * @property int twitch_id
 * @property int game_rank_1
 * @property int game_rank_2
 * @property int game_rank_3
 * @property int language_id
 * @property int map_style_id
 * @property int map_marker_id
 * @property int game_rank_rank
 * @property int logged_into_realm_id
 * @property bool is_admin
 * @property bool can_create_games
 * @property bool achievement_refresh_needed
 * @property string email
 * @property string work_email
 * @property string display_name
 * @property string country_code
 * @property string country_code_1
 * @property string country_code_2
 * @property string country_code_3
 * @property string country_code_4
 * @property CarbonInterface update_at
 * @property CarbonInterface created_at
 * @property CarbonInterface last_login_at
 * @property CarbonInterface country_pick_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class User extends Model {
    protected $table = 'users';
    protected $dateFormat = 'Y-m-d H:i:sO';
    public $timestamps = false;

    protected $casts = [
        'country_pick_at' => 'immutable_datetime',
        'created_at' => 'immutable_datetime',
        'last_login_at' => 'immutable_datetime',
        'update_at' => 'immutable_datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
