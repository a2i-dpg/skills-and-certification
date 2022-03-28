<?php

namespace App\Models;

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
 * @method static \Illuminate\Database\Eloquent\Builder|Institute acl()
 * @method static Builder|Institute active()
 * @method static Builder|Institute newModelQuery()
 * @method static Builder|Institute newQuery()
 * @method static Builder|Institute query()
 */
class Header extends BaseModel
{
    use HasFactory, ScopeRowStatusTrait;

    protected $guarded = ['id'];

    public function institute(): BelongsTo
    {
        $this->belongsTo(Institute::class);
    }
}
