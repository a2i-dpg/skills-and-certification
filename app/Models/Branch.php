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
 * Class Branch
 * @package App\Models
 * @property string title
 * @property int institute_id
 * @property string|null address
 * @property string|null google_map_src
 * @method static \Illuminate\Database\Eloquent\Builder|Institute acl()
 * @method static Builder|Institute active()
 * @method static Builder|Institute newModelQuery()
 * @method static Builder|Institute newQuery()
 * @method static Builder|Institute query()
 */
class Branch extends BaseModel
{
    use HasFactory, CreatedByUpdatedByRelationTrait, ScopeRowStatusTrait, ScopeAclTrait;

    protected $guarded = ['id'];


    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function publishCourses(): HasMany
    {
        return $this->hasMany(PublishCourse::class);
    }
}
