<?php

namespace App\Models;

use App\Traits\ScopeAclTrait;
use App\Traits\ScopeRowStatusTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ExaminationRoutineDetail
 * @package App\Models
 * @property string title
 * @property int institute_id
 * @property int examination_routine_id
 * @property int examination_id
 * @property int row_status
 * @property int created_by
 * @property int updated_by
 * @property Carbon start_time
 * @property Carbon end_time
 * @method static \Illuminate\Database\Eloquent\Builder|Institute acl()
 * @method static Builder|Institute active()
 * @method static Builder|Institute newModelQuery()
 * @method static Builder|Institute newQuery()
 * @method static Builder|Institute query()
 */

class ExaminationRoutineDetail extends BaseModel
{
    use HasFactory, ScopeAclTrait, ScopeRowStatusTrait;

    public $timestamps = true;
    protected $guarded = ['id'];


    public function examinationRoutine(): BelongsTo
    {
        return $this->belongsTo(ExaminationRoutine::class, 'examination_routine_id');
    }

    public function examination(): BelongsTo
    {
        return $this->belongsTo(Examination::class, 'examination_id');
    }

}
