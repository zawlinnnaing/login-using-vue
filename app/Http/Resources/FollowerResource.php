<?php

namespace App\Http\Resources;

use App\Repositories\UserRepository\UserRepository;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed follower_id
 * @property mixed followed_id
 * @property mixed followedByUser
 */
class FollowerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'follower_name'    => (new UserRepository())->find($this->follower_id)->name,
            'followed_name'    => (new UserRepository())->find($this->followed_id)->name,
            'follower_id'      => $this->follower_id,
            'followed_id'      => $this->followed_id,
            'followed_by_user' => $this->followedByUser

        ];
    }
}
