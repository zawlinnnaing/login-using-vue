<?php

namespace App\Http\Controllers\Api;

use App\Common\CommonFunctions;
use App\Common\Exceptions\FileSizeTooLargeException;
use App\Http\Requests\Api\User\UserUpdateRequest;
use App\Http\Resources\FollowerResource;
use App\Http\Resources\GuestUserResource;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository\UserRepository;
use App\User;
use Egulias\EmailValidator\Exception\ExpectingATEXT;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;

class UserController extends Controller
{
    //
    /****************** REST api actions ***********************/

    protected $repository;

    use CommonFunctions;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }

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
            $image = $this->decodeImage($request->input('image'));
            $imageName = $user->name;
            $path = $this->uploadImage($image, $this->profileDir, $imageName);
            if ($path) {
                $user->update([
                    'img_dir' => $path
                ]);
                return response()->json(new UserResource(User::find($id)), 200);
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
        $user = User::find($id);
        if (!empty($user) && $user->is_active) {
            return response()->json(new GuestUserResource($user), 200);
        } else {
            return response()->json(['message' => 'User is not found'], 400);
        }

    }

    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFollowers($userId)
    {
        $user = $this->repository->getFollowers($userId);
        return response()->json(FollowerResource::collection($user), 200);

    }

    public function getFollowing($userId)
    {
        $followings = $this->repository->getFollowings($userId);
        return response()->json(FollowerResource::collection($followings), 200);
    }

    /**
     * @param $followerId
     * @param $followedId
     * @return \Illuminate\Http\JsonResponse
     */
    public function follow($followerId, $followedId)
    {
        $follow = $this->repository->follow($followerId, $followedId);
        if ($follow) {
            return response()->json($follow, 200);
        }
    }

    /**
     * @param $followerId
     * @param $followedId
     * @return \Illuminate\Http\JsonResponse
     */
    public function unfollow($followerId, $followedId)
    {
        $unfollow = $this->repository->unfollow($followedId, $followerId);
        if ($unfollow) {
            return response()->json(['msg' => Lang::get('message.unfollow_success')], 200);
        }
    }

}
