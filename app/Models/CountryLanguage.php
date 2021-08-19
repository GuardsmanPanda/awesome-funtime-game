<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static CountryLanguage find(int $id, array $columns = ['*'])
 * @method static CountryLanguage findOrFail(int $id, array $columns = ['*'])
 * @method static CountryLanguage findOrNew(int $id, array $columns = ['*'])
 * @method static CountryLanguage create(array $values)
 * @method static CountryLanguage firstOrCreate(array $filter, array $values)
 * @method static CountryLanguage firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|CountryLanguage lockForUpdate()
 * @method static Builder|CountryLanguage where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|CountryLanguage whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|CountryLanguage whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|CountryLanguage orderBy(string $column, string $direction = 'asc')
 * @method static Builder|CountryLanguage with(array|string  $relations)
 *
 * @property int percentage
 * @property int language_id
 * @property int created_by_user_id
 * @property string country_code
 * @property CarbonInterface created_at
 * @property CarbonInterface updated_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class CountryLanguage extends Model {
    protected $table = 'country_language';
    protected $dateFormat = 'Y-m-d H:i:sO';
    protected $primaryKey = 'language_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
