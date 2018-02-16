<?php

use Database\Repository\UserRepository;
use Service\TwitterService;
use Util\ImageUtil;
use Util\UserSessionUtil;

/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/12
 * Time: 2:02
 */
class LoginControllerTest extends \PHPUnit\Framework\TestCase
{
    public function testIndex()
    {
        /**
         * ツイッターのログイン画面にリダイレクトする部分
         * @var TwitterService | PHPUnit_Framework_MockObject_MockObject $twitterServiceMock
         */
        $twitterServiceMock = $this->createMock(TwitterService::class);
        $twitterServiceMock->method('createUrl')->willReturn('http://test.com');
        $loginController = new LoginControllerForTest($twitterServiceMock);
        $result = $loginController->index([]);
        $result->getbody()->rewind();
        $this->assertSame('Moved Permanently', $result->getReasonPhrase());
        $this->assertSame('http://test.com', $result->getHeaders()['Location'][0]);
    }

    public function testCallback()
    {
        /**
         * ツイッターからコールバックされた値が正しくDBに入るかチェックする
         * 実行後にはマイページに遷移するためにトップページにリダイレクトする
         * @var TwitterService | PHPUnit_Framework_MockObject_MockObject $twitterServiceMock
         * @var ImageUtil | PHPUnit_Framework_MockObject_MockObject $imageUtilMock
         * @var UserSessionUtil | PHPUnit_Framework_MockObject_MockObject $userSessionUtilMock
         */
        $twitterServiceMock = $this->createMock(TwitterService::class);
        $twitterServiceMock->method('getAccessToken')->willReturn(['oauth_token'=> 'BzHSG','oauth_token_secret' => 'DBv2fya']);
        $twitterServiceMock->method('getUserInfo')->willReturn(new TwitterUserInfo);
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->method('createUserData')->willReturn('2');
        $userSessionUtilMock = $this->createMock(UserSessionUtil::class);
        $userSessionUtilMock->method('setUserSession');
        $requestMock = $this->createMock(Slim\Http\Request::class);
        $requestMock->method('getQueryParams')->willReturn(['oauth_token' => 'text', 'oauth_verifier' => 'test2']);
        $loginController = new LoginControllerForTest($twitterServiceMock, $userRepositoryMock, $userSessionUtilMock);
        $result = $loginController->callback($requestMock);
        $result->getbody()->rewind();
        $this->assertSame('Moved Permanently', $result->getReasonPhrase());
        $this->assertSame('/', $result->getHeaders()['Location'][0]);
    }

    public function testLogout()
    {
        /**
         * ログアウトの時にクッキーの削除やセッションの破棄をする
         * 最後にトップページにリダイレクトされる
         */
        $userSessionUtilMock = $this->createMock(UserSessionUtil::class);
        $userSessionUtilMock->method('getCookieSessionName')->willReturn(null);
        $userSessionUtilMock->method('setCookieSessionName');
        $userSessionUtilMock->method('sessionDestroy');
        $loginController = new LoginControllerForTest(null, null, $userSessionUtilMock);
        $result = $loginController->logout([]);
        $result->getbody()->rewind();
        $this->assertSame('Moved Permanently', $result->getReasonPhrase());
        $this->assertSame('/', $result->getHeaders()['Location'][0]);
    }
}

class LoginControllerForTest extends \Controller\LoginController
{
    private $twitterService;
    private $userRepository;
    private $userSessionUtil;
    private $app;

    /**
     * LoginControllerForTest constructor.
     * @param TwitterService $twitterService
     * @param UserRepository|null $userRepository
     * @param UserSessionUtil|null $userSessionUtil
     */
    public function __construct(TwitterService $twitterService = null, UserRepository $userRepository = null, UserSessionUtil $userSessionUtil = null) {
        $this->app = new \Slim\Container();
        parent::__construct($this->app);
        $this->twitterService = $twitterService;
        $this->userRepository = $userRepository;
        $this->userSessionUtil = $userSessionUtil;
    }

    public function getTwitterService()
    {
        return $this->twitterService;
    }

    public function getUserRepository()
    {
        return $this->userRepository;
    }

    public function getUserSessionUtil()
    {
        return $this->userSessionUtil;
    }
}

class TwitterUserInfo
{
    public $profile_image_url_https;

    public function __construct()
    {
        $this->profile_image_url_https = 'http://test4.com';
    }
}