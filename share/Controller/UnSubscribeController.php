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
     * @return Response
     */
    public function index(): Response
    {
        if ($this->getUserSessionUtil()->loginExist()) {
            $this->getUserRepository()->deleteUserData($_SESSION['user_id']);
            return $this->app->view->render($this->app->response, 'delete');
        } else {
            return $this->app->response->withRedirect('/', 301);
        }
    }
}