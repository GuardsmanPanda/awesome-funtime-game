<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static RoundUser find(int $id, array $columns = ['*'])
 * @method static RoundUser findOrFail(int $id, array $columns = ['*'])
 * @method static RoundUser findOrNew(int $id, array $columns = ['*'])
 * @method static RoundUser create(array $values)
 * @method static RoundUser firstOrCreate(array $filter, array $values)
 * @method static RoundUser firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|RoundUser lockForUpdate()
 * @method static Builder|RoundUser where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|RoundUser whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|RoundUser whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|RoundUser orderBy(string $column, string $direction = 'asc')
 * @method static Builder|RoundUser with(array|string  $relations)
 *
 * @property int user_id
 * @property int round_id
 * @property bool is_correct_country
 * @property float points
 * @property float distance
 * @property float closest_country_code_distance
 * @property string city_name
 * @property string state_name
 * @property string region_name
 * @property string county_name
 * @property string country_code
 * @property string country_name
 * @property string state_district_name
 * @property string closest_country_code
 * @property string extended_country_code
 * @property CarbonInterface created_at
 * @property CarbonInterface location_lookup_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class RoundUser extends Model {
    protected $table = 'round_user';
    protected $dateFormat = 'Y-m-d H:i:sO';
    protected $primaryKey = 'user_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'immutable_datetime',
        'location_lookup_at' => 'immutable_datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
