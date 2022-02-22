<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class TrainerBatch
 * @package App\Models
 * @property int Batch_id
 * @property string|null address
 * @property string|null google_map_src
 * @method static \Illuminate\Database\Eloquent\Builder|Batch acl()
 * @method static Builder|Batch active()
 * @method static Builder|Batch newModelQuery()
 * @method static Builder|Batch newQuery()
 * @method static Builder|Batch query()
 */
class TrainerBatch extends Pivot
{
    protected $guarded = ['id'];
    protected $table = 'trainer_batches';


    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }
}
