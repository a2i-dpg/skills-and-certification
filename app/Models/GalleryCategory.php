<?php

namespace App\Models;

use App\Traits\CreatedByUpdatedByRelationTrait;
use App\Traits\ScopeRowStatusTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use App\Traits\ScopeAclTrait;

/**
 * Class GalleryCategory
 * @package App\Models
 *
 * @property int $id
 * @property string title
 * @property string image
 * @property bool featured
 * @property int row_status
 * @property Carbon|null created_at
 * @property Carbon|null updated_at
 * @property int institute_id
 * @property int|null programme_id
 * @property int|null batch_id
 * @property-read Institute institute
 * @property-read Programme programme
 * @property-read Batch batch
 * @property-read Collection|Gallery galleries
 * @property-read RowStatus rowStatus
 * @method static \Illuminate\Database\Eloquent\Builder|Institute acl()
 * @method static Builder|Institute active()
 * @method static Builder|Institute newModelQuery()
 * @method static Builder|Institute newQuery()
 * @method static Builder|Institute query()
 */
class GalleryCategory extends BaseModel
{
    use HasFactory, ScopeRowStatusTrait, CreatedByUpdatedByRelationTrait, ScopeAclTrait;

    protected $guarded = ['id'];
    const DEFAULT_IMAGE = 'gallery-category/default.jpg';

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function logoIsDefault(): bool
    {
        return $this->image === self::DEFAULT_IMAGE;
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }
}
