<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use App\Traits\ScopeAclTrait;
use App\Traits\ScopeRowStatusTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class VideoCategory
 * @package App\Models
 * @property int institute_id
 * @property int|null parent_id
 * @property string title
 * @method static \Illuminate\Database\Eloquent\Builder|Institute acl()
 * @method static Builder|Institute active()
 * @method static Builder|Institute newModelQuery()
 * @method static Builder|Institute newQuery()
 * @method static Builder|Institute query()
 */

class VideoCategory extends BaseModel
{
    use HasFactory, ScopeRowStatusTrait, ScopeAclTrait;
    protected $guarded = ['id'];

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

}
