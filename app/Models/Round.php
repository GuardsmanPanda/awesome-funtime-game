<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static Round find(int $id, array $columns = ['*'])
 * @method static Round findOrFail(int $id, array $columns = ['*'])
 * @method static Round firstOrCreate(array $filter, array $values)
 * @method static Round create(array $values)
 * @method static Round firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder orderBy(string $column, string $direction = 'asc')
 * @method static Builder with(array|string  $relations)
 *
 * @property int id
 * @property int game_id
 * @property int round_number
 * @property int country_fact_id
 * @property string panorama_id
 * @property Carbon created_at
 * @property Carbon round_end_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class Round extends Model {
    protected $table = 'round';
    protected $dateFormat = 'Y-m-d H:i:s P';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
        'round_end_at' => 'datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
