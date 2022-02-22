<?php

namespace App\Models;

use App\Traits\CreatedByUpdatedByRelationTrait;
use App\Traits\ScopeRowStatusTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class CourseSession
 * @package App\Models
 * @property int course_id
 * @property int publish_course_id
 * @property int number_of_batches
 * @property Carbon application_start_date
 * @property Carbon application_end_date
 * @property Carbon course_start_date
 * @property int max_seat_available
 */
class CourseSession extends BaseModel
{
    use HasFactory, CreatedByUpdatedByRelationTrait, ScopeRowStatusTrait;

    protected $guarded = ['id'];

    protected $dates = [
      'application_start_date',
      'application_end_date',
      'course_start_date',
    ];

    public function publishCourse(): BelongsTo
    {
        return $this->belongsTo(PublishCourse::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

}
