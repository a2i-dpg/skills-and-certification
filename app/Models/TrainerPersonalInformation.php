<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Institute;

/**
 * App\Models\TrainerPersonalInformation
 *
 * @property int id
 * @property int trainer_id
 * @property int institute_id
 * @property string name
 * @property string|null email
 * @property string|null mobile
 * @property Carbon|null date_of_birth
 * @property int|null gender
 * @property int|null religion
 * @property string|null nationality
 * @property string|null nid_no
 * @property string|null passport_no
 * @property string|null birth_registration_no
 * @property int|null marital_status
 * @property string|null present_address
 * @property string|null permanent_address
 * @property int row_status
 * @property string|null profile_pic
 * @property string|null signature_pic
 * @property-read User user
 * @property-read Institute institute
 * @property-read User created_by
 * @property-read User updated_by
 */
class TrainerPersonalInformation extends BaseModel
{
    protected $guarded = ['id'];

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id', 'id');
    }

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }
}
