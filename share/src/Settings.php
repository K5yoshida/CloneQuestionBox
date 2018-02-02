<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2017/09/09
 * Time: 23:59
 */

//use Dotenv\Dotenv;
use Phinx\Config\Config as PhinxConfig;
use Slim\Views\Blade;

//$dotenv = new Dotenv(__DIR__ . '/../');
//$dotenv->load();
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

ORM::configure("mysql:dbname=dev;host={$sqlData['production']['host']};charset=utf8");
ORM::configure('username', $sqlData['production']['user']);
ORM::configure('password', $sqlData['production']['pass']);

$c = new \Slim\Container($configuration);
$slimApp = new \Slim\App($c);

$container = $slimApp->getContainer();
// Register Blade View helper
$container['view'] = function ($container) {
    return new Blade(
        $container['settings']['renderer']['blade_template_path'],
        $container['settings']['renderer']['blade_cache_path']
    );
};