<?php

namespace App\Models;

use App\Traits\ScopeAclTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

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

class CertificateRequest extends BaseModel
{
    use ScopeAclTrait;

    protected $guarded = ['id'];
    const REQUESTED = 1;
    const ACCEPTED = 2;
    const REJECTED = 0;


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
