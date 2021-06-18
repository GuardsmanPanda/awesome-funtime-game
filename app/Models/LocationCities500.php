<?php

namespace App\Models;

use  Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static LocationCities500 find(int $id, array $columns = ['*'])
 * @method static LocationCities500 findOrFail(int $id, array $columns = ['*'])
 * @method static LocationCities500 firstOrCreate(array $filter, array $values)
 * @method static LocationCities500 create(array $values)
 * @method static LocationCities500 firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder orderBy(string $column, string $direction = 'asc')
 * @method static Builder with(array|string  $relations)
 *
 * @property int id
 * @property int map_box
 * @property float lat
 * @property float lng
 * @property string city_name
 * @property string state_name
 * @property string country_name
 * @property string country_code
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class LocationCities500 extends Model {
    protected $table = 'location_cities_500';
    protected $dateFormat = 'Y-m-d H:i:s P';
    public $timestamps = false;

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
