<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static PanoramaRating find(int $id, array $columns = ['*'])
 * @method static PanoramaRating findOrFail(int $id, array $columns = ['*'])
 * @method static PanoramaRating findOrNew(int $id, array $columns = ['*'])
 * @method static PanoramaRating create(array $values)
 * @method static PanoramaRating firstOrCreate(array $filter, array $values)
 * @method static PanoramaRating firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|PanoramaRating lockForUpdate()
 * @method static Builder|PanoramaRating where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|PanoramaRating whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|PanoramaRating whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|PanoramaRating orderBy(string $column, string $direction = 'asc')
 * @method static Builder|PanoramaRating with(array|string  $relations)
 *
 * @property int rating
 * @property int user_id
 * @property string panorama_id
 * @property CarbonInterface created:at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class PanoramaRating extends Model {
    protected $table = 'panorama_rating';
    protected $dateFormat = 'Y-m-d H:i:sO';
    protected $primaryKey = 'user_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'created:at' => 'immutable_datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
