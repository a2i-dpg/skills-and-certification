<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Batch
 * @package App\Models
 * @property string id
 * @property int batch_certificate_id
 * @property int batch_id
 * @property int trainee_id
 * @property string name
 * @property string father_name
 * @property string mother_name
 * @property string UUID
 * @property carbon date_of_birth
 * @property int publish_status // 0 => unpublished, 1=> published
 */

class TraineeCertificate extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id'];
}
