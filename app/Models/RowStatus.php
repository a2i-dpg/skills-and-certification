<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RowStatus
 * @package App\Models
 * @property string title
 * @property string code
 */
class RowStatus extends Model
{
    use HasFactory;
    protected $table = "row_status";
}
