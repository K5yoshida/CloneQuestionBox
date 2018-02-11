<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2017/12/19
 * Time: 11:26
 */

namespace Controller;

use Di\RepositoryContainer;
use Di\ServiceContainer;
use Di\UtilContainer;
use Slim\Container;
use Slim\Http\Response;

class LoginController
{
    use ServiceContainer;
    use RepositoryContainer;
    use UtilContainer;

    private $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * ログイン用のtwitter認証画面にリダイレクト
     * @return Response
     */
    public function index(): Response
    {
            $authUrl = $this->getTwitterService()->createUrl();
            return $this->app->response->withRedirect($authUrl, 301);
    }

    /**
     * twitterで認証された後にコールバックされるメソッド
     * @return Response
     */
    public function callback(): Response
    {
        $getVars = $this->app->request->getQueryParams();
        $accessToken = $this->getTwitterService()->getAccessToken($getVars['oauth_token'], $getVars['oauth_verifier']);
        $twitterUserInfo = $this->getTwitterService()->getUserInfo($accessToken['oauth_token'],
            $accessToken['oauth_token_secret']);
        //Todo このままでは、毎回ログインするたびに画像が保存されてしまう。
        $image = $this->getImageUtil()->saveTwitterImage($twitterUserInfo->profile_image_url_https);
        $userId = $this->getUserRepository()->createUserData($accessToken, $twitterUserInfo, $image);
        $this->getUserSessionUtil()->setUserSession($userId);
        return $this->app->response->withRedirect('/', 301);
    }

    public function logout(): Response
    {
        $_SESSION = array();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }
        session_destroy();
        return $this->app->response->withRedirect('/', 301);
    }
}