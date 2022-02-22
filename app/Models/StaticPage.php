<?php

namespace App\Models;

use App\Traits\CreatedByUpdatedByRelationTrait;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\ScopeAclTrait;
use App\Traits\ScopeRowStatusTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class StaticPage
 * @package App\Models
 * @property int content_type
 * @property int institute_id
 * @property string title
 * @property string page_id
 * @property string page_contents
 * @property int row_status
 * @method static \Illuminate\Database\Eloquent\Builder|Institute acl()
 * @method static Builder|Institute active()
 * @method static Builder|Institute newModelQuery()
 * @method static Builder|Institute newQuery()
 * @method static Builder|Institute query()
 */

class StaticPage extends BaseModel
{
    use HasFactory, scopeRowStatusTrait, CreatedByUpdatedByRelationTrait, ScopeAclTrait;

    const PAGE_ID_ABOUT_US = 'aboutus';

    protected $table = 'static_pages';
    protected $guarded = ['id'];

    public function title(): ?string
    {
        return $this->title;
    }


    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }
}
