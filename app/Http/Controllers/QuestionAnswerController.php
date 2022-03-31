<?php

namespace App\Http\Controllers;

use App\Models\IntroVideo;
use App\Models\QuestionAnswer;
use App\Services\QuestionAnswerService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class QuestionAnswerController extends Controller
{
    const VIEW_PATH = 'backend.question-answers.';
    protected QuestionAnswerService $questionAnswersService;

    public function __construct(QuestionAnswerService $questionAnswersService)
    {
        $this->questionAnswersService = $questionAnswersService;
        $this->authorizeResource(QuestionAnswer::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return \view(self::VIEW_PATH .'browse');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): View
    {
        $questionAnswer = new QuestionAnswer();
        return \view(self::VIEW_PATH .'edit-add', compact('questionAnswer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $questionAnswerValidatedData = $this->questionAnswersService->validator($request)->validate();

        try {
            $this->questionAnswersService->createQuestionAnswer($questionAnswerValidatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('admin.question-answers.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'FAQ']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param QuestionAnswer $questionAnswer
     * @return View
     */
    public function show(QuestionAnswer $questionAnswer): View
    {
        return \view(self::VIEW_PATH .'read', compact('questionAnswer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param QuestionAnswer $questionAnswer
     * @return View
     */
    public function edit(QuestionAnswer $questionAnswer): View
    {
        return \view(self::VIEW_PATH .'edit-add', compact('questionAnswer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param QuestionAnswer $questionAnswer
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, QuestionAnswer $questionAnswer): RedirectResponse
    {
        
        $validatedData = $this->questionAnswersService->validator($request, $questionAnswer->id)->validate();

        try {
            $this->questionAnswersService->updateIntroVideo($validatedData, $questionAnswer);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }
        return redirect()->route('admin.question-answers.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'FAQ']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param QuestionAnswer $questionAnswer
     * @return RedirectResponse
     */
    public function destroy(QuestionAnswer $questionAnswer): RedirectResponse
    {
        try {
            $questionAnswer->delete();
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'FAQ']),
            'alert-type' => 'success'
        ]);
    }

    public function getDatatable(Request $request): JsonResponse
    {
        return $this->questionAnswersService->getListDataForDatatable($request);
    }
}
