<?php

namespace App\Traits;


use App\Models\LocDistrict;
use App\Models\LocDivision;
use Illuminate\Database\Eloquent\Model;

trait LocDivisionBelongsToRelation
{
    public function locDivision(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        /** @var Model $this */
        return $this->belongsTo(LocDivision::class, 'loc_division_id')
            ->select(['id', 'title', 'bbs_code']);
    }
}
