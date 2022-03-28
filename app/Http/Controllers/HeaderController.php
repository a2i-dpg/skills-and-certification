<?php

namespace App\Http\Controllers;

use App\Models\header;
use App\Http\Controllers\Controller;
use App\Services\HeaderService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

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
     * @param  \App\Models\header  $header
     * @return Response
     */
    public function show(header $header)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\header  $header
     * @return Response
     */
    public function edit(header $header)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Models\header  $header
     * @return Response
     */
    public function update(Request $request, header $header)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\header  $header
     * @return Response
     */
    public function destroy(header $header)
    {
        //
    }

    public function getDatatable(Request $request): JsonResponse
    {
        return $this->headerService->getListDataForDatatable($request);
    }
}
