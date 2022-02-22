<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use App\Models\Video;
use App\Services\VideoService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
{
    const VIEW_PATH = 'backend.videos.';
    protected VideoService $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
        $this->authorizeResource(Video::class);
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
        $video = new Video();
        return \view(self::VIEW_PATH .'edit-add', compact('video'));
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
        $videoValidatedData = $this->videoService->validator($request)->validate();
        $this->videoService->createVideo($videoValidatedData);
        try {

        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.videos.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'Video']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Video  $video
     * @return View
     */
    public function show(Video $video): View
    {
        return \view(self::VIEW_PATH .'read', compact('video'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Video $video
     * @return View
     */
    public function edit(Video $video): View
    {
        return \view(self::VIEW_PATH .'edit-add', compact('video'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Video $video
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Video $video): RedirectResponse
    {
        $validatedData = $this->videoService->validator($request, $video)->validate();

        try {
            $this->videoService->updateVideo($validatedData, $video);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.videos.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'Video']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Video $video
     * @return RedirectResponse
     */
    public function destroy(Video $video): RedirectResponse
    {
        try {
            $video->delete();
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'video']),
            'alert-type' => 'success'
        ]);
    }

    public function getDatatable(Request $request): JsonResponse
    {
        return $this->videoService->getListDataForDatatable($request);
    }
}
