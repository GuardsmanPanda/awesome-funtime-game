<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * AUTO GENERATED FILE DO NOT MODIFY
 *
 * @method static AuditError find(int $id, array $columns = ['*'])
 * @method static AuditError findOrFail(int $id, array $columns = ['*'])
 * @method static AuditError firstOrCreate(array $filter, array $values)
 * @method static AuditError create(array $values)
 * @method static AuditError firstWhere(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder where(string $column, string $operator = null, string $value = null, string $boolean = 'and')
 * @method static Builder whereIn(string $column, $values, $boolean = 'and', $not = false)
 * @method static Builder whereNotNull(string|array $columns, bool $boolean = 'and')
 * @method static Builder orderBy(string $column, string $direction = 'asc')
 * @method static Builder with(array|string  $relations)
 *
 * @property int id
 * @property int user_id
 * @property string ip
 * @property string path
 * @property string body
 * @property string type
 * @property string method
 * @property string message
 * @property string parameters
 * @property string country_code
 * @property string exception_trace
 * @property string exception_message
 * @property Carbon created_at
 *
 * AUTO GENERATED FILE DO NOT MODIFY
 */
class AuditError extends Model {
    protected $table = 'audit_error';
    protected $dateFormat = 'Y-m-d H:i:s P';
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $guarded = ['id','updated_at','created_at','deleted_at'];
}
