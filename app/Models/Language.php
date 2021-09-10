<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static Language find(int $id, array $columns = ['*'])
 * @method static Language findOrFail(int $id, array $columns = ['*'])
 * @method static Language findOrNew(int $id, array $columns = ['*'])
 * @method static Language create(array $values)
 * @method static Language firstOrCreate(array $filter, array $values)
 * @method static Language firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|Language lockForUpdate()
 * @method static Builder|Language where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|Language whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|Language whereNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|Language whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|Language orderBy(string $column, string $direction = 'asc')
 * @method static Builder|Language with(array|string  $relations)
 *
 * @property int id
 * @property int total_speakers
 * @property int native_speakers
 * @property int created_by_user_id
 * @property bool has_translation
 * @property string language_name
 * @property string two_letter_code
 * @property string translation_code
 * @property string three_letter_code
 * @property CarbonInterface created_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class Language extends Model {
    protected $table = 'language';
    protected $dateFormat = 'Y-m-d H:i:sO';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'immutable_datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
