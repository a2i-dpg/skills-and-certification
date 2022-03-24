<?php


namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\Trainee;
use App\Models\TraineeAcademicQualification;
use App\Models\TraineeFamilyMemberInfo;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TraineeCourseEnrollmentService
{
    public function saveCourseEnrollData(array $data)
    {
        /** @var Trainee $authTrainee */
        $authTrainee = AuthHelper::getAuthUser();

        $trainee = Trainee::findOrFail($authTrainee->id);
        $trainee->fill(array($authTrainee));

        if (!empty($data['ethnic_group']) || !empty($data['address'])) {
            $trainee->ethnic_group = $data['ethnic_group'];
            $trainee->address = $data['address'];
            $authTrainee->update();
        }

        foreach ($data['academicQualification'] as $key => $academicQualification) {
            if (empty($academicQualification['examination_name'])) continue;

            $existAcademicQualification = TraineeAcademicQualification::where('trainee_id', $authTrainee->id)
                ->where('examination', $academicQualification['examination'])
                ->first();

            if ($existAcademicQualification) {
                $existAcademicQualification->update($academicQualification);
            } else {
                $authTrainee->academicQualifications()->create($academicQualification);
            }
        }

        //guardian info

        foreach ($data['familyMember'] as $key => $guardian) {
            if (empty($guardian['relation_with_trainee']) || empty($guardian['name'])) {
                continue;
            }

            $existedGuardianData = TraineeFamilyMemberInfo::where('trainee_id', $authTrainee->id)
                ->where('relation_with_trainee', $guardian['relation_with_trainee'])->first();

            if ($existedGuardianData) {
                $existedGuardianData->fill($guardian);
                $existedGuardianData->update();
            } else {
                $authTrainee->familyMemberInfo()->create($guardian);
            }
        }

        $data = Arr::only($data, ['batches', 'course_id']);

        if (!empty($data['batches'])) {
            $data['batch_id'] = $data['batches'][0];
            $data['batch_preferences'] = $data['batches'];
            unset($data['batches']);
        }

        return $trainee->traineeCourseEnroll()->create($data);
    }

    public function validator(Request $request, $id = null): Validator
    {
        $rules = [
            'academicQualification.*' => 'nullable',
            'academicQualification.*.examination_name' => 'nullable|string|max:191',
            'academicQualification.*.examination' => 'nullable|string|max:191',
            'academicQualification.*.institute' => 'nullable|string|max:191',
            'academicQualification.*.roll_no' => 'nullable|string|max:20',
            'academicQualification.*.reg_no' => 'nullable|string|max:20',
            'academicQualification.*.grade' => 'nullable|string|between:0, 9.99',
            'academicQualification.*.subject' => 'nullable|string|max:191',
            'familyMember.*' => 'nullable',
            'ethnic_group' => 'nullable',
            'address' => 'nullable',
            'batches' => 'nullable',
            'course_id' => 'required|int'
        ];

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

}
