<?php

namespace App\Http\Controllers;

use App\Models\VideoCategory;
use App\Services\VideoCategoryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VideoCategoryController extends Controller
{
    const VIEW_PATH = "backend.video-categories.";

    protected VideoCategoryService $videoCategoryService;

    public function __construct(VideoCategoryService $videoCategoryService)
    {
        $this->videoCategoryService = $videoCategoryService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return \view(self::VIEW_PATH .'browse');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return \view(self::VIEW_PATH .'edit-add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $videoCategoryValidatedData = $this->videoCategoryService->validator($request)->validate();
        $this->videoCategoryService->createVideoCategory($videoCategoryValidatedData);

        try {

        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.video-categories.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'Video Category']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param VideoCategory  $videoCategory
     * @return View
     */
    public function show(VideoCategory $videoCategory): View
    {
        return \view(self::VIEW_PATH .'read', compact('videoCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  VideoCategory  $videoCategory
     * @return  View
     */
    public function edit(VideoCategory $videoCategory): View
    {
        return \view(self::VIEW_PATH. 'edit-add', compact('videoCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param VideoCategory $videoCategory
     * @return RedirectResponse
     */

    public function update(Request $request, VideoCategory $videoCategory): RedirectResponse
    {
        $validatedData = $this->videoCategoryService->validator($request, $videoCategory)->validate();

        try {
            $this->videoCategoryService->updatevideoCategory($videoCategory, $validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.video-categories.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'Video Category']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param VideoCategory $videoCategory
     * @return RedirectResponse
     */
    public function destroy(VideoCategory $videoCategory): RedirectResponse
    {
        try {
            $videoCategory->delete();
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'Video Category']),
            'alert-type' => 'success'
        ]);
    }

    public function getDatatable(Request $request): JsonResponse
    {
        return $this->videoCategoryService->getListDataForDatatable($request);
    }
}
