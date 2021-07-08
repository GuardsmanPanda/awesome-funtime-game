<?php

namespace App\Models;

use  Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static PanoramaRating find(int $id, array $columns = ['*'])
 * @method static PanoramaRating findOrFail(int $id, array $columns = ['*'])
 * @method static PanoramaRating firstOrCreate(array $filter, array $values)
 * @method static PanoramaRating create(array $values)
 * @method static PanoramaRating firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder orderBy(string $column, string $direction = 'asc')
 * @method static Builder with(array|string  $relations)
 *
 * @property int rating
 * @property int user_id
 * @property string panorama_id
 * @property Carbon created:at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class PanoramaRating extends Model {
    protected $table = 'panorama_rating';
    protected $dateFormat = 'Y-m-d H:i:s P';
    protected $primaryKey = 'user_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'created:at' => 'datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
