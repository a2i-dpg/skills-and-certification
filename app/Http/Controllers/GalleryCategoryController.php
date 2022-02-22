<?php

namespace App\Http\Controllers;

use App\Helpers\Classes\AuthHelper;
use App\Models\GalleryCategory;
use App\Services\GalleryCategoryService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class GalleryCategoryController extends Controller
{
    const VIEW_PATH = 'backend.gallery-categories.';
    protected GalleryCategoryService $galleryCategoryService;

    /**
     * CourseController constructor.
     * @param GalleryCategoryService $galleryCategoryService
     */
    public function __construct(GalleryCategoryService $galleryCategoryService)
    {
        $this->galleryCategoryService = $galleryCategoryService;
        $this->authorizeResource(GalleryCategory::class);
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
        return \view(self::VIEW_PATH . 'edit-add');
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
        $validatedData = $this->galleryCategoryService->validator($request)->validate();
        try {
            $this->galleryCategoryService->createGalleryCategory($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.gallery-categories.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'Gallery Album']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param GalleryCategory $galleryCategory
     * @return Application|Factory|View
     */
    public function show(GalleryCategory $galleryCategory)
    {
        return view(self::VIEW_PATH . 'read', compact('galleryCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param GalleryCategory $galleryCategory
     * @return View
     */
    public function edit(GalleryCategory $galleryCategory): View
    {
        return view(self::VIEW_PATH . 'edit-add', compact('galleryCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param GalleryCategory $galleryCategory
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, GalleryCategory $galleryCategory): RedirectResponse
    {
        $validatedData = $this->galleryCategoryService->validator($request, $galleryCategory->id)->validate();

        try {
            $this->galleryCategoryService->updateGalleryCategory($galleryCategory, $validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.gallery-categories.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'Gallery Album']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param GalleryCategory $galleryCategory
     * @return RedirectResponse
     */
    public function destroy(GalleryCategory $galleryCategory): RedirectResponse
    {
        try {
            $this->galleryCategoryService->deleteGalleryCategory($galleryCategory);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'Gallery Category']),
            'alert-type' => 'success'
        ]);
    }

    public function getDatatable(Request $request): JsonResponse
    {
        return $this->galleryCategoryService->getListDataForDatatable($request);
    }


    public function updateFeaturedGalleries(Request $request): JsonResponse
    {
        $galleryCategoryId = $request->data["id"];
        $maxFeaturedGallery = $request->data["maxFeaturedGallery"];
        $isFeatured = $request->data["featured"];
        $isFeatured = $isFeatured == "true";
        $galleryCategory = GalleryCategory::find($galleryCategoryId);
        $galleryCategories = GalleryCategory::where('institute_id', $galleryCategory->institute_id)
            ->where('featured', 1)
            ->get();

        if ($isFeatured && count($galleryCategories) >= $maxFeaturedGallery) {
            return response()->json([
                'message' => 'Max ' . $maxFeaturedGallery . ' features are supported',
                'alertType' => 'error',
            ]);
        }
        $galleryCategory->update(['featured' => $isFeatured]);

        return response()->json([
            'message' => __('generic.object_updated_successfully', ['object' => 'Featured Galleries']),
            'alertType' => 'success',
        ]);
    }
}
