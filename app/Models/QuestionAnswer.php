<?php

namespace App\Models;


use App\Traits\ScopeAclTrait;
use App\Traits\ScopeRowStatusTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Video
 * @package App\Models
 * @property int institute_id
 * @property string question
 * @property string answer
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionAnswer acl()
 * @method static Builder|QuestionAnswer active()
 * @method static Builder|QuestionAnswer withoutInstitute()
 */
class QuestionAnswer extends BaseModel
{
    use HasFactory, ScopeRowStatusTrait, ScopeAclTrait;

    protected $guarded = ['id'];

    public function institute(): belongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function scopeWithoutInstitute($query)
    {
        return $query->where('institute_id', null);
    }

}
