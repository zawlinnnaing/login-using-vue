<?php
/**
 * Created by PhpStorm.
 * User: zhiyu1205
 * Date: 2018-12-17
 * Time: 10:34
 */

namespace App\Helpers;


use App\User;

trait general
{
    /**
     * @param User $user
     */
    public function generateUniqueToken(User $user)
    {
        do {
            $token = str_random(16);
            $user->verified_token = $token;
            $user->save();
        } while (User::where('verified_token','=',$token)->get()->count() > 1   );

    }
}