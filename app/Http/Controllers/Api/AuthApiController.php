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


    /****************** REST api actions ***********************/


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (auth('api')->user() == null) {
            return response()->json(['data' => 'No user found'], 401);
        } else {
            return response()->json(new UserResource(auth('api')->user()));
        }
    }

    /**
     * @param UserUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(Lang::get('invalid.user_not_found'), 403);
        }

        if (!empty($request->input('image'))) {
            $user->update($request->except('image'));
            if (!empty($user->img_dir)) {
                $this->deleteImage($user->img_dir, $this->profileDir);
            }
            $base64Image = explode(',', $request->input('image'));
            $image = base64_decode($base64Image[1]);
            $imageName = $user->name;
            try {
                $path = $this->uploadImage($image, $this->profileDir, $imageName);
                if ($path) {
                    $user->update([
                        'img_dir' => $path
                    ]);
                    return response()->json(new UserResource(User::find($id)), 200);
                } else {
                    return response()->json('Upload image error', 500);
                }
            } catch (FileSizeTooLargeException $e) {
                return response()->json($e->getFileTooLargeMessage(), 403);
            }
        } else {
            $user->update($request->all());
            return response()->json(new UserResource(User::find($id)), 200);
        }

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
