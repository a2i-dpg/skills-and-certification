<?php


namespace App\Traits;


use App\Models\LocUpazila;
use Illuminate\Database\Eloquent\Model;

trait LocUpazilaBelongsToRelation
{
    public function locUpazila(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        /** @var Model $this */
        return $this->belongsTo(LocUpazila::class, 'loc_upazila_id')
            ->select(['id', 'loc_district_id', 'title']);
    }
}
