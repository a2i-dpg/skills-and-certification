<?php

namespace App\Http\Controllers;

use App\Helpers\Classes\AuthHelper;
use App\Models\CertificateTemplate;
use App\Models\BatchCertificate;
use App\Services\CertificateTemplateService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CertificateTemplateController extends Controller
{
    const VIEW_PATH = 'backend.certificate-templates.';
    protected CertificateTemplateService $certificateTemplateService;

    /**
     * CourseController constructor.
     * @param CertificateTemplateService $certificateTemplateService
     */
    public function __construct(CertificateTemplateService $certificateTemplateService)
    {
        $this->certificateTemplateService = $certificateTemplateService;
        $this->authorizeResource(CertificateTemplate::class);
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
        $validatedData = $this->certificateTemplateService->validator($request)->validate();
        try {
            $this->certificateTemplateService->createCertificateTemplate($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.certificate-templates.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'Gallery Album']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param CertificateTemplate $certificateTemplate
     * @return Application|Factory|View
     */
    public function show(CertificateTemplate $certificateTemplate)
    {
        return view(self::VIEW_PATH . 'read', compact('certificateTemplate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CertificateTemplate $certificateTemplate
     * @return View
     */
    public function edit(CertificateTemplate $certificateTemplate): View
    {
        return view(self::VIEW_PATH . 'edit-add', compact('certificateTemplate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param CertificateTemplate $certificateTemplate
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, CertificateTemplate $certificateTemplate): RedirectResponse
    {
        $validatedData = $this->certificateTemplateService->validator($request, $certificateTemplate->id)->validate();
        //dd($validatedData);

        $this->certificateTemplateService->updateCertificateTemplate($certificateTemplate, $validatedData);
        try {
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.certificate-templates.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'Gallery Album']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CertificateTemplate $certificateTemplate
     * @return RedirectResponse
     */
    public function destroy(CertificateTemplate $certificateTemplate): RedirectResponse
    {
        $batchCertificate = BatchCertificate::where(['certificate_template_id'=>$certificateTemplate->id])->first();
        
        if($batchCertificate){
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }
        try {
            $this->certificateTemplateService->deleteCertificateTemplate($certificateTemplate);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'Certificate Template']),
            'alert-type' => 'success'
        ]);
    }

    public function getDatatable(Request $request): JsonResponse
    {
        return $this->certificateTemplateService->getListDataForDatatable($request);
    }


    // public function updateFeaturedTemplates(Request $request): JsonResponse
    // {
    //     $certificateTemplateId = $request->data["id"];
    //     $maxFeaturedGallery = $request->data["maxFeaturedGallery"];
    //     $isFeatured = $request->data["featured"];
    //     $isFeatured = $isFeatured == "true";
    //     $certificateTemplate = CertificateTemplate::find($certificateTemplateId);
    //     $certificateTemplates = CertificateTemplate::where('institute_id', $certificateTemplate->institute_id)
    //         ->where('featured', 1)
    //         ->get();

    //     if ($isFeatured && count($certificateTemplates) >= $maxFeaturedGallery) {
    //         return response()->json([
    //             'message' => 'Max ' . $maxFeaturedGallery . ' features are supported',
    //             'alertType' => 'error',
    //         ]);
    //     }
    //     $certificateTemplate->update(['featured' => $isFeatured]);

    //     return response()->json([
    //         'message' => __('generic.object_updated_successfully', ['object' => 'Featured Templates']),
    //         'alertType' => 'success',
    //     ]);
    // }
}
