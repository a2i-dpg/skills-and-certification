<?php

namespace App\Http\Controllers;

use App\Models\StaticPage;
use App\Models\Institute;
use App\Services\StaticPageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

use Cache;

class StaticPageController extends Controller
{
    const VIEW_PATH = 'backend.static-page.';
    protected StaticPageService $staticPageService;

    public function __construct(StaticPageService $staticPageService)
    {
        $this->staticPageService = $staticPageService;
        $this->authorizeResource(StaticPage::class);
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return \view(self::VIEW_PATH . 'browse');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $institutes = Institute::acl()->active()->get();
        $page = StaticPage::select('id', 'page_id')->get();
        return \view(self::VIEW_PATH . 'edit-add', compact('institutes', 'page'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $staticPageValidatedData = $this->staticPageService->validator($request)->validate();
        try {
            $this->staticPageService->createStaticPage($staticPageValidatedData);
            Cache::forget('staticPageFooter');
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.static-page.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'Static Page']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param Request $request
     * @param StaticPage $staticPage
     * @return View
     */
    public function edit(Request $request, StaticPage $staticPage): View
    {
        $institutes = Institute::acl()->active()->get();
        $page = StaticPage::select('id', 'page_id')->get();

        return \view(self::VIEW_PATH . 'edit-add', compact('staticPage', 'institutes', 'page'));
    }

    /**
     * @param StaticPage $staticPage
     * @return View
     */
    public function show(StaticPage $staticPage): View
    {
        return \view(self::VIEW_PATH . 'read', compact('staticPage'));
    }


    public function update(Request $request, StaticPage $staticPage): RedirectResponse
    {
        $validatedData = $this->staticPageService->validator($request, $staticPage->id)->validate();

        try {
            $this->staticPageService->updateStaticPage($staticPage, $validatedData);
            Cache::forget('staticPageFooter');
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.static-page.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'Static Page']),
            'alert-type' => 'success'
        ]);
    }


    /**
     * @param StaticPage $staticPage
     * @return RedirectResponse
     */
    public function destroy(StaticPage $staticPage): RedirectResponse
    {
        try {
            $this->staticPageService->deleteStaticPage($staticPage);
            Cache::forget('staticPageFooter');
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'StaticPage']),
            'alert-type' => 'success'
        ]);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getDatatable(Request $request): JsonResponse
    {
        return $this->staticPageService->getListDataForDatatable($request);
    }

    /**
     * @param Request $request
     * @return object
     */
    public function imageUpload(Request $request): object
    {
        return $this->staticPageService->staticPageImage($request);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function checkCode(Request $request): JsonResponse
    {
        $staticPage = StaticPage::where([
            ['page_id', '=', $request->page_id],
            ['institute_id', '=', $request->institute_id]
        ]);

        if ($request->id && $request->id != 0) {
            $staticPage->where('id', '!=', $request->id);
        }

        if ($staticPage->first() == null) {
            return response()->json(true);
        } else {
            return response()->json('Page Id already in use!');
        }
    }
}
