<?php
/**
 * Created by PhpStorm.
 * User: syo
 * Date: 2018/02/11
 * Time: 12:42
 */

use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Phinx\Config\Config as PhinxConfig;

require_once( __DIR__ . '/../vendor/autoload.php');

$dotenv = new Dotenv(__DIR__ . '/../');
$dotenv->load();
$log = new Logger('cool-php-libraries');
$log->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::DEBUG));

$config = PhinxConfig::fromYaml(__DIR__ . '/../phinx.yml');
$sqlData = $config->getEnvironments();

ORM::configure("mysql:dbname={$sqlData['testing']['name']};host={$sqlData['testing']['host']};charset=utf8");
ORM::configure('username', $sqlData['testing']['user']);
ORM::configure('password', $sqlData['testing']['pass']);