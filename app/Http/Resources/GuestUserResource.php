<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed name
 * @property mixed img_dir
 */
class GuestUserResource extends JsonResource
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
            'name'            => $this->name,
            'img_dir'         => $this->img_dir,
            'posts_count'     => $this->posts()->count(),
            'followers_count' => $this->followers()->count(),
            'followed_count'  => $this->followed()->count()
        ];
    }
}
