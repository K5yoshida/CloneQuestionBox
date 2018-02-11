<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/09
 * Time: 7:36
 */

namespace Controller;

use Di\RepositoryContainer;
use Di\UtilContainer;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class OptionController
{
    use RepositoryContainer;
    use UtilContainer;

    private $app;


    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * ユーザの情報設定
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        if ($this->getUserSessionUtil()->loginExist()) {
            $userInfo = $this->getUserRepository()->getUserInfo($_SESSION['user_id']);
            $nameKey = $this->app->csrf->getTokenNameKey();
            $valueKey = $this->app->csrf->getTokenValueKey();
            $array = [
                'userInfo' => $userInfo,
                'nameKey' => $nameKey,
                'valueKey' => $valueKey,
                'name' => $request->getAttribute($nameKey),
                'value' => $request->getAttribute($valueKey)
            ];
            return $this->app->view->render($this->app->response, 'option', $array);
        } else {
            return $this->app->response->withRedirect('/', 301);
        }
    }

    /**
     * ユーザ情報更新
     * @param Request $request
     * @return Response
     */
    public function post(Request $request): Response
    {
        /*csrfの判別*/
        if (false === $request->getAttribute('csrf_status')) {
            return $this->app->view->render($this->app->response, 'error');
        }
        if ($this->getUserSessionUtil()->loginExist()) {
            $this->getUserRepository()->updateUserData($_SESSION['user_id'], $this->app->request->getParsedBody());
            return $this->app->response->withRedirect('/user/option', 301);
        } else {
            return $this->app->response->withRedirect('/', 301);
        }
    }
}