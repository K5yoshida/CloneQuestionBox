<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/10
 * Time: 11:20
 */

namespace Util;

use Database\Models\Message;

class TwitMessageUtil
{
    /**
     * ツイートする文字列を生成するメソッド
     * @param array $getVars
     * @param Message $message
     * @return null|array
     */
    public function index(array $getVars, Message $message)
    {
        $type = $getVars['type'] ?? null;
        if($type === 'image') {
            $text = $getVars['message'] . "\n\n#クローン質問箱 ";
            return ['text' => $text, 'path' => $message->image_path, 'type' => 'image'];
        } elseif ($type === 'link') {
            $text = $getVars['message'] . "\n\n#クローン質問箱 " . getenv('APP_URL') . "/post/$message->hash";
            return ['text' => $text, 'type' => 'link'];
        } else {
            return null;
        }
    }
}