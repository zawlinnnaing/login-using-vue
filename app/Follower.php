<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    //
    protected $table = 'followers';

    protected $fillable = ['follower_id', 'followed_id'];

    /**
     * Get user model from follower_id
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function followerUser()
    {
        return $this->belongsTo('App\User', 'follower_id');
    }

    /**
     * Get user model from follwed_id
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function followedUser()
    {
        return $this->belongsTo('App\User', 'followed_id');
    }
}
