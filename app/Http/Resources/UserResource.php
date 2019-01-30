<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed name
 * @property mixed email
 * @property mixed email_verified_at
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed verified_token
 * @property mixed deleted_at
 * @property mixed is_active
 * @property mixed img_dir
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email'=> $this->email,
            'is_verified' => ($this->email_verified_at != null),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'verified_token'=> $this->verified_token,
            'active' => ($this->is_active == 1),
            'img_dir' => $this->img_dir,
            'posts_count' => $this->posts()->count(),
            'followers_count'=> $this->followers()->count(),
            'followed_count'=> $this->followed()->count()
        ];
    }
}
