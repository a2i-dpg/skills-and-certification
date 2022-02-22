<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Services\TrainerService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TrainerController extends BaseController
{
    const  VIEW_PATH = "master::acl.trainers.";

    protected TrainerService $trainerService;

    public function __construct(TrainerService $trainerService)
    {
        $this->trainerService = $trainerService;
    }

    /**
     * display additional information page
     * @param int $userId
     * @return View
     */
    public function index(int $userId): View
    {
        $trainer = User::findOrFail($userId);
        $trainerInstitute = User::where('institute_id', $trainer->institute_id)
            ->where('user_type_id', User::USER_TYPE_TRAINER_USER_CODE)
            ->first();

        $academicQualifications = $trainer->trainerAcademicQualifications()->get()->keyBy('examination');

        return \view(self::VIEW_PATH . 'additional-information', ['trainer' => $trainer, 'trainerInstitute' => $trainerInstitute, 'academicQualifications' => $academicQualifications]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */

    public function store(Request $request): JsonResponse
    {
        $validated = $this->trainerService->validator($request)->validate();

        DB::beginTransaction();
        try {
            $trainer = $this->trainerService->storeTrainerInfo($validated);
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::debug($exception->getMessage());

            return response()->json([
                'message' => __('generic.something_wrong_try_again'),
                'alertType' => 'error'
            ]);
        }

        return response()->json([
            'message' => __('information stored successfully!'),
            'alertType' => 'success',
        ]);
    }
}
