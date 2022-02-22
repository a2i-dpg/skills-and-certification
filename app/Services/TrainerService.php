<?php


namespace App\Services;


use App\Helpers\Classes\FileHandler;
use App\Models\TrainerAcademicQualification;
use App\Models\TrainerExperience;
use App\Models\TrainerPersonalInformation;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class TrainerService
{
    public function storeTrainerInfo(array $data)
    {
        $trainer = User::findOrFail($data['trainer_id']);
        $trainerPersonalInfo = Arr::only($data, ['trainer_id', 'institute_id', 'name', 'mobile', 'date_of_birth', 'gender', 'nid_no', 'passport_no', 'birth_registration_no', 'marital_status', 'email', 'present_address', 'permanent_address', 'profile_pic', 'signature_pic']);


        if (isset($data['signature_pic'])) {
            $filename = FileHandler::storePhoto($data['signature_pic'], 'trainer_signature');
            $trainerPersonalInfo['signature_pic'] = 'trainer_signature/' . $filename;
        }

        if (isset($data['profile_pic'])) {
            $filename = FileHandler::storePhoto($data['profile_pic'], 'trainer',);
            $trainerPersonalInfo['profile_pic'] = 'trainer/' . $filename;
        }


        $existedPersonalInfo = TrainerPersonalInformation::where('trainer_id', $trainer->id)->first();
        if ($existedPersonalInfo) {
            $trainer->trainerPersonalInformation()->update($trainerPersonalInfo);
        } else {
            $trainer->trainerPersonalInformation()->create($trainerPersonalInfo);
        }

        foreach ($data['academicQualification'] as $key => $academicQualification) {
            if (empty($academicQualification['examination_name'])) continue;

            $existAcademicQualification = TrainerAcademicQualification::where('trainer_id', $trainer->id)
                ->where('examination', $academicQualification['examination'])
                ->first();

            if ($existAcademicQualification) {
                $existAcademicQualification->update($academicQualification);
            } else {
                $trainer->trainerAcademicQualifications()->create($academicQualification);
            }
        }


        if (isset($data['trainer_experiences'])) {
            foreach ($data['trainer_experiences'] as $key => $trainerExperience) {
                if ($trainerExperience['organization_name'] == null) {
                    continue;
                }

                if (!empty($trainerExperience['id']) && !empty($trainerExperience['delete'])) {
                    TrainerExperience::where('id', $trainerExperience['id'])->delete();
                } elseif (!empty($trainerExperience['id'])) {
                    $trainerExp = TrainerExperience::find($trainerExperience['id']);
                    $trainerExp->update($trainerExperience);
                } else {
                    $trainer->trainerExperiences()->create($trainerExperience);
                }
            }
        }

        return $trainer;
    }

    public function validator(Request $request): Validator
    {
        $rules = [
            'name' => 'required|string|max:191',
            'mobile' => 'required|string|max:20',
            'email' => 'required|string|max:191|email',
            'nid' => [
                'nullable',
                'string',
            ],
            'passport_number' => [
                'nullable',
                'string',
            ],

            'birth_registration_no' => [
                'nullable',
                'string',
            ],
            'gender' => [
                'nullable',
                'int'
            ],
            'date_of_birth' => 'nullable|date',
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'marital_status' => 'nullable|int',
            'institute_id' => 'required|int',
            'trainer_id' => 'required|int',
            'religion' => 'nullable|int',
            'nationality' => 'nullable|string',
            'profile' => 'nullable',
            'signature_pic' => 'nullable',
            'academicQualification' => 'nullable',
//            'academicQualification.*.result' => Rule::requiredIf(function () use ($request) {
//                return $request->input('academicQualification.*.examination_name') == true;
//            }),
            'trainer_experiences' => 'nullable',
        ];

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }
}
