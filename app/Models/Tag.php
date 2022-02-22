<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Tag
 * @package App\Models
 * @property string tag_en
 * @property string tag_bn
 * @property int institute_id
 */

class Tag extends BaseModel
{
    use HasFactory;
    protected $guarded = ['id'];


    public function taggable(): MorphTo
    {
        return $this->morphTo();
    }

}
