<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * Class RoutineSlot
 * @package App\Models
 * @property int institute_id
 * @property int routine_id
 * @property int user_id
 * @property int batch_id
 * @property int row_status
 * @property int created_by
 * @property int updated_by
 * @property string|null class
 * @property Carbon start_time
 * @property Carbon end_time
 * @method static \Illuminate\Database\Eloquent\Builder|Batch acl()
 * @method static Builder|Batch active()
 * @method static Builder|Batch newModelQuery()
 * @method static Builder|Batch newQuery()
 * @method static Builder|Batch query()
 */

class RoutineSlot extends BaseModel
{
    protected $guarded = ['id'];


    /**
     * @return BelongsTo
     */
    public function routine(): BelongsTo
    {
        return $this->belongsTo(Routine::class, 'routine_id');
    }

    /**
     * Trainer who is an authenticated user bound with this routine slot
     *
     * @return BelongsTo
     */
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
