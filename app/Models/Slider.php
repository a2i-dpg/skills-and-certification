<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Traits\ScopeAclTrait;
use App\Traits\ScopeRowStatusTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Slider
 * @package App\Models
 * @property int institute_id
 * @property string title
 * @property string sub_title
 * @property string description
 * @property string link
 * @property int is_button_available
 * @property string button_text
 * @property string slider
 * @method static \Illuminate\Database\Eloquent\Builder|Institute acl()
 * @method static Builder|Institute active()
 * @method static Builder|Institute newModelQuery()
 * @method static Builder|Institute newQuery()
 * @method static Builder|Institute query()
 */
class Slider extends BaseModel
{
    use HasFactory, ScopeRowStatusTrait, ScopeAclTrait;

    protected $guarded = ['id'];

    const SLIDER_PIC_FOLDER_NAME = 'sliders';

    public const IS_BUTTON_AVAILABLE_YES = 1;
    public const IS_BUTTON_AVAILABLE_NO = 0;


    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

}
