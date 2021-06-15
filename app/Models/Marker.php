<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static Marker find(int $id, array $columns = ['*'])
 * @method static Marker findOrFail(int $id, array $columns = ['*'])
 * @method static Marker firstOrCreate(array $filter, array $values)
 * @method static Marker create(array $values)
 * @method static Marker firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder orderBy(string $column, string $direction = 'asc')
 * @method static Builder with(array|string  $relations)
 *
 * @property int id
 * @property string file_name
 * @property string marker_name
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class Marker extends Model {
    protected $table = 'marker';
    protected $dateFormat = 'Y-m-d H:i:s P';
    public $timestamps = false;

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
