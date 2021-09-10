<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonInterface;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static TranslationLanguage find(int $id, array $columns = ['*'])
 * @method static TranslationLanguage findOrFail(int $id, array $columns = ['*'])
 * @method static TranslationLanguage findOrNew(int $id, array $columns = ['*'])
 * @method static TranslationLanguage create(array $values)
 * @method static TranslationLanguage firstOrCreate(array $filter, array $values)
 * @method static TranslationLanguage firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|TranslationLanguage lockForUpdate()
 * @method static Builder|TranslationLanguage where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder|TranslationLanguage whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder|TranslationLanguage whereNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|TranslationLanguage whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder|TranslationLanguage orderBy(string $column, string $direction = 'asc')
 * @method static Builder|TranslationLanguage with(array|string  $relations)
 *
 * @property int language_id
 * @property int translation_id
 * @property int translator_user_id
 * @property string translated_phrase
 * @property string translation_status
 * @property CarbonInterface created_at
 * @property CarbonInterface updated_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class TranslationLanguage extends Model {
    protected $table = 'translation_language';
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
