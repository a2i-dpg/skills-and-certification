<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use App\Models\ExaminationType;
use App\Services\ExaminationTypeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ExaminationTypeController extends Controller
{
    const VIEW_PATH = 'backend.examination-types.';
    public ExaminationTypeService $examinationTypeService;

    public function __construct(ExaminationTypeService $examinationTypeService)
    {
        $this->examinationTypeService = $examinationTypeService;
        $this->authorizeResource(ExaminationType::class);
    }

    /**
     * @return View
     */
    public function index()
    {
        return \view(self::VIEW_PATH . 'browse');
    }

    /**
     * @param ExaminationType $examinationType
     * @return View
     */
    public function create(ExaminationType  $examinationType): View
    {
        return view(self::VIEW_PATH . 'edit-add', compact('examinationType'));
    }


    public function store(Request $request): RedirectResponse
    {
        $validatedData = $this->examinationTypeService->validator($request)->validate();
        try {
            $this->examinationTypeService->createExaminationType($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.examination-types.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'ExaminationType']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param ExaminationType $examinationType
     * @return View
     */
    public function show(ExaminationType $examinationType): View
    {
        return view(self::VIEW_PATH . 'read', compact('examinationType'));
    }

    /**
     * @param ExaminationType $examinationType
     * @return View
     */
    public function edit(ExaminationType $examinationType): View
    {
        return view(self::VIEW_PATH . 'edit-add', compact('examinationType'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, ExaminationType $examinationType): RedirectResponse
    {
        $validatedData = $this->examinationTypeService->validator($request)->validate();

        try {
            $this->examinationTypeService->updateExaminationType($examinationType, $validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.examination-types.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'ExaminationType']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param ExaminationType $examinationType
     * @return RedirectResponse
     */
    public function destroy(ExaminationType $examinationType): RedirectResponse
    {
        try {
            $this->examinationTypeService->deleteExaminationType($examinationType);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'ExaminationType']),
            'alert-type' => 'success'
        ]);
    }


    public function getDatatable(Request $request): JsonResponse
    {
        return $this->examinationTypeService->getExaminationTypeLists($request);
    }
}
