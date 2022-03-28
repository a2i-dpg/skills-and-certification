<?php

namespace App\Models;

use App\Traits\ScopeAclTrait;
use App\Traits\ScopeRowStatusTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Institute
 * @package App\Models
 * @property int id
 * @property string title
 * @property int institute_id
 * @property string|null url
 * @property string|null target
 * @property string|null route
 * @property int order
 * @method static \Illuminate\Database\Eloquent\Builder|Header acl()
 * @method static Builder|Header active()
 * @method static Builder|Header newModelQuery()
 * @method static Builder|Header newQuery()
 * @method static Builder|Header query()
 */
class Header extends BaseModel
{
    use HasFactory, ScopeRowStatusTrait, ScopeAclTrait;

    protected $guarded = ['id'];

    public const TARGET_BLANK = '_blank';
    public const TARGET_SELF = '_self';

    /**
     * get url target options
     *
     * @return string[]
     */
    public static function getTargetOptions(): array {
        return [
            'blank' => self::TARGET_BLANK,
            'self' => self::TARGET_SELF
        ];
    }

    /**
     * @return BelongsTo
     */
    public function institute(): BelongsTo
    {
        $this->belongsTo(Institute::class);
    }
}
