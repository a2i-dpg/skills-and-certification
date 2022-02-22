<?php


namespace App\Traits;


use App\Models\LocDistrict;
use Illuminate\Database\Eloquent\Model;


/**
 * Trait LocDistrictBelongsToRelation
 * @package App\Traits
 * @property int loc_district_id
 * @property-read LocDistrict locDistrict
 */
trait LocDistrictBelongsToRelation
{
    public function locDistrict(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        /** @var Model $this */
        return $this->belongsTo(LocDistrict::class, 'loc_district_id')
            ->select(['id', 'loc_division_id', 'title', 'bbs_code']);
    }
}
