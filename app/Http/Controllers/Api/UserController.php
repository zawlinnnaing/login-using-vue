<?php

namespace App\Http\Controllers\Api;

use App\Common\Exceptions\FileSizeTooLargeException;
use App\Http\Requests\Api\User\UserUpdateRequest;
use App\Http\Resources\GuestUserResource;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;

class UserController extends Controller
{
    //
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

    /*************************  End RestAPi actions ******************************
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function getUser($id)
    {
        $user = User::find($id)->first();
        if (!empty($user)) {
            return response()->json(new GuestUserResource($user), 200);
        } else {
            return response()->json(['message' => 'User is not found'],400);
        }

    }

}
