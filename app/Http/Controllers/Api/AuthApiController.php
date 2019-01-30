<?php

namespace App\Http\Controllers\Api;

use App\Common\CommonFunctions;
use App\Common\Exceptions\FileSizeTooLargeException;
use App\Helpers\general;
use App\Http\Requests\Api\User\UserChangePasswordRequest;
use App\Http\Requests\Api\User\UserUpdateRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Jobs\SendEmailVerificationApi;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AuthApiController extends Controller
{
    use general, CommonFunctions;

    //

    private $profileDir = 'profile_images';

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



    /****************************************************************************/

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
        try {
            if (auth('api')->user() == null) {
                auth('api')->invalidate();
                return response()->json([
                    'error' => 'token refresh failed',
                    'user'  => auth('api')->user()
                ], 401);
            } else {
//                return response()->json(['message' => 'hi ']);
                return $this->respondWithToken(auth('api')->refresh());
            }
        } catch (TokenExpiredException $e) {
//            auth('api')->invalidate();
            return response()->json(['message' => 'your token has expired'], 401);
        }
    }

    /**
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        try {
            return response()->json([
                'Authorization' => 'Bearer ' . $token,
                'token'         => $token,
                'user'          => auth('api')->user()
            ])->header('Access-Control-Expose-Headers', 'Authorization')
                ->header('Authorization', 'Bearer ' . $token);
        } catch (TokenExpiredException $e) {
            auth('api')->invalidate();
            return response()->json(['message' => 'Your token has expired']);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyEmail(Request $request)
    {
        $token = $request->input('token');
        $user = User::where('verified_token', $token)->first();
        $user->email_verified_at = now('UTC');
        $user->is_active = true;
        $user->save();
        return response()->json(['message' => 'email verified successfully']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendVerificationEmail(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        SendEmailVerificationApi::dispatch($user);
        return response()->json(['message' => 'verification resend successfully']);
    }

    /**
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard('api');
    }

    public function changePassword(UserChangePasswordRequest $request)
    {
        $request->merge(['password' => bcrypt($request->input('password'))]);
        $user = User::find($request->input('id'));
        if (Hash::check($request->input('old_password'), $user->password)) {
            $user->update([
                'password' => $request->input('password')
            ]);
            return response()->json(['message' => 'password changed successfully'], 200);
        } else {
            return response()->json(['error' => 'Old password does not match'], 403);
        }
    }

}
