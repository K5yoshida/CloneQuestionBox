<?php

use Database\Repository\UserRepository;
use Util\UserSessionUtil;

/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/13
 * Time: 17:53
 */

class UnSubscribeControllerTest extends \PHPUnit\Framework\TestCase
{
    public function testIndex()
    {
        /**
         * もし、csrfトークンが間違っていた場合のテスト
         * @var Slim\Http\Request | PHPUnit_Framework_MockObject_MockObject $requestMock
         */
        $requestMock = $this->createMock(Slim\Http\Request::class);
        $requestMock->method('getAttribute')->willReturn(false);
        $unSubscribeController = new UnSubscribeControllerForTest();
        $result = $unSubscribeController->index($requestMock);
        $result->getbody()->rewind();
        $this->assertSame('error', $result->getBody()->getContents());
        /**
         * もし、csrfトークンも正常で、ユーザがログインしていた場合のテスト
         *@var Slim\Http\Request | PHPUnit_Framework_MockObject_MockObject $requestMock2
         */
        $_SESSION['user_id'] = 1;
        $userSessionUtilMock = $this->createMock(UserSessionUtil::class);
        $userSessionUtilMock->method('loginExist')->willReturn(true);
        $userSessionUtilMock->method('getCookieSessionName')->willReturn('PHP_SESSION');
        $userSessionUtilMock->method('setCookieSessionName')->willReturn(0);
        $userSessionUtilMock->method('sessionDestroy')->willReturn(0);
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->method('deleteUserData')->willReturn(0);
        $requestMock2 = $this->createMock(Slim\Http\Request::class);
        $requestMock2->method('getAttribute')->willReturn(true);
        $unSubscribeController2 = new UnSubscribeControllerForTest($userSessionUtilMock, $userRepositoryMock);
        $result = $unSubscribeController2->index($requestMock2);
        $result->getbody()->rewind();
        $this->assertSame('delete', $result->getBody()->getContents());

        /**
         * もし、,csrfトークンが正常で、ユーザがログインしていなかった場合のテスト
         */
        $userSessionUtilMock2 = $this->createMock(UserSessionUtil::class);
        $userSessionUtilMock2->method('loginExist')->willReturn(false);
        $userSessionUtilMock2->method('getCookieSessionName')->willReturn('PHP_SESSION');
        $userSessionUtilMock2->method('setCookieSessionName')->willReturn(0);
        $userSessionUtilMock2->method('sessionDestroy')->willReturn(0);
        $unSubscribeController3 = new UnSubscribeControllerForTest($userSessionUtilMock2, $userRepositoryMock);
        $result = $unSubscribeController3->index($requestMock2);
        $result->getbody()->rewind();
        $this->assertSame('Moved Permanently', $result->getReasonPhrase());
        $this->assertSame('/', $result->getHeaders()['Location'][0]);
    }
}

class UnSubscribeControllerForTest extends \Controller\UnSubscribeController
{
    private $app;
    private $userSessionUtil;
    private $userRepository;

    public function __construct(UserSessionUtil $userSessionUtil = null, UserRepository $userRepository = null)
    {
        $this->app = new Slim\Container();
        $this->app->view = new BladeForUnSubscribeController();
        parent::__construct($this->app);
        $this->userSessionUtil = $userSessionUtil;
        $this->userRepository = $userRepository;
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

class BladeForUnSubscribeController extends Slim\Views\Blade
{
    public function render(\Psr\Http\Message\ResponseInterface $response, $template, array $data = [])
    {
        $response->getBody()->write($template);
        return $response;
    }
}