<?php

namespace App\Models;

use App\Traits\ScopeAclTrait;
use App\Traits\ScopeRowStatusTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Session;
/**
 * Class ExaminationRoutine
 * @package App\Models
 * @property int institute_id
 * @property int batch_id
 * @property int training_center_id
 * @property int row_status
 * @property int created_by
 * @property int updated_by
 * @property Carbon date
 * @method static \Illuminate\Database\Eloquent\Builder|Institute acl()
 * @method static Builder|Institute active()
 * @method static Builder|Institute newModelQuery()
 * @method static Builder|Institute newQuery()
 * @method static Builder|Institute query()
 */

class ExaminationRoutine extends BaseModel
{
    use HasFactory, ScopeAclTrait, ScopeRowStatusTrait;
    public $timestamps = true;
    protected $guarded = ['id'];


    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }
    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }
    public function trainingCenter(): BelongsTo
    {
        return $this->belongsTo(TrainingCenter::class);
    }

    public function examinationRoutineDetail(): HasMany
    {
        return $this->hasMany(ExaminationRoutineDetail::class,'examination_routine_id');
    }
}
