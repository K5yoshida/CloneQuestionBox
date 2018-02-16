<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/08
 * Time: 11:08
 */

namespace Util;

class TextUtil
{
    /**
     * 送られてきたメッセージが21文字以上改行されていなかった場合は
     * 改行コードを追加する
     * @param string $message
     * @return string
     */
    public function checkMessageText(string $message): string
    {
        $count = 0;
        $newMessage = '';
        foreach (preg_split("//u", $message, -1, PREG_SPLIT_NO_EMPTY) as $value) {
            if (20 < $count) {
                $newMessage .= PHP_EOL;
                if ($value !== "\n") {
                    $newMessage .= $value;
                }
                $count = 0;
            } elseif ($value === PHP_EOL) {
                $newMessage .= $value;
                $count = 0;
            } else {
                $newMessage .= $value;
                $count++;
            }
        }
        return $newMessage;
    }
}