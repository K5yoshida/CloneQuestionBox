<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2017/09/09
 * Time: 23:59
 */

use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Phinx\Config\Config as PhinxConfig;
use Slim\Views\Blade;
session_start();
date_default_timezone_set('Asia/Tokyo');

$dotenv = new Dotenv(__DIR__ . '/../');
$dotenv->load();
$log = new Logger('cool-php-libraries');
$log->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::DEBUG));

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
        // Renderer settings
        'renderer'            => [
            'blade_template_path' => __DIR__ . '/../views', // String or array of multiple paths
            'blade_cache_path'    => __DIR__ . '/cache', // Mandatory by default, though could probably turn caching off for development
        ],
    ],
];

$config = PhinxConfig::fromYaml(__DIR__ . '/../phinx.yml');
$sqlData = $config->getEnvironments();

ORM::configure("mysql:dbname={$sqlData['production']['name']};host={$sqlData['production']['host']};charset=utf8");
ORM::configure('username', $sqlData['production']['user']);
ORM::configure('password', $sqlData['production']['pass']);

$c = new \Slim\Container($configuration);
$slimApp = new \Slim\App($c);

$container = $slimApp->getContainer();

$container['csrf'] = function ($c) {
    $guard = new \Slim\Csrf\Guard();
    $guard->setFailureCallable(function ($request, $response, $next) {
        $request = $request->withAttribute("csrf_status", false);
        return $next($request, $response);
    });
    return $guard;
};

// Register Blade View helper
$container['view'] = function ($container) {
    return new Blade(
        $container['settings']['renderer']['blade_template_path'],
        $container['settings']['renderer']['blade_cache_path']
    );
};

$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['view']->render($response, 'error');
    };
};

//$container['errorHandler'] = require_once(__DIR__ . '/ErrorHandler.php');