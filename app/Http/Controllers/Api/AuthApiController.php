<?php

namespace App\Http\Controllers\Api;

use App\Helpers\general;
use App\Http\Requests\UserRequest;
use App\Jobs\SendEmailVerificationApi;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthApiController extends Controller
{
    use general;

    //

    /**
     * AuthApiController constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request()->only(['email', 'password']);
        if (!$token = $this->guard()->attempt($credentials)) {
            return response()->json(['error' => 'unauthorized from server'], 401);
        }
        return $this->respondWithToken($token);
    }

    /**
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRequest $request)
    {
        $request->merge(['password' => bcrypt($request->input('password'))]);
        $data = $request->all();
        $user = User::create($data);
        $this->generateUniqueToken($user);
        SendEmailVerificationApi::dispatch($user);
        return response()->json(['message' => 'account created successfully']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'Authorization' => 'Bearer ' . $token,
        ])->header('Access-Control-Expose-Headers', 'Authorization')
            ->header('Authorization', 'Bearer ' . $token);

    }

    public function verifyEmail(Request $request)
    {
        $token = $request->input('token');
        $user = User::where('verified_token', '=', $token)->first();
        $user->email_verified_at = now('UTC');
        $user->save();
        return response()->json(['message' => 'email verified successfully']);
    }

    /**
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard('api');
    }

}
