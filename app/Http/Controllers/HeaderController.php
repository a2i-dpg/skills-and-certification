<?php

namespace App\Http\Controllers;

use App\Models\header;
use App\Services\HeaderService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class HeaderController extends Controller
{
    const VIEW_PATH = 'backend.headers.';
    protected HeaderService $headerService;

    /**
     * @param HeaderService $headerService
     */
    public function __construct(HeaderService $headerService)
    {
        $this->headerService = $headerService;
        $this->authorizeResource(Header::class);
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
        $header = new Header();

        return \view(self::VIEW_PATH . 'edit-add', compact('header'));
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
        $headerValidatedData = $this->headerService->validator($request)->validate();
        try {
            $this->headerService->createHeader($headerValidatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }
        return redirect()->route('admin.headers.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'Header']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param header $header
     * @return View
     */
    public function show(header $header): View
    {
        return \view(self::VIEW_PATH. 'read', compact('header'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param header $header
     * @return View
     */
    public function edit(header $header): View
    {
        return \view(self::VIEW_PATH. 'edit-add', compact('header'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param header $header
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, header $header): RedirectResponse
    {
        $headerValidatedData = $this->headerService->validator($request)->validate();

        try {
            $this->headerService->updateHeader($header, $headerValidatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.headers.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'Header']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param header $header
     * @return RedirectResponse
     */
    public function destroy(header $header): RedirectResponse
    {
        try {
           $header->delete();
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'Header']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getDatatable(Request $request): JsonResponse
    {
        return $this->headerService->getListDataForDatatable($request);
    }
}
