<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static Round find(int $id, array $columns = ['*'])
 * @method static Round findOrFail(int $id, array $columns = ['*'])
 * @method static Round findOrNew(int $id, array $columns = ['*'])
 * @method static Round create(array $values)
 * @method static Round firstOrCreate(array $filter, array $values)
 * @method static Round firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|Round lockForUpdate()
 * @method static Builder|Round where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|Round whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|Round whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|Round orderBy(string $column, string $direction = 'asc')
 * @method static Builder|Round with(array|string  $relations)
 *
 * @property int id
 * @property int game_id
 * @property int round_number
 * @property int country_fact_id
 * @property string panorama_id
 * @property string panorama_pick_strategy
 * @property CarbonInterface created_at
 * @property CarbonInterface round_end_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class Round extends Model {
    protected $table = 'round';
    protected $dateFormat = 'Y-m-d H:i:sO';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'immutable_datetime',
        'round_end_at' => 'immutable_datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
