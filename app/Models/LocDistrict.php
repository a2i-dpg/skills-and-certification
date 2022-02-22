<?php

namespace App\Models;

use App\Traits\CreatedByUpdatedByRelationTrait;
use App\Traits\LocDivisionBelongsToRelation;
use App\Traits\ScopeRowStatusTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * App\Models\LocDistrict
 *
 * @property int $id
 * @property string $title
 * @property string|null $bbs_code
 * @property int $loc_division_id
 * @property bool|null $is_sadar_district
 * @property-read Collection|\App\Models\LocUpazila[] $locUpazilas
 * @property-read int|null $loc_upazilas_count
 * @property-read LocDivision division
 */
class LocDistrict extends BaseModel
{
    use  ScopeRowStatusTrait, CreatedByUpdatedByRelationTrait, LocDivisionBelongsToRelation;

    protected $guarded = ['id'];

    protected $casts = [
        'is_sadar_district' => 'boolean'
    ];

    public static function getIsSadarDistrictOptions(): array
    {
        return [
            '0' => __('No'),
            '1' => __('Yes')
        ];
    }

    /**
     * Scope a query to only include the data access associate with the auth user.
     * @param Builder $query
     * @param string|null $alias
     * @return Builder
     */
    public function scopeAcl(Builder $query, string $alias = null): Builder
    {
        if (empty($alias)) {
            $alias = $this->getTable() . '.';
        }
        return $query;
    }

    public function locUpazilas(): HasMany
    {
        return $this->hasMany(LocUpazila::class);
    }

    /**
     * @return BelongsTo
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(LocDivision::class, 'loc_division_id');
    }
}
