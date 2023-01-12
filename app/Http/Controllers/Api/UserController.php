<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class UserController extends BaseController
{
    /**
     * Register api
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'email'      => 'required|email',
            'password'   => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', [$validator->errors()], HttpResponse::HTTP_BAD_REQUEST);
        }

        $inputs                      = $request->all();
        $inputs['password']          = bcrypt($inputs['password']);
        $inputs['email_verified_at'] = now();
        $inputs['remember_token']    = Str::random(10);

        $user = User::create($inputs);

        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name']  =  $user->name;
        $success['email'] =  $user->email;

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt([
            'email'    => $request->input('email'),
            'password' => $request->input('password')
        ])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name']  = $user->name;
            $success['email'] = $user->email;

            return $this->sendResponse($success, 'User logged-in successfully.');
        } else {
            $error = ['error'=>'Unauthorised'];

            return $this->sendError(
                'Unauthorised.',
                $error,
                HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Get User api
     *
     * @param  Guard $auth
     * @return JsonResponse
     */
    public function user(Guard $auth): JsonResponse
    {
        $user    = $auth->user();
        $success = $user->toArray();

        return $this->sendResponse($success, 'User retrieved successfully.');
    }
}
