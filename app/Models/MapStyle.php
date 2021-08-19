<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static MapStyle find(int $id, array $columns = ['*'])
 * @method static MapStyle findOrFail(int $id, array $columns = ['*'])
 * @method static MapStyle findOrNew(int $id, array $columns = ['*'])
 * @method static MapStyle create(array $values)
 * @method static MapStyle firstOrCreate(array $filter, array $values)
 * @method static MapStyle firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|MapStyle lockForUpdate()
 * @method static Builder|MapStyle where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|MapStyle whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|MapStyle whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|MapStyle orderBy(string $column, string $direction = 'asc')
 * @method static Builder|MapStyle with(array|string  $relations)
 *
 * @property int id
 * @property string preview_img
 * @property string map_style_name
 * @property string map_style_source
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class MapStyle extends Model {
    protected $table = 'map_style';
    protected $dateFormat = 'Y-m-d H:i:sO';
    public $timestamps = false;

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
