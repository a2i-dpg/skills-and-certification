<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class LocalizationController extends BaseController
{
    /**
     * @param Request $request
     * @param string $language
     * @return RedirectResponse
     */
    public function changeLanguage(Request $request, string $language): RedirectResponse
    {
        try {
            App::setLocale($language);
            session()->put('locale', $language);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with(['message' => 'Something Wrong. Please Try Again', 'alert-type' => 'error']);
        }

        return back()->with(['message' => __('generic.lang'), 'alert-type' => 'success']);
    }
}
