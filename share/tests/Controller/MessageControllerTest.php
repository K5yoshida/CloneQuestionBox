<?php

use Database\Repository\MessageRepository;
use Database\Repository\UserRepository;
use Service\TwitterService;
use Util\ImageUtil;
use Util\TwitMessageUtil;
use Util\UserSessionUtil;

/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/16
 * Time: 13:12
 */
class MessageControllerTest extends \PHPUnit\Framework\TestCase
{
    public function testIndex()
    {
        /**
         * @var ImageUtil | PHPUnit_Framework_MockObject_MockObject $imageUtilMock
         * @var UserRepository | PHPUnit_Framework_MockObject_MockObject $userRepositoryMock
         * @var MessageRepository | PHPUnit_Framework_MockObject_MockObject $messageRepositoryMock
         * @var Slim\Http\Request | PHPUnit_Framework_MockObject_MockObject $requestMock
         */
        $requestMock = $this->createMock(Slim\Http\Request::class);
        $requestMock->method('getAttribute')->willReturn(true);
        $requestMock->method('getParsedBody')->willReturn(['message' => 'テストメッセージ']);
        $model = new UserForMessageController();
        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->method('getUserData')->willReturn($model);
        $imageUtilMock = $this->createMock(ImageUtil::class);
        $imageUtilMock->method('makeMessageImage')->willReturn('test.png');
        $messageRepositoryMock = $this->createMock(MessageRepository::class);
        $messageRepositoryMock->method('createMessage')->willReturn('test_hash');
        $optionController = new MessageControllerForTest(null, $userRepositoryMock, $imageUtilMock,
            $messageRepositoryMock);
        $result = $optionController->index($requestMock);
        $result->getbody()->rewind();
        $this->assertSame('Moved Permanently', $result->getReasonPhrase());
        $this->assertSame('/post/test_hash', $result->getHeaders()['Location'][0]);
    }

    public function testPostMessage()
    {
        /**
         * @var UserSessionUtil | PHPUnit_Framework_MockObject_MockObject $userSessionUtilMock
         * @var MessageRepository | PHPUnit_Framework_MockObject_MockObject $messageRepositoryMock
         * @var Slim\Http\Request | PHPUnit_Framework_MockObject_MockObject $requestMock
         */
        $requestMock = $this->createMock(Slim\Http\Request::class);
        $requestMock->method('getAttribute')->willReturn('name');
        $model2 = new MessageForMessageController();
        $messageRepositoryMock = $this->createMock(MessageRepository::class);
        $messageRepositoryMock->method('getMessage')->willReturn($model2);
        $userSessionUtilMock = $this->createMock(UserSessionUtil::class);
        $userSessionUtilMock->method('loginUserExist')->willReturn(true);
        $messageController = new MessageControllerForTest($userSessionUtilMock, null, null, $messageRepositoryMock);
        $result = $messageController->postMessage($requestMock);
        $result->getbody()->rewind();
        $this->assertSame('message 10 name name token_name_key token_value_key 1  ', $result->getBody()->getContents());
    }

    /**
     * answerMessageList()とmessageList()はflog以外同じ処理なので、省略
     */
    public function testMessageList()
    {
        /**
         * @var UserSessionUtil | PHPUnit_Framework_MockObject_MockObject $userSessionUtilMock
         * @var MessageRepository | PHPUnit_Framework_MockObject_MockObject $messageRepositoryMock
         */
        $_SESSION['user_id'] = '1';
        $userSessionUtilMock = $this->createMock(UserSessionUtil::class);
        $userSessionUtilMock->method('loginExist')->willReturn(true);
        $model3 = new MessageForMessageController();
        $model4 = new MessageForMessageController();
        $messageRepositoryMock = $this->createMock(MessageRepository::class);
        $messageRepositoryMock->method('getMessageList')->willReturn([$model3, $model4]);
        $messageController = new MessageControllerForTest($userSessionUtilMock, null, null, $messageRepositoryMock);
        $result = $messageController->messageList();
        $result->getbody()->rewind();
        $this->assertSame('messageList      0 test_text test_text', $result->getBody()->getContents());
    }

