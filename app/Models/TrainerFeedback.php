<?php

namespace App\Models;

use App\Traits\ScopeAclTrait;
use App\Traits\ScopeRowStatusTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * Class TrainerFeedback
 * @package App\Models
 * @property int trainee_id
 * @property int batch_id
 * @property int user_id
 * @property string feedback
 */
class TrainerFeedback extends BaseModel
{
    use HasFactory, ScopeAclTrait, ScopeRowStatusTrait;

    protected $guarded = ['id'];
    protected $table = 'trainer_feedbacks';

    /**
     * @return BelongsTo
     */
    public function trainee(): BelongsTo
    {
        return $this->belongsTo(Trainee::class);
    }

    /**
     * @return BelongsTo
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
