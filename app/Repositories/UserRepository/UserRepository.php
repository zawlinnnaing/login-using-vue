<?php
/**
 * Created by PhpStorm.
 * User: zhiyu1205
 * Date: 2019-02-11
 * Time: 13:04
 */

namespace App\Repositories\UserRepository;


use App\Repositories\BaseRepository;
use App\User;

class UserRepository extends BaseRepository
{

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(new User());
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getFollowers($userId)
    {
        $followers = $this->find($userId)->followers()->get();
        foreach ($followers as $follower) {
            $isFollowedByUser = $this->find($follower->follower_id)->followers()->where('follower_id', $userId)->get();
            if ($isFollowedByUser->count() > 0) {
                $follower['followedByUser'] = true;
            } else {
                $follower['followedByUser'] = false;
            }
        }
        return $followers;
    }

    public function getFollowings($userId)
    {
        $followings = $this->find($userId)->followed()->get();
        foreach ($followings as $following) {
            $following['followedByUser'] = true;
        }
        return $followings;

    }

    /**
     * @param $followerId
     * @param $userId
     * @return mixed
     */
    public function follow($userId, $followedId)
    {
        $user = $this->find($userId);
        return $user->followed()->create([
            'followed_id' => $followedId
        ]);
    }

    public function unfollow($followedId, $userId)
    {
        $user = $this->find($userId);

        return $user->followed()->where('followed_id', $followedId)->delete();

    }
}