<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2017/09/10
 * Time: 0:01
 */

use Controller\HelloController;

$slimApp->get('/', HelloController::class . ':index');

/**実行部分*/
$slimApp->run();