<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2017/09/10
 * Time: 0:01
 */

use Controller\HomeController;
use Controller\LoginController;
use Controller\MessageController;
use Controller\OptionController;
use Controller\UnSubscribeController;
use Controller\UserHomeController;

$slimApp->add($container->get('csrf'));

$slimApp->get('/', HomeController::class . ':index');
$slimApp->get('/{name}', UserHomeController::class . ':index');
$slimApp->get('/post/{hash}', MessageController::class . ':postMessage');
$slimApp->get('/user/option', OptionController::class . ':index');
$slimApp->get('/user/message', MessageController::class .':messageList');
$slimApp->get('/user/message/answer', MessageController::class .':answerMessageList');

$slimApp->get('/auth/twitter', LoginController::class . ':index');
$slimApp->get('/auth/twitter/callback', LoginController::class . ':callback');
$slimApp->get('/auth/twitter/logout', LoginController::class . ':logout');

$slimApp->post('/{name}/message', MessageController::class . ':index');
$slimApp->post('/post/{hash}/answer', MessageController::class . ':answerMessagePost');
$slimApp->post('/user/option/post', OptionController::class . ':post');

$slimApp->post('/user/option/delete', UnSubscribeController::class . ':index');

/**実行部分*/
$slimApp->run();