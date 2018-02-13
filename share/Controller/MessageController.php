<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/08
 * Time: 9:15
 */

namespace Controller;

use Di\RepositoryContainer;
use Di\ServiceContainer;
use Di\UtilContainer;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class MessageController
{
    use RepositoryContainer;
    use UtilContainer;
    use ServiceContainer;

    private $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * ユーザにメッセージがポストされた時に呼び出されるメソッド
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        /*csrfの判別*/
        if (false === $request->getAttribute('csrf_status')) {
            return $this->app->view->render($this->app->response, 'error');
        }
        $userInfo = $this->getUserRepository()->getUserData($request->getAttribute('name'));
        $path = $this->getImageUtil()->makeMessageImage($request->getParsedBody()['message']);
        $hash = $this->getMessageRepository()->createMessage($userInfo->id, $path,
            $request->getParsedBody()['message']);
        return $this->app->response->withRedirect("/post/$hash", 301);
    }

    /**
     * ユーザの質問を表示する時に呼び出されるメソッド
     * @param Request $request
     * @return Response
     */
    public function postMessage(Request $request): Response
    {
        $message = $this->getMessageRepository()->getMessage($request->getAttribute('hash'));
        $nameKey = $this->app->csrf->getTokenNameKey();
        $valueKey = $this->app->csrf->getTokenValueKey();
        $loginUserExist = $this->getUserSessionUtil()->loginUserExist($message->user_id);
        $array = [
            'message' => $message,
            'nameKey' => $nameKey,
            'valueKey' => $valueKey,
            'name' => $request->getAttribute($nameKey),
            'value' => $request->getAttribute($valueKey),
            'loginUserExist' => $loginUserExist
        ];
        return $this->app->view->render($this->app->response, 'message', $array);
    }

    /**
     * 送られてきた質問で返信をしていないリスト
     * @return Response
     */
    public function messageList(): Response
    {
        if ($this->getUserSessionUtil()->loginExist()) {
            $messageList = $this->getMessageRepository()->getMessageList($_SESSION['user_id'], 0);
            $array = [
                'messageList' => $messageList,
                'flog' => 0
            ];
            return $this->app->view->render($this->app->response, 'messageList', $array);
        } else {
            return $this->app->response->withRedirect('/', 301);
        }
    }

    /**
     * 送られてきた質問で返信をしていないリスト
     * @return Response
     */
    public function answerMessageList(): Response
    {
        if ($this->getUserSessionUtil()->loginExist()) {
            $messageList = $this->getMessageRepository()->getMessageList($_SESSION['user_id'], 1);
            $array = [
                'messageList' => $messageList,
                'flog' => 1
            ];
            return $this->app->view->render($this->app->response, 'messageList', $array);
        } else {
            return $this->app->response->withRedirect('/', 301);
        }
    }

    /**
     * 質問に対して解答した時に呼ばれるメソッド
     * @param Request $request
     * @return Response
     */
    public function answerMessagePost(Request $request): Response
    {
        $getVars = $request->getParsedBody();
        /*csrfの判別*/
        if (false === $request->getAttribute('csrf_status')) {
            return $this->app->view->render($this->app->response, 'error');
        }
        if ($this->getUserSessionUtil()->loginExist()) {
            $message = $this->getMessageRepository()->updateSendMessage($request->getAttribute('hash'), $getVars['message']);
            $twitMessage = $this->getTwitMessageUtil()->index($getVars, $message);
            $this->getTwitterService()->postTwit($twitMessage);
            return $this->app->response->withRedirect('/post/' . $message->hash, 301);
        } else {
            return $this->app->response->withRedirect('/', 301);
        }

    }

}