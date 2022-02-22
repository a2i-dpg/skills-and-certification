<?php

namespace App\Http\Controllers;

use App\Models\RowStatus;

class RowStatusController extends Controller
{
    const VIEW_PATH = 'backend.row-status.';
    public function index()
    {
        $rowStatuses = RowStatus::all();
        return view(self::VIEW_PATH . 'browse',compact('rowStatuses'));
    }
}
