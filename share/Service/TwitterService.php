<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2017/12/19
 * Time: 11:27
 */

namespace Service;

use Abraham\TwitterOAuth\TwitterOAuth;
use Abraham\TwitterOAuth\TwitterOAuthException;
use Di\RepositoryContainer;
use Di\UtilContainer;

class TwitterService
{
    use UtilContainer;
    use RepositoryContainer;

    private $consumerKey;
    private $consumerSecret;
    private $callback;

    public function __construct()
    {
        $this->consumerKey = getenv('CONSUMER_KEY');
        $this->consumerSecret = getenv('CONSUMER_SECRET');
        $this->callback = getenv('CALLBACK');
    }

    /**
     * 依存性注入のためのメソッド
     * @param $consumerKey
     * @param $consumerSecret
     * @param $oauthToken
     * @param $oauthTokenSecret
     * @return TwitterOAuth
     */
    public function getTwitterOAuth($consumerKey, $consumerSecret, $oauthToken = null, $oauthTokenSecret = null)
    {
        return new TwitterOAuth($consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret);
    }

    /**
     * 認証用のURLを生成する
     * @return string
     * @throws TwitterOAuthException
     */
    public function createUrl(): string
    {
        try {
            $connection = $this->getTwitterOAuth($this->consumerKey, $this->consumerSecret);
            $request_token = $connection->oauth("oauth/request_token", array("oauth_callback" => $this->callback));
            $_SESSION['oauth_token'] = $request_token['oauth_token'];
            $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
            $url = $connection->url("oauth/authorize", array("oauth_token" => $request_token['oauth_token']));
            return $url;
        } catch (TwitterOAuthException $e) {
            $this->getLoggerUtil()->setTwitterLog();
            throw $e;
        }
    }

    /**
     * OAuthトークンからアクセストークンを取得する
     * @param string $oauthToken
     * @param string $oauthVerifier
     * @return array
     * @throws TwitterOAuthException
     * @throws \Exception
     */
    public function getAccessToken(string $oauthToken, string $oauthVerifier): array
    {
        try {
            if ($_SESSION['oauth_token'] == $oauthToken and $oauthVerifier) {
                $connection = $this->getTwitterOAuth($this->consumerKey, $this->consumerSecret,
                    $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
                $accessToken = $connection->oauth('oauth/access_token',
                    array('oauth_token' => $oauthToken, 'oauth_verifier' => $oauthVerifier));
                $_SESSION = array();
                return $accessToken;
            } else {
                $_SESSION = array();
                throw new TwitterOAuthException('ツイッターログインエラー');
            }
        } catch (\Exception $e) {
            $this->getLoggerUtil()->setTwitterLog();
            throw $e;
        }
    }

    /**
     * Twitterからユーザ情報を取得する
     * @param string $oauthToken
     * @param string $oauthTokenSecret
     * @return array|object
     */
    public function getUserInfo($oauthToken, $oauthTokenSecret)
    {
        $userConnection = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $oauthToken, $oauthTokenSecret);
        $twitterUserInfo = $userConnection->get('account/verify_credentials');
        return $twitterUserInfo;
    }

    public function postTwit(array $message)
    {
        $userInfo = $this->getUserRepository()->getUserInfo($_SESSION['user_id']);
        $connection = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $userInfo->access_token,
            $userInfo->access_token_secret);
        if ($message['type'] === 'image') {
            $media = $connection->upload('media/upload', ['media' => __DIR__ . '/../public/message/' . $message['path']]);
            $parameters = [
                "status" => $message,
                'media_ids' => implode(',', [$media->media_id_string])
            ];
            $connection->post("statuses/update", $parameters);
        } else {
            $connection->post("statuses/update", array("status" => $message));
        }
        if (!($connection->getLastHttpCode() == 200)) {
            $this->getLoggerUtil()->setTwitterLog();
            throw new TwitterOAuthException('ツイッターツイートエラー');
        }
    }
}