<?php
/**
 * Created by PhpStorm.
 * User: zhiyu1205
 * Date: 2019-01-08
 * Time: 11:32
 */

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;

class ActivateUser
{

    public function __construct()
    {

    }

    /**
     * @param Verified $event
     */
    public function handle(Verified $event)
    {
        $event->user->is_active = 1;
        $event->user->save();
    }
}