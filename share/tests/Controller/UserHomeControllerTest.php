<?php

/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/12
 * Time: 5:55
 */

use Database\Repository\UserRepository;
use Util\UserSessionUtil;

class UserHomeControllerTest extends \PHPUnit\Framework\TestCase
{
    public function testIndex()
    {
        /**
         * @var UserSessionUtil | PHPUnit_Framework_MockObject_MockObject $userSessionUtilMock
         * @var UserSessionUtil | PHPUnit_Framework_MockObject_MockObject $userSessionUtilMock2
         * @var UserSessionUtil | PHPUnit_Framework_MockObject_MockObject $userSessionUtilMock3
         * @var UserRepository | PHPUnit_Framework_MockObject_MockObject $userRepositoryMock
         * @var Slim\Http\Request | PHPUnit_Framework_MockObject_MockObject $requestMock
         */
        $userSessionUtilMock = $this->createMock(UserSessionUtil::class);
        $userSessionUtilMock->method('loginExist')->willReturn(true);
        $userSessionUtilMock->method('loginUserExist')->willReturn(true);
        $userSessionUtilMock2 = $this->createMock(UserSessionUtil::class);
        $userSessionUtilMock2->method('loginExist')->willReturn(false);
        $userSessionUtilMock3 = $this->createMock(UserSessionUtil::class);
        $userSessionUtilMock3->method('loginExist')->willReturn(true);
        $userSessionUtilMock3->method('loginUserExist')->willReturn(false);
        $model = new UserForUserHomeController();
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->method('getUserData')->willReturn($model);
        $requestMock = $this->createMock(Slim\Http\Request::class);
        $requestMock->method('getAttribute')->willReturn('name');
        /**
         * もし、loginExist()がtrueで、loginUserExist()もtrueだった場合のテスト
         */
        $userHomeController = new UserHomeControllerForTest($userSessionUtilMock, $userRepositoryMock);
        $result = $userHomeController->index($requestMock);
        $result->getbody()->rewind();
        $this->assertSame('userHome 山田太郎 Syo_pr test.png name name token_name_key token_value_key 1',
            $result->getBody()->getContents());
        /**
         * もし、loginExist()がfalseだった場合のテスト
         */
        $userHomeController2 = new UserHomeControllerForTest($userSessionUtilMock2, $userRepositoryMock);
        $result = $userHomeController2->index($requestMock);
        $result->getbody()->rewind();
        $this->assertSame('userHome 山田太郎 Syo_pr test.png name name token_name_key token_value_key 0',
            $result->getBody()->getContents());
        /**
         * もし、loginExist()がtrueで、loginUserExist()がfalseだった場合のテスト
         */
        $userHomeController3 = new UserHomeControllerForTest($userSessionUtilMock3, $userRepositoryMock);
        $result = $userHomeController3->index($requestMock);
        $result->getbody()->rewind();
        $this->assertSame('userHome 山田太郎 Syo_pr test.png name name token_name_key token_value_key 0',
            $result->getBody()->getContents());
    }


}

class UserHomeControllerForTest extends \Controller\UserHomeController
{
    private $app;
    private $userSessionUtil;
    private $userRepository;

    public function __construct(UserSessionUtil $userSessionUtil, UserRepository $userRepository = null)
    {
        $this->app = new Slim\Container();
        $this->app->view = new BladeForUserHomeController();
        $this->app->csrf = new CsrfForUserHomeController();
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

class UserForUserHomeController extends Database\Models\User
{
    public $screen_name = 'Syo_pr';
    public $username = '山田太郎';
    public $user_image = 'test.png';
    public $id = 1;
}

class BladeForUserHomeController extends Slim\Views\Blade
{
    public function render(\Psr\Http\Message\ResponseInterface $response, $template, array $data = [])
    {
        $response->getBody()->write($template . ' ' . $data['userInfo']->username . ' ' .
            $data['userInfo']->screen_name . ' ' . $data['userInfo']->user_image . ' ' .
            $data['name'] . ' ' . $data['value'] . ' ' . $data['nameKey'] . ' ' . $data['valueKey'] . ' ' . (int)$data['flog']);
        return $response;
    }
}

class CsrfForUserHomeController extends \Slim\Csrf\Guard
{
    public function getTokenNameKey()
    {
        return 'token_name_key';
    }

    public function getTokenValueKey()
    {
        return 'token_value_key';
    }
}