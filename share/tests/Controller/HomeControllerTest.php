<?php

/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/11
 * Time: 21:38
 */

use Database\Repository\UserRepository;
use Slim\Container;
use Util\UserSessionUtil;

class HomeControllerTest extends \PHPUnit\Framework\TestCase
{
    public function testIndex()
    {
        /**
         * ログインしていた時に正常にリダイレクトされているか確認
         * @var UserSessionUtil | PHPUnit_Framework_MockObject_MockObject $userSessionUtilMock
         * @var UserRepository | PHPUnit_Framework_MockObject_MockObject $userRepositoryMock
         */
        $_SESSION['user_id'] = '1';
        $userSessionUtilMock = $this->createMock(UserSessionUtil::class);
        $userSessionUtilMock->method('loginExist')->willReturn(true);
        $model = new UserForHomeController();
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->method('getUserInfo')->with('1')->willReturn($model);

        $userController = new HomeControllerForTest($userSessionUtilMock, $userRepositoryMock);
        $result = $userController->index([]);
        $result->getbody()->rewind();
        $this->assertSame('Moved Permanently', $result->getReasonPhrase());
        $this->assertSame('http://localhost:3080/Syo_pr', $result->getHeaders()['Location'][0]);

        /**
         * ログインしていなかった時にhomeテンプレートが呼ばれるか確認
         * @var UserSessionUtil | PHPUnit_Framework_MockObject_MockObject $userSessionUtilMock2
         */
        $userSessionUtilMock2 = $this->createMock(UserSessionUtil::class);
        $userSessionUtilMock2->method('loginExist')->willReturn(false);
        $userController = new HomeControllerForTest($userSessionUtilMock2, $userRepositoryMock);
        $result = $userController->index([]);
        $result->getbody()->rewind();
        $this->assertSame('home', $result->getbody()->getContents());
    }
}


class HomeControllerForTest extends \Controller\HomeController
{
    private $userSessionUtil;
    private $userRepository;
    private $app;

    public function __construct(UserSessionUtil $userSessionUtil, UserRepository $userRepository)
    {
        $this->app = new Container();
        $this->app->view = new BladeForHomeController();
        parent::__construct($this->app);
        $this->userSessionUtil = $userSessionUtil;
        $this->userRepository = $userRepository;

    }

    public function getUserSessionUtil()
    {
        return $this->userSessionUtil;
    }

    public function getUserRepository()
    {
        return $this->userRepository;
    }
}

class UserForHomeController extends Database\Models\User
{
    public $screen_name = 'Syo_pr';
}

class BladeForHomeController extends Slim\Views\Blade
{
    public function render(\Psr\Http\Message\ResponseInterface $response, $template, array $data = [])
    {
        $response->getBody()->write($template);
        return $response;
    }
}