    public function testAnswerMessagePost()
    {
        /**
         * @var Slim\Http\Request | PHPUnit_Framework_MockObject_MockObject $requestMock
         * @var UserSessionUtil | PHPUnit_Framework_MockObject_MockObject $userSessionUtilMock
         * @var MessageRepository | PHPUnit_Framework_MockObject_MockObject $messageRepositoryMock
         * @var TwitMessageUtil | PHPUnit_Framework_MockObject_MockObject $twitMessageUtilMock
         * @var TwitterService | PHPUnit_Framework_MockObject_MockObject $twitterServiceMock
         */
        $requestMock = $this->createMock(Slim\Http\Request::class);
        $requestMock->method('getAttribute')->willReturn('name');
        $requestMock->method('getParsedBody')->willReturn(['message' => 'test_text']);
        $userSessionUtilMock = $this->createMock(UserSessionUtil::class);
        $userSessionUtilMock->method('loginExist')->willReturn(true);
        $model5 = new MessageForMessageController();
        $messageRepositoryMock = $this->createMock(MessageRepository::class);
        $messageRepositoryMock->method('updateSendMessage')->willReturn($model5);
        $twitMessageUtilMock = $this->createMock(TwitMessageUtil::class);
        $twitMessageUtilMock->method('index')->willReturn(['text' => 'test_message_text', 'type' => 'link']);
        $twitterServiceMock = $this->createMock(TwitterService::class);
        $twitterServiceMock->method('postTwit')->willReturn('');
        $messageController = new MessageControllerForTest($userSessionUtilMock, null, null, $messageRepositoryMock, $twitMessageUtilMock, $twitterServiceMock);
        $result = $messageController->answerMessagePost($requestMock);
        $result->getbody()->rewind();
        $this->assertSame('Moved Permanently', $result->getReasonPhrase());
        $this->assertSame('/post/test_hash', $result->getHeaders()['Location'][0]);
    }
}

class MessageControllerForTest extends \Controller\MessageController
{
    private $app;
    private $userSessionUtil;
    private $userRepository;
    private $messageRepository;
    private $imageUtil;
    private $twitMessageUtilMock;
    private $twitterServiceMock;

    public function __construct(
        UserSessionUtil $userSessionUtil = null,
        UserRepository $userRepository = null,
        ImageUtil $imageUtil = null,
        MessageRepository $messageRepository = null,
        TwitMessageUtil $twitMessageUtilMock = null,
        TwitterService $twitterServiceMock = null
    ) {
        $this->app = new Slim\Container();
        $this->app->view = new BladeForMessageController();
        $this->app->csrf = new CsrfForMessageController();
        parent::__construct($this->app);
        $this->userSessionUtil = $userSessionUtil;
        $this->userRepository = $userRepository;
        $this->messageRepository = $messageRepository;
        $this->imageUtil = $imageUtil;
        $this->twitMessageUtilMock = $twitMessageUtilMock;
        $this->twitterServiceMock = $twitterServiceMock;
    }

    public function getUserRepository()
    {
        return $this->userRepository;
    }

    public function getUserSessionUtil()
    {
        return $this->userSessionUtil;
    }

    public function getMessageRepository()
    {
        return $this->messageRepository;
    }

    public function getImageUtil()
    {
        return $this->imageUtil;
    }

    public function getTwitMessageUtil()
    {
        return $this->twitMessageUtilMock;
    }

    public function getTwitterService()
    {
        return $this->twitterServiceMock;
    }
}

class UserForMessageController extends Database\Models\User
{
    public $id = 1;
}

class MessageForMessageController extends \Database\Models\Message
{
    public $user_id = 10;
    public $message_text = 'test_text';
    public $hash = 'test_hash';
}

class BladeForMessageController extends Slim\Views\Blade
{
    public function render(\Psr\Http\Message\ResponseInterface $response, $template, array $data = [])
    {
        $response->getBody()->write($template . ' ');
        $response->getBody()->write($data['message']->user_id . ' ');
        $response->getBody()->write($data['name'] . ' ');
        $response->getBody()->write($data['value'] . ' ');
        $response->getBody()->write($data['nameKey'] . ' ');
        $response->getBody()->write($data['valueKey'] . ' ');
        $response->getBody()->write((int)$data['loginUserExist'] . ' ');
        $response->getBody()->write($data['messageList'][0]->message_text . ' ');
        $response->getBody()->write($data['messageList'][1]->message_text);
        return $response;
    }
}

class CsrfForMessageController extends \Slim\Csrf\Guard
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