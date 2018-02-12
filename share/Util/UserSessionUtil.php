<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/03
 * Time: 9:32
 */

namespace Util;

class UserSessionUtil
{
    /**
     * セッション変数にuser_Idを保存
     * @param string $userId
     */
    public function setUserSession(string $userId)
    {
        $_SESSION['user_id'] = $userId;
    }

    /**
     * ログイン状態を確認
     * @return bool
     */
    public function loginExist(): bool
    {
        $userId = $_SESSION['user_id'] ?? null;
        if ($userId === null) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 開いてる質問箱がログインユーザの質問箱か判別する
     * @param string $userId
     * @return bool
     */
    public function loginUserExist(string $userId): bool
    {
        $session = $_SESSION['user_id'] ?? null;
        if ($userId === $session) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * セッション名をクッキーから取得する
     * @return null | true | false
     */
    public function getCookieSessionName()
    {
        return $_COOKIE[session_name()];
    }

    /**
     * セッション名のクッキーを有効期限の切れたものにする
     */
    public function setCookieSessionName()
    {
        setcookie(session_name(), '', time() - 42000, '/');
    }

    /**
     * セッションを破棄する
     */
    public function sessionDestroy()
    {
        session_destroy();
    }
}