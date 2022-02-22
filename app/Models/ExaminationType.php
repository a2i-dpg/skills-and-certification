<?php

namespace App\Models;

use App\Traits\CreatedByUpdatedByRelationTrait;
use App\Traits\ScopeRowStatusTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\ScopeAclTrait;

/**
 * Class ExaminationType
 * @package App\Models
 * @property int institute_id
 * @property string|null title
 * @property int row_status
 * @method static \Illuminate\Database\Eloquent\Builder|ExaminationType acl()
 * @method static Builder|ExaminationType active()
 */

class ExaminationType extends BaseModel
{
    use ScopeRowStatusTrait, ScopeAclTrait;

    const EXAMINATION_TYPE_STATUS_ACTIVE = 1;
    const EXAMINATION_TYPE_STATUS_INACTIVE = 0;

    public $timestamps = true;
    protected $guarded = ['id'];

    public function examination(): HasMany
    {
        return $this->hasMany(Examination::class, 'examination_type_id');
    }
    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }
}
