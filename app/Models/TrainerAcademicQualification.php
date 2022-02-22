<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TrainerAcademicQualification
 * @package App\Models
 * @property int trainer_id
 * @property int examination
 * @property int examination_name
 * @property int board
 * @property string|null institute
 * @property string roll_no
 * @property string reg_no
 * @property double result
 * @property int group
 * @property string passing_year
 * @property string|null subject
 * @property int|null course_duration
 * @property-read User trainer
 */
class TrainerAcademicQualification extends BaseModel
{
    protected $guarded = ['id'];

    public function trainer(): BelongsTo
    {
        return $this->BelongsTo(User::class, 'trainer_id', 'id');
    }
}
