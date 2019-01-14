<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed title
 * @property mixed body
 * @property mixed created_at
 * @property mixed user_id
 */
class PostResource extends JsonResource
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
            'id'         => $this->id,
            'title'      => $this->title,
            'created_at' => date('Y-M-d', strtotime($this->created_at)),
            'body'       => $this->body,
            'author'     => User::find($this->user_id)->name,
            'user_id' => $this->user_id
        ];
    }
}
