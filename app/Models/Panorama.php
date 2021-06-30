<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static Panorama find(int $id, array $columns = ['*'])
 * @method static Panorama findOrFail(int $id, array $columns = ['*'])
 * @method static Panorama firstOrCreate(array $filter, array $values)
 * @method static Panorama create(array $values)
 * @method static Panorama firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder orderBy(string $column, string $direction = 'asc')
 * @method static Builder with(array|string  $relations)
 *
 * @property int map_box
 * @property int added_by_user_id
 * @property string jpg_name
 * @property string city_name
 * @property string town_name
 * @property string file_name
 * @property string state_name
 * @property string region_name
 * @property string county_name
 * @property string panorama_id
 * @property string village_name
 * @property string country_code
 * @property string country_name
 * @property string municipality_name
 * @property string state_district_name
 * @property string extended_country_code
 * @property Carbon captured_date
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class Panorama extends Model {
    protected $table = 'panorama';
    protected $dateFormat = 'Y-m-d H:i:s P';
    protected $primaryKey = 'panorama_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'captured_date' => 'date',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
