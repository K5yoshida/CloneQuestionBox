<?php

/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/16
 * Time: 21:25
 */

class UserSessionUtilTest extends \PHPUnit\Framework\TestCase
{
    public function testSetUserSession()
    {
        $this->assertTrue(true);
    }

    public function testLoginExist()
    {
        /**
         * セッションにuser_idがセットされていなかった場合のテスト
         */
        $userSessionUtil = new Util\UserSessionUtil;
        $result = $userSessionUtil->loginExist();
        $this->assertSame(false, $result);

        /**
         * セッションにuser_idがセットされていた場合のテスト
         */
        $_SESSION['user_id'] = '1';
        $result = $userSessionUtil->loginExist();
        $this->assertSame(true, $result);
    }

    public function testLoginUserExist()
    {
        /**
         * セッションが設定されていなかった場合のテスト
         */
        $userId = '1';
        $_SESSION = array();
        $userSessionUtil = new Util\UserSessionUtil;
        $result = $userSessionUtil->loginUserExist($userId);
        $this->assertSame(false, $result);

        /**
         * セッションのuser_idと$userIdが一致しなかった場合のテスト
         */
        $_SESSION['user_id'] = '2';
        $result = $userSessionUtil->loginUserExist($userId);
        $this->assertSame(false, $result);

        /**
         * セッションのuser_idと$userIdが一致した場合のテスト
         */
        $_SESSION['user_id'] = '1';
        $result = $userSessionUtil->loginUserExist($userId);
        $this->assertSame(true, $result);
    }

    public function testGetCookieSessionName()
    {
        $this->assertTrue(true);
    }

    public function testSetCookieSessionName()
    {
        $this->assertTrue(true);
    }

    public function testSessionDestroy()
    {
        $this->assertTrue(true);
    }
}