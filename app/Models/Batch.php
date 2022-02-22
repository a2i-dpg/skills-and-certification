<?php

namespace App\Models;

use App\Traits\ScopeAclTrait;
use App\Traits\ScopeRowStatusTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Batch
 * @package App\Models
 * @property string title
 * @property string code
 * @property int institute_id
 * @property int|null branch_id
 * @property int training_center_id
 * @property int course_id
 * @property int max_student_enrollment
 * @property string course_coordinator_signature
 * @property string course_director_signature
 * @property int created_by
 * @property int batch_status
 * @property Carbon start_date
 * @property Carbon end_date
 * @property Carbon start_time
 * @property Carbon end_time
 * @property-read Course course
 * @property-read Institute institute
 * @property-read Branch branch
 * @property-read TrainingCenter trainingCenter
 * @method static \Illuminate\Database\Eloquent\Builder|Batch acl()
 * @method static Builder|Batch active()
 * @method static Builder|Batch newModelQuery()
 * @method static Builder|Batch newQuery()
 * @method static Builder|Batch query()
 */
class Batch extends Model
{
    use ScopeAclTrait, ScopeRowStatusTrait;

    const BATCH_STATUS_OPEN_FOR_REGISTRATION = 1;
    const BATCH_STATUS_ON_GOING = 2;
    const BATCH_STATUS_COMPLETE = 3;

    protected $guarded = ['id'];

    protected $casts = [
        'application_start_date' => 'date',
        'application_end_date' => 'date',
        'batch_start_date' => 'date',
        'batch_end_date' => 'date',
    ];


    /**
     * @return BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->BelongsTo(Course::class);
    }

    /**
     * @return BelongsTo
     */
    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    /**
     * @return BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * @return BelongsTo
     */
    public function trainingCenter(): BelongsTo
    {
        return $this->belongsTo(TrainingCenter::class);
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'trainer_batches',
            'batch_id',
            'user_id');
    }

    /**
     * @return HasMany
     */
    public function enrolledTrainees(): HasMany
    {
        $this->hasMany(TraineeCourseEnroll::class, 'batch_id', 'id')
            ->where('enroll_status', TraineeCourseEnroll::ENROLL_STATUS_ACCEPT);
    }

}
