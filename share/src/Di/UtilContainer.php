<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/03
 * Time: 9:34
 */

namespace Di;

use Util\ImageUtil;
use Util\LoggerUtil;
use Util\TextUtil;
use Util\TwitMessageUtil;
use Util\UserSessionUtil;

trait UtilContainer
{
    public function getUserSessionUtil()
    {
        return new UserSessionUtil();
    }

    public function getImageUtil()
    {
        return new ImageUtil();
    }

    public function getTextUtil()
    {
        return new TextUtil();
    }

    public function getLoggerUtil()
    {
        return new LoggerUtil();
    }

    public function getTwitMessageUtil()
    {
        return new TwitMessageUtil();
    }
}