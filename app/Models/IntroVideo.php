<?php

namespace App\Models;


use App\Traits\CreatedByUpdatedByRelationTrait;
use App\Traits\ScopeAclTrait;
use App\Traits\ScopeRowStatusTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Video
 * @package App\Models
 * @property int institute_id
 * @property string|null youtube_video_id
 * @property string|null youtube_video_url
 * @method static \Illuminate\Database\Eloquent\Builder|Institute acl()
 * @method static Builder|Institute active()
 */
class IntroVideo extends BaseModel
{
    use HasFactory, ScopeRowStatusTrait, ScopeAclTrait, CreatedByUpdatedByRelationTrait;

    protected $guarded = ['id'];

    public function institute(): belongsTo
    {
        return $this->belongsTo(Institute::class);
    }

}
