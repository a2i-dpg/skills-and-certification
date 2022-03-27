<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Classes\AuthHelper;
use App\Models\Trainee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class TraineeLoginController
{
    public function passwordResetForm()
    {
        if (AuthHelper::isAuthTrainee()) {
            return redirect()->route('frontend.main');
        }

        $currentInstitute = app('currentInstitute');

        return view('acl.auth.trainee-password-reset', compact('currentInstitute'));
    }

    /**
     * login with keycloak idp server
     *
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginWithKeycloak()
    {
        if (AuthHelper::isAuthTrainee()) {
            return redirect()->route('frontend.main');
        }

        return Socialite::driver('keycloak')->redirect();
    }


    public function ssoLoginCallback(Request $request)
    {
        try {
            $user = Socialite::driver('keycloak')->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))->user();
            $trainee = Trainee::firstOrCreate([
                'email' => $user->email
            ],
                [
                    'name' => $user->name,
                    'email' => $user->email,
                ]);

            if ($user) {
                Auth::guard('trainee')->login($trainee);
                Auth::shouldUse('trainee');
            } else {
                return redirect()->to('/')
                    ->with(['message' => 'User not found.', 'alert-type' => 'error']);
            }


        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            Log::debug($exception->getTraceAsString());
            return redirect()->to('/')
                ->with(['message' => 'Error occurred.', 'alert-type' => 'error']);
        }


        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended(route('frontend.main') ?: route('frontend.trainee-enrolled-courses'))
                ->with(['message' => __('generic.login_successful'), 'alert-type' => 'success']);

    }
}
