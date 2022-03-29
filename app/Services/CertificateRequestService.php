<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\FileHandler;
use App\Models\CertificateRequest;
use App\Models\Trainee;
use App\Models\TraineeBatch;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CertificateRequestService
{

    /**
     * @param Request $request
     * @param null $id
     * @return Validator
     */
    public function validator(Request $request, $id = null): Validator
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:191'
            ],
            'father_name' => [
                'required',
                'string',
                'max:191'
            ],
            'mother_name' => [
                'required',
                'string',
                'max:191'
            ],
            'date_of_birth' => [
                'required'
            ],
            'trainee_course_enrolls_id' => [
                'required',
            ],
            'id_image' => [
                'image',
                'max:500',
                'mimes:jpg,bmp,png,jpeg,svg',

            ],

        ];

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function requestTraineeCertificate(array $data)
    {
        $trainee = Trainee::getTraineeByAuthUser();
        $traineeBatch = TraineeBatch::where('trainee_course_enroll_id','=',$data['trainee_course_enrolls_id'])->first();
        $data['trainee_batch_id'] = $traineeBatch->id;
        $data['trainee_id'] = $trainee->id;
        if (!empty($data['id_image'])) {
            $filename = FileHandler::storePhoto($data['id_image'], 'trainee-proof');
        }

        if ($filename) {
            $data['id_image'] = 'trainee-proof/' . $filename;
        } else {
            $data['id_image'] = '';
        }

        return CertificateRequest::create($data);
    }

}
