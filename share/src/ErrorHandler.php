<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/09
 * Time: 19:53
 */

use Util\LoggerUtil;

return function ($c) {
    return function ($request, $response, $e) use ($c) {
        (new LoggerUtil())->setErrorMessage($e);
        return $c['view']->render($response, 'error');
    };
};