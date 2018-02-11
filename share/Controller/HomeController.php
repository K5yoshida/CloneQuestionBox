<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2017/09/10
 * Time: 0:02
 */

namespace Controller;

use Di\RepositoryContainer;
use Di\UtilContainer;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class HomeController
{
    use UtilContainer;
    use RepositoryContainer;

    private $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * トップページを生成
     * @return Response
     */
    public function index(): Response
    {
        if ($this->getUserSessionUtil()->loginExist()) {
            $userInfo = $this->getUserRepository()->getUserInfo($_SESSION['user_id']);
            $userHomeUrl = getenv('APP_URL') . '/' . $userInfo->screen_name;
            return $this->app->response->withRedirect($userHomeUrl, 301);
        } else {
            return $this->app->view->render($this->app->response, 'home');
        }

    }
}