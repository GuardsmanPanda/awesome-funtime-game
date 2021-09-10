<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static Country find(int $id, array $columns = ['*'])
 * @method static Country findOrFail(int $id, array $columns = ['*'])
 * @method static Country findOrNew(int $id, array $columns = ['*'])
 * @method static Country create(array $values)
 * @method static Country firstOrCreate(array $filter, array $values)
 * @method static Country firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|Country lockForUpdate()
 * @method static Builder|Country where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|Country whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|Country whereNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|Country whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|Country orderBy(string $column, string $direction = 'asc')
 * @method static Builder|Country with(array|string  $relations)
 *
 * @property int area
 * @property int area_rank
 * @property int population
 * @property int gdp_per_capita
 * @property int panorama_buffer
 * @property int population_rank
 * @property int gdp_per_capita_rank
 * @property int independence_date_rank
 * @property bool is_right_handed_driving
 * @property string tld
 * @property string iso_3
 * @property string capital
 * @property string country_code
 * @property string country_name
 * @property string dialing_code
 * @property string currency_name
 * @property string currency_code
 * @property string sub_region_name
 * @property string independent_status
 * @property CarbonInterface independence_date
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class Country extends Model {
    protected $table = 'country';
    protected $dateFormat = 'Y-m-d H:i:sO';
    protected $primaryKey = 'country_code';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'independence_date' => 'immutable_date ',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
