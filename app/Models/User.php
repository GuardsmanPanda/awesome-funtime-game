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
 * @method static Builder orderBy(string $column, string $direction = 'asc')
 * @method static Builder with(array|string  $relations)
 *
 * @property int id
 * @property int twitch_id
 * @property int map_marker_id
 * @property bool is_admin
 * @property bool can_create_games
 * @property string email
 * @property string work_email
 * @property string display_name
 * @property string country_code
 * @property string translation_code
 * @property Carbon update_at
 * @property Carbon created_at
 * @property Carbon last_login_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class User extends Model {
    protected $table = 'users';
    protected $dateFormat = 'Y-m-d H:i:s P';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
        'last_login_at' => 'datetime',
        'update_at' => 'datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
