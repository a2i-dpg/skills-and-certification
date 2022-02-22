<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use App\Models\Batch;
use App\Models\Branch;
use App\Services\BranchService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BranchController extends Controller
{
    const VIEW_PATH = 'backend.branches.';
    public BranchService $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
        $this->authorizeResource(Branch::class);
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return \view(self::VIEW_PATH . 'browse');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $branch = new Batch();
        return view(self::VIEW_PATH . 'edit-add', compact('branch'));
    }


    public function store(Request $request): RedirectResponse
    {
        $validatedData = $this->branchService->validator($request)->validate();

        try {
            $this->branchService->createBranch($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.branches.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'Branch']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param Branch $branch
     * @return View
     */
    public function show(Branch $branch): View
    {
        return view(self::VIEW_PATH . 'read', compact('branch'));
    }

    /**
     * @param Branch $branch
     * @return View
     */
    public function edit(Branch $branch): View
    {
        return view(self::VIEW_PATH . 'edit-add', compact('branch'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, Branch $branch): RedirectResponse
    {
        $validatedData = $this->branchService->validator($request)->validate();

        try {
            $this->branchService->updateBranch($branch, $validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.branches.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'Branch']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param Branch $branch
     * @return RedirectResponse
     */
    public function destroy(Branch $branch): RedirectResponse
    {
        try {
            $this->branchService->deleteBranch($branch);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'Branch']),
            'alert-type' => 'success'
        ]);
    }


    public function getDatatable(Request $request): JsonResponse
    {
        return $this->branchService->getBranchLists($request);
    }
}
