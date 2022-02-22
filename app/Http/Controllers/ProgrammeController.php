<?php

namespace App\Http\Controllers;

use App\Models\Institute;
use App\Models\Programme;
use App\Services\ProgrammeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProgrammeController extends Controller
{
    const VIEW_PATH = 'backend.programmes.';
    public ProgrammeService $programmeService;

    public function __construct(ProgrammeService $programmeService)
    {
        $this->programmeService = $programmeService;
        $this->authorizeResource(Programme::class);
    }

    /**
     * Display a listing of the resource.
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
        return \view(self::VIEW_PATH . 'edit-add', compact('institutes'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $programmeValidateData = $this->programmeService->validator($request)->validate();

        try {
            $this->programmeService->createProgramme($programmeValidateData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.programmes.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'Programme']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Programme $programme
     * @return View
     */
    public function show(Programme $programme): View
    {
        return view(self::VIEW_PATH . 'read', compact('programme'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Programme $programme
     * @return View
     */
    public function edit(Programme $programme): View
    {
        $institutes = Institute::acl()->active()->get();
        return view(self::VIEW_PATH . 'edit-add', compact('programme', 'institutes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Programme $programme
     * @return RedirectResponse
     */
    public function update(Request $request, Programme $programme): RedirectResponse
    {
        $programmeValidate = $this->programmeService->validator($request, $programme->id)->validate();
        try {
            $this->programmeService->updateProgramme($programme, $programmeValidate);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.programmes.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'Programme']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Programme $programme
     * @return RedirectResponse
     */
    public function destroy(Programme $programme): RedirectResponse
    {
        try {
            $this->programmeService->deleteProgramme($programme);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }
        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'Programme']),
            'alert-type' => 'success'
        ]);
    }

    public function getDatatable(Request $request): JsonResponse
    {
        return $this->programmeService->programmeGetDataTable($request);
    }

    public function checkCode(Request $request): JsonResponse
    {
        $programme = Programme::where(['code' => $request->code])->first();
        if ($programme == null) {
            return response()->json(true);
        } else {
            return response()->json('Code already in use!');
        }
    }

}
