<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Institute;
use App\Services\GalleryService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class GalleryController extends Controller
{
    const VIEW_PATH = 'backend.galleries.';
    protected GalleryService $galleryService;

    /**
     * CourseController constructor.
     * @param GalleryService $galleryService
     */
    public function __construct(GalleryService $galleryService)
    {
        $this->galleryService = $galleryService;
        $this->authorizeResource(Gallery::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return \view(self::VIEW_PATH . 'browse');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $institutes = Institute::acl()->active()->get();
        return \view(self::VIEW_PATH . 'edit-add', compact( 'institutes'));
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
        $galleryValidatedData = $this->galleryService->validator($request)->validate();
        try {
            $this->galleryService->createGallery($galleryValidatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }
        return redirect()->route('admin.galleries.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'Gallery']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Gallery $gallery
     * @return Application|Factory|View
     */
    public function show(Gallery $gallery)
    {
        return \view(self::VIEW_PATH . 'read', compact('gallery'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Gallery $gallery
     * @return Application|Factory|View
     */
    public function edit(Gallery $gallery)
    {
        $institutes = Institute::acl()->active()->get();

        return \view(self::VIEW_PATH . 'edit-add', compact('gallery', 'institutes'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Gallery $gallery
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        $galleryValidatedData = $this->galleryService->validator($request)->validate();

        try {
            $this->galleryService->updateGallery($gallery, $galleryValidatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.galleries.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'Gallery']),
            'alert-type' => 'success'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Gallery $gallery
     * @return RedirectResponse
     */
    public function destroy(Gallery $gallery): RedirectResponse
    {
        try {
            $this->galleryService->deleteGallery($gallery);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'gallery']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getDatatable(Request $request): JsonResponse
    {
        return $this->galleryService->getListDataForDatatable($request);
    }
}
