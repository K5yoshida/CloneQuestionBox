<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/16
 * Time: 20:06
 */

class ImageUtilTest extends \PHPUnit\Framework\TestCase
{
    public function testSaveTwitterImage()
    {
        $this->assertTrue(true);
    }

    public function testMakeMessageImage()
    {
        $this->assertTrue(true);
    }

    public function testGetMessageImageWidth()
    {
        /**
         * 改行が5行だった時のテスト
         */
        $imageUtil = new \Util\ImageUtil();
        $massageImageWidth = $imageUtil->getMessageImageWidth(5);
        $this->assertSame(200, $massageImageWidth);

        /**
         * 改行が6行だった時のテスト
         */
        $massageImageWidth = $imageUtil->getMessageImageWidth(6);
        $this->assertSame(225, $massageImageWidth);

        /**
         * 改行が10行だった時のテスト
         */
        $massageImageWidth = $imageUtil->getMessageImageWidth(10);
        $this->assertSame(325, $massageImageWidth);
    }
}