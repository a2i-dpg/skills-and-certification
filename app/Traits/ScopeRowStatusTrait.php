<?php

namespace App\Traits;


use App\Models\RowStatus;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait ScopeRowStatusTrait
 * @package App\Traits\ModelTraits
 * @method static Builder active()
 * @method static Builder inactive()
 * @method static Builder deleted()
 * @property-read RowStatus rowStatus
 * @property int row_status
 */
trait ScopeRowStatusTrait
{
    public function scopeActive($query): Builder
    {
        /**  @var Builder $query */
        return $query->where('row_status', 1);
    }

    public function scopeInactive($query): Builder
    {
        /**  @var Builder $query */
        return $query->where('row_status', 0);
    }

    public function scopeDeleted($query): Builder
    {
        /**  @var Builder $query */
        return $query->where('row_status', 99);
    }

    public function rowStatus(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RowStatus::class, 'row_status', 'code');
    }
}
