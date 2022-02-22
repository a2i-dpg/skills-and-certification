<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\VisitorFeedback;
use App\Services\VisitorFeedbackService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VisitorFeedbackController extends Controller
{
    const VIEW_PATH = 'backend.visitor-feedback.';
    protected VisitorFeedbackService $visitorFeedbackService;

    public function __construct(VisitorFeedbackService $visitorFeedbackService)
    {
        $this->visitorFeedbackService = $visitorFeedbackService;
    }

    public function index(): View
    {
        return \view(self::VIEW_PATH . 'browse');
    }

    public function show(VisitorFeedback $visitorFeedback): View
    {
        $visitorFeedback->read_at = Carbon::now();
        $visitorFeedback->save();

        return view(self::VIEW_PATH . 'read', compact('visitorFeedback'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $this->visitorFeedbackService->validator($request)->validate();
        try {
            $this->visitorFeedbackService->createVisitorFeedback($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }
        return back()->with([
            'message' => __('আপনার মতামতটি সফলভাবে সংরক্ষিত হয়েছে!'),
            'alert-type' => 'success'
        ]);

    }

    public function destroy(VisitorFeedback $visitorFeedback): RedirectResponse
    {
        try {
            $this->visitorFeedbackService->deleteVisitorFeedback($visitorFeedback);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'VisitorFeedback']),
            'alert-type' => 'success'
        ]);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getDatatable(Request $request): JsonResponse
    {
        return $this->visitorFeedbackService->getVisitorFeedbackLists($request);
    }

    /**
     * show trainee feedbacks given on trainer
     *
     * @return View
     */
    public function showTraineeFeedbacks(): View
    {
        return \view(self::VIEW_PATH . 'trainee-feedbacks');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function traineeFeedbackDatatable(Request $request): JsonResponse
    {
        return $this->visitorFeedbackService->getTraineeFeedbackDatatable($request);
    }


    /**
     * show trainer feedbacks given by trainees
     *
     * @return View
     */
    public function showTrainerFeedbacks(): View
    {
        return \view(self::VIEW_PATH . 'trainer-feedbacks');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function trainerFeedbackDatatable(Request $request): JsonResponse
    {
        return $this->visitorFeedbackService->getTrainerFeedbackDatatable($request);
    }
}
