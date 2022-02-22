<?php

namespace App\Models;

use App\Traits\ScopeAclTrait;
use App\Traits\ScopeRowStatusTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class Tag
 * @package App\Models
 * @property Carbon enrollment_date
 * @property int batch_id
 * @property int trainee_registration_id
 * @property int trainee_id
 * @property int enrollment_status
 * @property int created_by
 * @property int trainee_enroll_id
 */

class TraineeBatch extends BaseModel
{
    use HasFactory, ScopeAclTrait, ScopeRowStatusTrait;

    const ENROLLMENT_STATUS_PENDING = 0;
    const ENROLLMENT_STATUS_ENROLLED = 1;
    const ENROLLMENT_STATUS_REJECTED = 2;

    const FEEDBACK_STATUS_NOT_GIVEN = 0;
    const FEEDBACK_STATUS_GIVEN = 1;

    protected $guarded = ['id'];


    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function traineeCourseEnroll(): BelongsTo
    {
        return $this->belongsTo(TraineeCourseEnroll::class);
    }
}
