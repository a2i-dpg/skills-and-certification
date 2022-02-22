<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Batch
 * @package App\Models
 * @property string id
 * @property int batch_id
 * @property int course_id
 * @property int trainer_id
 * @property string certificate_path
 * @property string code
 * @property int publish_status // 0 => unpublished, 1=> published
 */


class Certificate extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id'];

}
