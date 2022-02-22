<?php


namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseController;
use App\Models\StaticPage;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class StaticContentController extends BaseController
{
    /**
     * @param string|null $slug
     * @param string|null $pid
     * @return Application|Factory|View
     */
    public function index(string $slug = null, string $pid = null): View
    {
        if (!request()->current_institute_slug) {
            $pid = $slug;
        }

        $currentInstitute = app('currentInstitute');
        $staticContent = StaticPage::where('page_id', $pid);
        if ($currentInstitute) {
            $staticContent->where('institute_id', $currentInstitute->id);
        } else {
            $staticContent->whereNull('institute_id');
        }

        $staticContent = $staticContent->firstOrFail();

        return view('frontend.static-contents.browse', compact('staticContent'));
    }
}
