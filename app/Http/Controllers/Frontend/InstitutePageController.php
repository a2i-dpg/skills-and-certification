<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Models\Institute;

class InstitutePageController extends Controller
{
    /**
     * items of trainee course search page in frontend
     *
     * @return View
     */
    const VIEW_PATH = 'frontend.institute-list.';

    public function index(): View
    {
        $institutes = Institute::all();

        return view(self::VIEW_PATH . 'index', compact('institutes'));
    }

    /**
     * @param int $id
     * @return View
     */
    public function details(int $id): View
    {
        $institute = Institute::findOrFail($id);
        return \view('frontend.ssp.details', compact('institute'));
    }
}
