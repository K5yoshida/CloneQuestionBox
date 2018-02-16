<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/16
 * Time: 20:32
 */

class TextUtilTest extends \PHPUnit\Framework\TestCase
{
    public function testCheckMessageText()
    {
        /**
         * 21文字目の後に改行が追加されているかテスト
         */
        $text = "これはテストで文章を書いています。正常にメソッドが動作していれば、21行目で改行されるはず！";
        $resultText = "これはテストで文章を書いています。正常にメ\nソッドが動作していれば、21行目で改行される\nはず！";
        $textUtil = new \Util\TextUtil();
        $checkMessageText = $textUtil->checkMessageText($text);
        $this->assertSame($resultText, $checkMessageText);

        /**
         * 途中に改行を入れた場合のテスト
         */
        $text = "これはテストで文章を書いています。\n正常にメソッドが動作していれば、21行目で改行されるはず！";
        $resultText = "これはテストで文章を書いています。\n正常にメソッドが動作していれば、21行目で\n改行されるはず！";
        $checkMessageText = $textUtil->checkMessageText($text);
        $this->assertSame($resultText, $checkMessageText);

        /**
         * もし、21文字目に改行が来ていた場合のテスト
         */
        $text = "これはテストで文章を書いています。正常にメ\nソッドが動作していれば、21行目で改行され\nるはず！";
        $resultText = "これはテストで文章を書いています。正常にメ\nソッドが動作していれば、21行目で改行され\nるはず！";
        $checkMessageText = $textUtil->checkMessageText($text);
        $this->assertSame($resultText, $checkMessageText);
    }
}