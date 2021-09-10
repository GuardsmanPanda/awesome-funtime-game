<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static TableName find(int $id, array $columns = ['*'])
 * @method static TableName findOrFail(int $id, array $columns = ['*'])
 * @method static TableName findOrNew(int $id, array $columns = ['*'])
 * @method static TableName create(array $values)
 * @method static TableName firstOrCreate(array $filter, array $values)
 * @method static TableName firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|TableName lockForUpdate()
 * @method static Builder|TableName where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|TableName whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|TableName whereNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|TableName whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|TableName orderBy(string $column, string $direction = 'asc')
 * @method static Builder|TableName with(array|string  $relations)
 *
 * @property int language_id
 * @property int translation_id
 * @property string translated_phrase
 * @property string translation_status
 * @property CarbonInterface created_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class TableName extends Model {
    protected $table = 'table_name';
    protected $dateFormat = 'Y-m-d H:i:sO';
    protected $primaryKey = 'translation_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'immutable_datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
