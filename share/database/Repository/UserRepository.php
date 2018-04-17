<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/03
 * Time: 8:46
 */

namespace Database\Repository;

use Database\Models\User;
use DateTime;
use Di\UtilContainer;
use Exception\DatabaseFalseException;
use PDOException;

class UserRepository
{
    use UtilContainer;

    /**
     * ユーザの情報をデータベースに登録
     * @param array $accessToken
     * @param \stdClass $userInfo
     */
    public function createUserData(array $accessToken, $userInfo)
    {
        date_default_timezone_set('Asia/Tokyo');
        $time = new DateTime();
        try {
            $userData = User::where('twitter_id', $userInfo->id)->where('delete_flag', 0)->findOne();
            if (!$userData) {
                $image = $this->getImageUtil()->saveTwitterImage($userInfo->profile_image_url_https);
                User::create()
                    ->set('twitter_id', $userInfo->id)
                    ->set('username', $userInfo->name)
                    ->set('screen_name', $userInfo->screen_name)
                    ->set('user_image', $image)
                    ->set('access_token', $accessToken['oauth_token'])
                    ->set('access_token_secret', $accessToken['oauth_token_secret'])
                    ->set('notification_flag', 0)
                    ->set('delete_flag', 0)
                    ->set('created', $time->format('Y-m-d H:i:s'))
                    ->set('updated', $time->format('Y-m-d H:i:s'))
                    ->save();
                return User::where('twitter_id', $userInfo->id)->where('delete_flag', 0)->findOne()->id();
            } else {
                $userData
                    ->set('username', $userInfo->name)
                    ->set('screen_name', $userInfo->screen_name)
                    ->set('access_token', $accessToken['oauth_token'])
                    ->set('access_token_secret', $accessToken['oauth_token_secret'])
                    ->set('updated', $time->format('Y-m-d H:i:s'))
                    ->save();
                return $userData->id();
            }
        } catch (PDOException $e) {
            $this->getLoggerUtil()->setDatabaseLog();
            throw $e;
        }
    }

    /**
     * User_IdからUser_Infoを取得する
     * @param string $userId
     * @return User
     * @throws DatabaseFalseException
     */
    public function getUserInfo(string $userId): User
    {
        try {
            $userInfo = User::findOne($userId);
            if (!$userInfo) {
                $this->getLoggerUtil()->setDatabaseLog();
                throw new DatabaseFalseException('ユーザが存在しませんでした');
            }
            return $userInfo;
        } catch (PDOException $e) {
            $this->getLoggerUtil()->setDatabaseLog();
            throw $e;
        }
    }

    /**
     * screen_Nameでユーザ情報
     * @param string $screenName
     * @return User
     * @throws DatabaseFalseException
     */
    public function getUserData(string $screenName): User
    {
        try {
            $userInfo = User::where('screen_name', $screenName)->where('delete_flag', 0)->findOne();
            if (!$userInfo) {
                $this->getLoggerUtil()->setDatabaseLog();
                throw new DatabaseFalseException('ユーザが存在しませんでした');
            }
            return $userInfo;
        } catch (PDOException $e) {
            $this->getLoggerUtil()->setDatabaseLog();
            throw $e;
        }
    }

    /**
     * ユーザの情報を更新
     * @param string $userId
     * @param array $array
     * @todo メールアドレスが存在するか、確認できるようにする
     */
    public function updateUserData(string $userId, array $array)
    {
        $username = $array['user_name'] ?? null;
        $email = $array['email'] ?? null;
        $notification = $array['notification'] ?? null;
        try {
            $userInfo = User::findOne($userId);
            if ($username !== '' && $username !== null) {
                $userInfo->username = $username;
            }
            if ($email !== '' && $email !== null) {
                $userInfo->email = $email;
            }
            if ($notification === 'on') {
                $userInfo->notification_flag = 1;
            }
            $userInfo->save();
        } catch (PDOException $e) {
            $this->getLoggerUtil()->setDatabaseLog();
            throw $e;
        }
    }

    /**
     * ユーザのデリートフラグを1にする
     * @param string $userId
     * @throws DatabaseFalseException
     */
    public function deleteUserData(string $userId)
    {
        try {
            $userInfo = User::where('id', $userId)->where('delete_flag', 0)->findOne();
            if (!$userInfo) {
                $this->getLoggerUtil()->setDatabaseLog();
                throw new DatabaseFalseException('ユーザが存在しませんでした');
            } else {
                $userInfo->delete_flag = 1;
                $userInfo->save();
            }
        } catch (PDOException $e) {
            $this->getLoggerUtil()->setDatabaseLog();
            throw $e;
        }
    }
}