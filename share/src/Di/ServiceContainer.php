<?php

/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/03
 * Time: 8:33
 */

namespace Di;

use Service\TwitterService;

trait ServiceContainer
{
    public function getTwitterService()
    {
        return new TwitterService();
    }
}