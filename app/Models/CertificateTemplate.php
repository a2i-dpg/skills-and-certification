<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CertificateTemplate extends BaseModel
{
    public $timestamps = true;
    protected $guarded = ['id'];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
}
