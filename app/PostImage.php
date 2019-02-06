<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    //
    protected $table = 'post_img';

    protected $fillable = ['post_id', 'img_dir'];

    public function post()
    {
        return $this->belongsTo('App\Post','post_id');
    }
}
