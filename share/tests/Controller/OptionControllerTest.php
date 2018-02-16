<?php

use Database\Repository\UserRepository;
use Util\UserSessionUtil;

/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/16
 * Time: 11:35
 */
class OptionControllerTest extends \PHPUnit\Framework\TestCase
{
    public function testIndex()
    {
        /**
         * ログインしていた場合のテスト
         * @var UserSessionUtil | PHPUnit_Framework_MockObject_MockObject $userSessionUtilMock
         * @var UserRepository | PHPUnit_Framework_MockObject_MockObject $userRepositoryMock
         * @var Slim\Http\Request | PHPUnit_Framework_MockObject_MockObject $requestMock
         */
        $_SESSION['user_id'] = '1';
        $userSessionUtilMock = $this->createMock(UserSessionUtil::class);
        $userSessionUtilMock->method('loginExist')->willReturn(true);
        $model = new UserForOptionController();
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->method('getUserInfo')->with('1')->willReturn($model);
        $requestMock = $this->createMock(Slim\Http\Request::class);
        $requestMock->method('getAttribute')->willReturn('name');
        $optionController = new OptionControllerForTest($userSessionUtilMock, $userRepositoryMock);
        $result = $optionController->index($requestMock);
        $result->getbody()->rewind();
        $this->assertSame('option Syo_pr name name token_name_key token_value_key', $result->getBody()->getContents());

        /**
         * ログインしていなかった場合のテスト
         * @var UserSessionUtil | PHPUnit_Framework_MockObject_MockObject $userSessionUtilMock2
         */
        $userSessionUtilMock2 = $this->createMock(UserSessionUtil::class);
        $userSessionUtilMock2->method('loginExist')->willReturn(false);
        $requestMock = $this->createMock(Slim\Http\Request::class);
        $requestMock->method('getAttribute')->willReturn('name');
        $optionController = new OptionControllerForTest($userSessionUtilMock2);
        $result = $optionController->index($requestMock);
        $result->getbody()->rewind();
        $this->assertSame('Moved Permanently', $result->getReasonPhrase());
        $this->assertSame('/', $result->getHeaders()['Location'][0]);
    }

    public function testPost()
    {
        /**
         * ログイン済みで、csrfトークンが正しかった場合
         * @var UserSessionUtil | PHPUnit_Framework_MockObject_MockObject $userSessionUtilMock
         * @var UserRepository | PHPUnit_Framework_MockObject_MockObject $userRepositoryMock
         * @var Slim\Http\Request | PHPUnit_Framework_MockObject_MockObject $requestMock
         */
        $_SESSION['user_id'] = '1';
        $userSessionUtilMock = $this->createMock(UserSessionUtil::class);
        $userSessionUtilMock->method('loginExist')->willReturn(true);
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->method('updateUserData')->willReturn('');
        $requestMock = $this->createMock(Slim\Http\Request::class);
        $requestMock->method('getParsedBody')->willReturn(['user_name' => 'text', 'email' => 'test@example.com', 'notification' => 'on']);
        $requestMock->method('getAttribute')->willReturn(true);
        $optionController = new OptionControllerForTest($userSessionUtilMock, $userRepositoryMock);
        $result = $optionController->post($requestMock);
        $result->getbody()->rewind();
        $this->assertSame('Moved Permanently', $result->getReasonPhrase());
        $this->assertSame('/user/option', $result->getHeaders()['Location'][0]);

        /**
         * @var UserSessionUtil | PHPUnit_Framework_MockObject_MockObject $userSessionUtilMock2
         * @var Slim\Http\Request | PHPUnit_Framework_MockObject_MockObject $requestMock2
         */
        $userSessionUtilMock2 = $this->createMock(UserSessionUtil::class);
        $userSessionUtilMock2->method('loginExist')->willReturn(false);
        $requestMock2 = $this->createMock(Slim\Http\Request::class);
        $optionController = new OptionControllerForTest($userSessionUtilMock2);
        $result = $optionController->post($requestMock2);
        $result->getbody()->rewind();
        $this->assertSame('Moved Permanently', $result->getReasonPhrase());
        $this->assertSame('/', $result->getHeaders()['Location'][0]);
    }
}

class OptionControllerForTest extends \Controller\OptionController
{
    private $app;
    private $userSessionUtil;
    private $userRepository;

    public function __construct(UserSessionUtil $userSessionUtil, UserRepository $userRepository = null)
    {
        $this->app = new Slim\Container();
        $this->app->view = new BladeForOptionController();
        $this->app->csrf = new CsrfForOptionController();
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

class BladeForOptionController extends Slim\Views\Blade
{
    public function render(\Psr\Http\Message\ResponseInterface $response, $template, array $data = [])
    {
        $response->getBody()->write($template . ' ' . $data['userInfo']->screen_name . ' ' .
            $data['name'] . ' ' . $data['value'] . ' ' . $data['nameKey'] . ' ' . $data['valueKey']);
        return $response;
    }
}

class UserForOptionController extends Database\Models\User
{
    public $screen_name = 'Syo_pr';
}

class CsrfForOptionController extends \Slim\Csrf\Guard
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