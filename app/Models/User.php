<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static User find(int $id, array $columns = ['*'])
 * @method static User findOrFail(int $id, array $columns = ['*'])
 * @method static User firstOrCreate(array $filter, array $values)
 * @method static User create(array $values)
 * @method static User firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder orderBy(string $column, string $direction = 'asc')
 * @method static Builder with(array|string  $relations)
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
 * @property Carbon update_at
 * @property Carbon created_at
 * @property Carbon last_login_at
 * @property Carbon country_pick_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class User extends Model {
    protected $table = 'users';
    protected $dateFormat = 'Y-m-d H:i:s P';
    public $timestamps = false;

    protected $casts = [
        'country_pick_at' => 'datetime',
        'created_at' => 'datetime',
        'last_login_at' => 'datetime',
        'update_at' => 'datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
