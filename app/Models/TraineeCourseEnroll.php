<?php

namespace App\Models;

use App\Traits\ScopeAclTrait;
use App\Traits\ScopeRowStatusTrait;
use App\View\Components\Input\Text;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


/**
 * Class TraineeCourseEnroll
 * @package App\Models
 * @property int $id
 * @property int trainee_id
 * @property int course_id
 * @property int batch_id
 * @property string $enroll_status
 * @property string $payment_status
 * @property array $batch_preferences
 * @property int $feedback_status
 * @property text $feedback
 * @method static Builder|TraineeCourseEnroll active()
 */
class TraineeCourseEnroll extends Model
{
    use ScopeRowStatusTrait, ScopeAclTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'batch_preferences' => 'array'
    ];

    const ENROLL_STATUS_PROCESSING = 0;
    const ENROLL_STATUS_ACCEPT = 1;
    const ENROLL_STATUS_REJECT = 2;


    const PAYMENT_STATUS_PAID = 1;
    const PAYMENT_STATUS_UNPAID = 0;


    /**
     * @return BelongsTo
     */
    public function trainee(): BelongsTo
    {
        return $this->belongsTo(Trainee::class);
    }

    /**
     * @return BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
  /**
     * @return BelongsTo
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * @return HasOne
     */
    public function traineeBatch(): HasOne
    {
        return $this->hasOne(traineeBatch::class);
    }
}
