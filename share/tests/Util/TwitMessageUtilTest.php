<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/16
 * Time: 21:04
 */

class TwitMessageUtilTest extends \PHPUnit\Framework\TestCase
{
    public function testIndex()
    {
        /**
         * 空の配列を渡した場合のテスト
         */
        $model = new MessageForTwitMessage();
        $twitMessageUtil = new \Util\TwitMessageUtil();
        $result = $twitMessageUtil->index([],$model);
        $this->assertSame(null, $result);

        /**
         * typeがimageだった場合のテスト
         */
        $array = ['type' => 'image', 'message' => 'text_sample'];
        $result = $twitMessageUtil->index($array,$model);
        $textArray = ['text' => "text_sample\n\n#クローン質問箱 ", 'path' => 'test.png', 'type' => 'image'];
        $this->assertSame($textArray, $result);

        /**
         * typeがlinkだった場合のテスト
         */
        $array = ['type' => 'link', 'message' => 'text_sample'];
        $result = $twitMessageUtil->index($array,$model);
        $textArray = ['text' => "text_sample\n\n#クローン質問箱 http://localhost:3080/post/test_hash", 'type' => 'link'];
        $this->assertSame($textArray, $result);
    }
}

class MessageForTwitMessage extends \Database\Models\Message
{
    public $image_path = 'test.png';
    public $hash = 'test_hash';
}