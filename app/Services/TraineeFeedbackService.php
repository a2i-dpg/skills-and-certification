<?php

namespace App\Services;

use App\Models\TraineeFeedback;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

class TraineeFeedbackService
{

    public function validator(Request $request): Validator
    {
        $rules = [
            'batch_id' => [
                'required',
                'int'
            ],
            'user_id' => [
                'required',
                'int'
            ],
            'trainee_id' => [
                'required',
                'int'
            ],
            'feedback' => [
                'required'
            ]
        ];
        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    /**
     * @param array $data
     * @return TraineeFeedback
     */
    public function createFeedback(array $data): TraineeFeedback
    {
        return TraineeFeedback::create($data);
    }
}
