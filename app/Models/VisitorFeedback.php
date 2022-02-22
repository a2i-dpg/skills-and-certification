<?php

namespace App\Models;


use App\Traits\CreatedByUpdatedByRelationTrait;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\ScopeAclTrait;
use App\Traits\ScopeRowStatusTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class VisitorFeedback
 * @package App\Models
 * @property int institute_id
 * @property string name
 * @property string mobile
 * @property string email
 * @property string|null address
 * @property string comment
 * @property int form_type
 * @property Carbon read_at
 * @property-read Institute institute
 * @method static \Illuminate\Database\Eloquent\Builder|Institute acl()
 * @method static Builder|Institute active()
 * @method static Builder|Institute newModelQuery()
 * @method static Builder|Institute newQuery()
 * @method static Builder|Institute query()
 */
class VisitorFeedback extends BaseModel
{
    use HasFactory, ScopeRowStatusTrait, CreatedByUpdatedByRelationTrait, ScopeAclTrait;

    protected $guarded = ['id'];
    const FORM_TYPE_FEEDBACK = 1;
    const FORM_TYPE_CONTACT = 2;

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

}
