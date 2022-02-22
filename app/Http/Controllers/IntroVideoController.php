<?php

namespace App\Http\Controllers;

use App\Models\IntroVideo;
use App\Models\Video;
use App\Services\IntroVideoService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class IntroVideoController extends Controller
{
    const VIEW_PATH = 'backend.intro-videos.';
    protected IntroVideoService $introVideoService;

    public function __construct(IntroVideoService $introVideoService)
    {
        $this->introVideoService = $introVideoService;
        $this->authorizeResource(IntroVideo::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $videosCount = IntroVideo::acl()->get()->count();
        return \view(self::VIEW_PATH . 'browse', compact('videosCount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $video = new Video();
        return \view(self::VIEW_PATH . 'edit-add', compact('video'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $introVideoValidatedData = $this->introVideoService->validator($request)->validate();

        try {
            $this->introVideoService->createIntroVideo($introVideoValidatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.intro-videos.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'Intro Video']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param IntroVideo $introVideo
     * @return View
     */
    public function show(IntroVideo $introVideo): View
    {
        return \view(self::VIEW_PATH . 'read', compact('introVideo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param IntroVideo $introVideo
     * @return View
     */
    public function edit(IntroVideo $introVideo): View
    {
        return \view(self::VIEW_PATH . 'edit-add', compact('introVideo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param IntroVideo $introVideo
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, IntroVideo $introVideo): RedirectResponse
    {
        $validatedData = $this->introVideoService->validator($request, $introVideo->id)->validate();

        try {
            $this->introVideoService->updateIntroVideo($validatedData, $introVideo);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }
        return redirect()->route('admin.intro-videos.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'Intro Video']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param IntroVideo $introVideo
     * @return RedirectResponse
     */
    public function destroy(IntroVideo $introVideo): RedirectResponse
    {
        try {
            $introVideo->delete();
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'Intro Video']),
            'alert-type' => 'success'
        ]);
    }

    public function getDatatable(Request $request): JsonResponse
    {
        return $this->introVideoService->getListDataForDatatable($request);
    }
}
