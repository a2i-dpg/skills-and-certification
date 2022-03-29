<?php

namespace App\Http\Controllers;

use App\Models\TraineeCourseEnroll;
use App\Models\TraineeFeedback;
use App\Services\TraineeFeedbackService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TraineeFeedbackController extends Controller
{

    const VIEW_PATH = 'backend.trainee-feedback.';
    public TraineeFeedbackService $traineeFeedbackService;

    /**
     * Trainer Feedback constructor.
     * @param TraineeFeedbackService $traineeFeedbackService
     */
    public function __construct(TraineeFeedbackService $traineeFeedbackService)
    {
        $this->traineeFeedbackService = $traineeFeedbackService;
        $this->authorizeResource(TraineeFeedback::class);
    }

    /**
     * Display a listing of the resource.
     * @param int $id
     * @return View
     */
    public function index(int $id): View
    {
        $trainee = TraineeCourseEnroll::find($id);
        $traineeFeedback = TraineeFeedback::where('trainee_id', '=', $trainee->trainee_id)->first();
        return view(self::VIEW_PATH . 'index', compact('trainee', 'traineeFeedback'));
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
        $validatedData = $this->traineeFeedbackService->validator($request)->validate();
        try {
            $this->traineeFeedbackService->createFeedback($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.batches.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'FeedBack']),
            'alert-type' => 'success'
        ]);
    }
}
