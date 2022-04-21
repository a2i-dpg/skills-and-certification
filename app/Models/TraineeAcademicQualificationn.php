<?php

namespace App\Models;


use App\Traits\CreatedByUpdatedByRelationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class TraineeAcademicQualificationn extends BaseModel
{
    use HasFactory, CreatedByUpdatedByRelationTrait;
    public $timestamps = true;
    protected $guarded = ['id'];

    public function trainee(): BelongsTo
    {
        return $this->BelongsTo(Trainee::class);
    }
}
