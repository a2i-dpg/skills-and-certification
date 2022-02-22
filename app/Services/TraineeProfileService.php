<?php


namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\FileHandler;
use App\Models\Trainee;
use App\Models\TraineeAcademicQualification;
use App\Models\TraineeFamilyMemberInfo;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TraineeProfileService
{

    public function validator(Request $request, $id): Validator
    {
        $rules = [
            'name' => 'required|string|max:191',
            'address' => 'nullable|string|max:255',
            'mobile' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'email' => 'required|string|max:191|email|unique:trainees,email,' . $id,
            'physically_disable' => 'nullable',
            'disable_status' => 'nullable',
            'physical_disabilities' => 'nullable',
            'gender' => 'required|int',
            'password' => [
                'nullable',
                'bail',
                'confirmed'
            ],
            'profile_pic' => [
                'mimes:jpeg,jpg,png,gif'
            ]
        ];

        if (AuthHelper::getAuthUser('trainee')->id == $id && !empty($request->input('password'))) {
            $rules['old_password'] = [
                'bail',
                static function ($attribute, $value, $fail) {
                    if (!Hash::check($value, AuthHelper::getAuthUser('trainee')->password)) {
                        $fail(__('Credentials does not match.'));
                    }
                }
            ];
        }

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    /**
     * @param Request $request
     * @return Validator
     */
    public function guardianInfoValidator(Request $request): Validator
    {
        $rules = [
            'name' => 'required|string|max:191',
            'mobile' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|int',
            'relation_with_trainee' => 'required|int',
            'occupation' => 'nullable|string|max:191',
            'relation' => 'nullable|string|max:191',
        ];

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }


    /**
     * @param array $data
     * @param $id
     * @return bool
     */
    public function updatePersonalInfo(array $data, $id): bool
    {
        $trainee = Trainee::find($id);

        if (!empty($data['profile_pic'])) {
            $filename = FileHandler::storePhoto($data['profile_pic'], Trainee::PROFILE_PIC_FOLDER_NAME);
            $data['profile_pic'] = $filename ? Trainee::PROFILE_PIC_FOLDER_NAME . '/' . $filename : Trainee::DEFAULT_PROFILE_PIC;
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $trainee->fill($data);
        return $trainee->update();
    }


    public function educationInfoValidator(Request $request): array
    {
        return $request->validate([
            'academicQualification' => 'nullable'
        ]);
    }

    public function storeAcademicInfo(array $data): bool
    {
        $trainee = Trainee::find(AuthHelper::getAuthUser('trainee')->id);

        foreach ($data['academicQualification'] as  $academicQualification) {
            if (empty($academicQualification['examination_name'])) continue;

            $existAcademicQualification = TraineeAcademicQualification::where('trainee_id', $trainee->id)
                ->where('examination', $academicQualification['examination'])
                ->first();

            if ($existAcademicQualification) {
                $existAcademicQualification->update($academicQualification);
            } else {
                $trainee->academicQualifications()->create($academicQualification);
            }
        }

        return true;
    }

    public function storeGuardian(array $data): TraineeFamilyMemberInfo
    {
        $data['trainee_id'] = AuthHelper::getAuthUser('trainee')->id;

        return TraineeFamilyMemberInfo::create($data);
    }

    public function updateGuardian(array $data, int $id): bool
    {
        $guardian = TraineeFamilyMemberInfo::find($id);
        return $guardian->update($data);
    }
}
