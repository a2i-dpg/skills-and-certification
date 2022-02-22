<?php

namespace App\Models;

use App\Traits\CreatedByUpdatedByRelationTrait;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\ScopeAclTrait;
use App\Traits\ScopeRowStatusTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class TrainingCenter
 * @package App\Models
 * @property string title
 * @property int institute_id
 * @property int branch_id
 * @property string|null address
 * @property string|null course_coordinator_signature
 * @property string|null course_director_signature
 * @property string|null google_map_src
 * @method static \Illuminate\Database\Eloquent\Builder|Institute acl()
 * @method static Builder|Institute active()
 * @method static Builder|Institute newModelQuery()
 * @method static Builder|Institute newQuery()
 * @method static Builder|Institute query()
 */
class TrainingCenter extends BaseModel
{
    use HasFactory, CreatedByUpdatedByRelationTrait, ScopeRowStatusTrait, ScopeAclTrait;
    protected $guarded = ['id'];

    const TRAINING_CENTER_STATUS_INACTIVE= 0;
    const TRAINING_CENTER_STATUS_ACTIVE= 1;
    const TRAINING_CENTER_STATUS_DELETED= 99;


    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function publishCourses(): HasMany
    {
        return $this->hasMany(PublishCourse::class);
    }

}
