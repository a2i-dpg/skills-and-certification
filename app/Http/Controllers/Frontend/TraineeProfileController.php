<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Classes\AuthHelper;
use App\Http\Controllers\BaseController;
use App\Models\Trainee;
use App\Models\TraineeFamilyMemberInfo;
use App\Services\TraineeProfileService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\TraineeAcademicQualificationn;

class TraineeProfileController extends BaseController
{
    const VIEW_PATH = "frontend.trainee-profile.";

    protected TraineeProfileService $traineeProfileService;

    public function __construct(TraineeProfileService $traineeProfileService)
    {
        $this->traineeProfileService = $traineeProfileService;
    }

    /**
     * @return View
     */
    public function editPersonalInfo(): View
    {
        $authTrainee = Trainee::getTraineeByAuthUser();

        return \view(self::VIEW_PATH . 'edit-personal-info', with(['trainee' => $authTrainee]));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updatePersonalInfo(Request $request, $id): RedirectResponse
    {
        $validated = $this->traineeProfileService->validator($request, $id)->validate();

        DB::beginTransaction();
        try {
            $this->traineeProfileService->updatePersonalInfo($validated, $id);
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::debug($exception->getMessage());

            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('personal information updated successfully!'),
            'alert-type' => 'success',
        ]);
    }


    /**
     * @param int $id
     * @return View
     */
    // public function addEditEducation(int $id): View
    // {
    //     $trainee = Trainee::findOrFail($id);
    //     $academicQualifications = $trainee->academicQualificationns->keyBy('examination');
    //     return \view(self::VIEW_PATH . 'add-edit-education', with(['trainee' => $trainee, 'academicQualifications' => $academicQualifications]));
    // }

    public function addEditEducation(int $id)
    {
        $trainee = Trainee::getTraineeByAuthUser();

        if($trainee->user_id != $id){
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        $trainee = Trainee::findOrFail($id);
        $academicQualifications = $trainee->academicQualificationns;
        //dd(count($academicQualifications));
        return \view(self::VIEW_PATH . 'add-edit-education-one', with(['trainee' => $trainee, 'academicQualifications' => $academicQualifications]));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function storeEducationInfo(Request $request): RedirectResponse
    {
        //dd($request->all());
        $validated = $this->traineeProfileService->educationInfoValidator($request);
        // $result = $this->traineeProfileService->storeAcademicInfos($validated);
        // dd($result);
        try {
            $this->traineeProfileService->storeAcademicInfos($validated);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('This process has been done successfully'),
            'alert-type' => 'success'
        ]);
    }

    public function educationInfoSingleDelete($id,$trainee_id)
    {
        $data['id'] = $id;
        $data['trainee_id'] = $trainee_id;
        
        try {
            $this->traineeProfileService->educationInfoSingleDelete($data);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('This process has been done successfully'),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param int|null $id
     * @return View
     */
    public function editGuardianInfo(int $id = null): View
    {
        $guardian = new TraineeFamilyMemberInfo();

        if ($id) {
            $guardian = TraineeFamilyMemberInfo::findOrFail($id);
        }

        return \view(self::VIEW_PATH . 'add-guardian-information', compact('guardian'));
    }

    /**
     * @param $relationWithTrainee
     * @return bool
     */
    private function relationAlreadyAdded($relationWithTrainee): bool
    {
        /** @var Trainee $authTrainee */
        $authUser = AuthHelper::getAuthUser();
        $authTrainee = Trainee::findOrFail($authUser->id);

        $guardian = TraineeFamilyMemberInfo::where('trainee_id', $authTrainee->id)
            ->where('relation_with_trainee', $relationWithTrainee)
            ->first();

        if ($guardian) {
            return true;
        }
        return false;
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function storeGuardianInfo(Request $request): RedirectResponse
    {
        $validatedData = $this->traineeProfileService->guardianInfoValidator($request)->validate();

        if ($this->relationAlreadyAdded($validatedData['relation_with_trainee'])) {
            return back()->with([
                'message' => __('generic.relation_already_added'),
                'alertType' => 'warning'
            ]);
        }

        try {
            $this->traineeProfileService->storeGuardian($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());

            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alertType' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('guardian added successfully!'),
            'alertType' => 'success',
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updateGuardianInfo(Request $request, int $id): RedirectResponse
    {
        $validatedData = $this->traineeProfileService->guardianInfoValidator($request)->validate();

        try {
            $this->traineeProfileService->updateGuardian($validatedData, $id);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());

            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alertType' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('guardian information updated successfully!'),
            'alertType' => 'success',
        ]);
    }


}
