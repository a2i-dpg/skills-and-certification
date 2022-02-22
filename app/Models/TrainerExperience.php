<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TrainerExperience
 * @package App\Models
 * @property int trainer_id
 * @property string organization_name
 * @property string position
 * @property \Carbon\Carbon job_start_date
 * @property \Carbon\Carbon job_end_date
 * @property-read  User trainer
 *
 */
class TrainerExperience extends BaseModel
{
    protected $guarded = ['id'];

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id', 'id');
    }
}
