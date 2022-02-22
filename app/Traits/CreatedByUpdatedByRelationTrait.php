<?php
namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Trait CreatedByUpdatedByRelationTrait
 * @package App\Traits\ModelTraits
 * @property int created_by
 * @property int updated_by
 * @property-read User createdBy
 * @property-read User updatedBy
 */
trait CreatedByUpdatedByRelationTrait
{
    public function createdBy(): BelongsTo
    {
        /** @var Model $this */
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy(): BelongsTo
    {
        /** @var Model $this */
        return $this->belongsTo(User::class, 'updated_by');
    }
}
