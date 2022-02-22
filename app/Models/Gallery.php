<?php

namespace App\Models;

use App\Traits\CreatedByUpdatedByRelationTrait;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\ScopeAclTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Gallery
 * @package App\Models
 * @property int content_type
 * @property int institute_id
 * @property int gallery_category_id
 * @property string content_title
 * @property string content_path
 * @property int is_youtube_video
 * @property Carbon publish_date
 * @property Carbon archive_date
 * @property string you_tube_video_id
 * @property-read GalleryCategory galleryCategory
 * @method static \Illuminate\Database\Eloquent\Builder|Institute acl()
 * @method static Builder|Institute active()
 * @method static Builder|Institute newModelQuery()
 * @method static Builder|Institute newQuery()
 * @method static Builder|Institute query()
 */
class Gallery extends BaseModel
{
    use HasFactory, CreatedByUpdatedByRelationTrait, ScopeAclTrait;

    protected $guarded = ['id'];
    const FILE_DIRECTORY = 'gallery/';
    const CONTENT_TYPE_IMAGE = 1;
    const CONTENT_TYPE_VIDEO = 2;

    const CONTENT_TYPES = [
        self::CONTENT_TYPE_IMAGE => 'Image',
        self::CONTENT_TYPE_VIDEO => 'Video',
    ];

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function galleryCategory(): BelongsTo
    {
        return $this->belongsTo(GalleryCategory::class);
    }

}
