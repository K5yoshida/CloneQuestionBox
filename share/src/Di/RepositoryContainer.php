<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/03
 * Time: 8:35
 */

namespace Di;

use Database\Repository\MessageRepository;
use Database\Repository\UserRepository;

trait RepositoryContainer
{
    public function getUserRepository()
    {
        return new UserRepository();
    }

    public function getMessageRepository()
    {
        return new MessageRepository();
    }
}