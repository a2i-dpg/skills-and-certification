<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Classes\AuthHelper;
use App\Http\Controllers\Controller;
use App\Models\Trainee;
use App\Models\TrainerBatch;
use App\Models\TrainerFeedback;
use App\Services\TrainerFeedbackService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TrainerFeedbackController extends Controller
{
    const VIEW_PATH = 'frontend.trainee.';
    public TrainerFeedbackService $trainerFeedback;

    /**
     * Trainer Feedback constructor.
     * @param TrainerFeedbackService $trainerFeedback
     */
    public function __construct(TrainerFeedbackService $trainerFeedback)
    {
        $this->trainerFeedback = $trainerFeedback;
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $id
     * @return mixed
     */
    public function index(int $id): View
    {
        /** @var Trainee $trainee */
        $trainee = Trainee::getTraineeByAuthUser();
        
        if (!$trainee) {
            return redirect()->route('frontend.trainee.login-form')->with([
                    'message' => 'You are not Auth user, Please login',
                    'alert-type' => 'error']
            );
        }
        $trainee = Trainee::find($trainee->id);
        
        $trainers = TrainerBatch::select(
            'users.name as name',
            'trainer_batches.id',
            'trainer_batches.user_id'
        )
            ->join('users', 'trainer_batches.user_id', '=', 'users.id')
            ->where('batch_id', '=', $id)->get();
        return \view(self::VIEW_PATH . 'trainers', compact('trainee', 'trainers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $id
     * @return mixed
     */
    public function feedback(int $id): View
    {
        /** @var Trainee $trainee */
        $trainee = Trainee::getTraineeByAuthUser();
        if (!$trainee) {
            return redirect()->route('frontend.trainee.login-form')->with([
                    'message' => 'You are not Auth user, Please login',
                    'alert-type' => 'error']
            );
        }
        $trainee = Trainee::find($trainee->id);

        
        $batchTrainers = TrainerBatch::findOrFail($id);
        $trainerFeedbackCheck = TrainerFeedback::where([
            'trainee_id' => $trainee->id,
            'batch_id' => $batchTrainers->batch_id,
            'user_id' => $batchTrainers->user_id
        ])->first();

        if (empty($trainerFeedbackCheck)) {
            $trainer = $batchTrainers;
        } else {
            $trainer = $trainerFeedbackCheck;
        }

        return view(self::VIEW_PATH . 'feedback', compact('trainee', 'trainer'));
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
        
        $validatedData = $this->trainerFeedback->validator($request)->validate();
        try {
            $this->trainerFeedback->createFeedback($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('frontend.trainee-enrolled-courses')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'Feedback']),
            'alert-type' => 'success'
        ]);

    }
}
