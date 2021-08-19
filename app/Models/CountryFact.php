<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static CountryFact find(int $id, array $columns = ['*'])
 * @method static CountryFact findOrFail(int $id, array $columns = ['*'])
 * @method static CountryFact findOrNew(int $id, array $columns = ['*'])
 * @method static CountryFact create(array $values)
 * @method static CountryFact firstOrCreate(array $filter, array $values)
 * @method static CountryFact firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|CountryFact lockForUpdate()
 * @method static Builder|CountryFact where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|CountryFact whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|CountryFact whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|CountryFact orderBy(string $column, string $direction = 'asc')
 * @method static Builder|CountryFact with(array|string  $relations)
 *
 * @property int id
 * @property int display_count
 * @property int created_by_user_id
 * @property string fact_text
 * @property string country_code
 * @property CarbonInterface created_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class CountryFact extends Model {
    protected $table = 'country_fact';
    protected $dateFormat = 'Y-m-d H:i:sO';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'immutable_datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
