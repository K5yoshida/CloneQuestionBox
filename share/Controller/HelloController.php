<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2017/09/10
 * Time: 0:02
 */

namespace Controller;

use Slim\Container;

class HelloController
{
    private $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function index($request, $response, $args)
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $response->getBody()->write(json_encode(['response' => 'hello']));
    }
}