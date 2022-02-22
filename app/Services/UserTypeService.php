<?php

namespace App\Services;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use App\Models\UserType;

class UserTypeService
{
    public function updateUserType(UserType $userType, Request $request): UserType
    {
        $userType->fill($request->all());
        $userType->save();

        return $userType;
    }

    public function validatorUserType(Request $request, $id = null): Validator
    {
        $rules = [
            'title' => 'required|string|max:191',
            'default_role_id' => ['required', 'int']
        ];
        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }
}
