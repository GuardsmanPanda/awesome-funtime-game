<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static RealmUserRatingHistory find(int $id, array $columns = ['*'])
 * @method static RealmUserRatingHistory findOrFail(int $id, array $columns = ['*'])
 * @method static RealmUserRatingHistory findOrNew(int $id, array $columns = ['*'])
 * @method static RealmUserRatingHistory create(array $values)
 * @method static RealmUserRatingHistory firstOrCreate(array $filter, array $values)
 * @method static RealmUserRatingHistory firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|RealmUserRatingHistory lockForUpdate()
 * @method static Builder|RealmUserRatingHistory where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|RealmUserRatingHistory whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|RealmUserRatingHistory whereNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|RealmUserRatingHistory whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|RealmUserRatingHistory orderBy(string $column, string $direction = 'asc')
 * @method static Builder|RealmUserRatingHistory with(array|string  $relations)
 *
 * @property int id
 * @property int user_id
 * @property int game_id
 * @property int rating_after
 * @property int rating_change
 * @property CarbonInterface created_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class RealmUserRatingHistory extends Model {
    protected $table = 'realm_user_rating_history';
    protected $dateFormat = 'Y-m-d H:i:sO';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'immutable_datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
