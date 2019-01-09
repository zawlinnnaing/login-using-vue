<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed post_id
 * @property mixed user_id
 * @property mixed description
 * @property mixed created_at
 * @property mixed updated_at
 */
class CommentResource extends JsonResource
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
            'id'           => $this->id,
            'post_id'      => $this->post_id,
            'commented_by' => User::find($this->user_id)->name,
            'user_email'   => User::find($this->user_id)->email,
            'description'  => $this->description,
            'created_at'   => date('Y-M-d', strtotime($this->created_at)),
            'updated_at'   => $this->updated_at
        ];
    }
}
