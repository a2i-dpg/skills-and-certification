<?php

namespace App\Services;

use App\Models\TrainerFeedback;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

class TrainerFeedbackService
{

    /**
     * @param Request $request
     * @param null $id
     * @return Validator
     */
    public function validator(Request $request, $id = null): Validator
    {
        $rules = [
            'batch_id' => [
                'required',
                'int',
            ],
            'trainee_id' => [
                'required',
                'int',
            ],
            'user_id' => [
                'required',
                'int',
            ],
            'feedback' => [
                'required'
            ]
        ];

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }


    /**
     * @param array $data
     * @return TrainerFeedback
     */
    public function createFeedback(array $data): TrainerFeedback
    {
        return TrainerFeedback::create($data);
    }
}
