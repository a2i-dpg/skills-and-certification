<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Batch
 * @package App\Models
 * @property string id
 * @property int batch_id
 * @property int authorized_by
 * @property int created_by
 * @property string tamplate
 * @property string signature
 * @property carbon issued_date
 */

class BatchCertificate extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id'];

    public const CERTIFICATE_TEMPLATE =[
      1=>'certificateTemplate/certificate-main-sample.png',
      //2=>'certificateTemplate/certificate-sample.jpg'
    ];


    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }
}
