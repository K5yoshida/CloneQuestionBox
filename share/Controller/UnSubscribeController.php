<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/13
 * Time: 8:55
 */

namespace Controller;

use Di\RepositoryContainer;
use Di\UtilContainer;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class UnSubscribeController
{
    use UtilContainer;
    use RepositoryContainer;

    private $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * 退会用の処理
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        /*csrfの判別*/
        if (false === $request->getAttribute('csrf_status')) {
            return $this->app->view->render($this->app->response, 'error');
        }
        if ($this->getUserSessionUtil()->loginExist()) {
            $this->getUserRepository()->deleteUserData($_SESSION['user_id']);
            $cookieSessionName = $this->getUserSessionUtil()->getCookieSessionName();
            $_SESSION = array();
            if (isset($cookieSessionName)) {
                $this->getUserSessionUtil()->setCookieSessionName();
            }
            $this->getUserSessionUtil()->sessionDestroy();
            return $this->app->view->render($this->app->response, 'delete');
        } else {
            return $this->app->response->withRedirect('/', 301);
        }
    }
}