<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/03
 * Time: 6:15
 */

/**
 * @param string $path
 * @return string
 */
function assets(string $path): string
{
    $rootPath = getenv('APP_URL');
    return $rootPath . '/' . $path;
}

function convertToFuzzyTime(string $messageTime): string
{
    $time = (new DateTime())->format('Y-m-d H:i:s');
    $diffSec = strtotime($time) - strtotime($messageTime);

    if ($diffSec < 3600) {
        $time = $diffSec / 60;
        $unit = "分前";
    } elseif ($diffSec < 86400) {
        $time = $diffSec / 3600;
        $unit = "時間前";
    } else {
        $time = $diffSec / 86400;
        $unit = "日前";
    }
    return (string)((int)$time . $unit);
}

function timeExist(string $messageTime): bool
{
    $time = (new DateTime())->format('Y-m-d H:i:s');
    $diffSec = strtotime($time) - strtotime($messageTime);
    if ($diffSec > 86400) {
        return false;
    }
    return true;
}