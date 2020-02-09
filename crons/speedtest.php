<?php

require '../vendor/autoload.php';
// set base path
define('BASE_PATH', dirname(__FILE__));

// load app config
if (!is_file(BASE_PATH . '/../public/config.php')) die('No configuration file found. Please read documentation.');
$app_config = include_once BASE_PATH . '/../public/config.php';

$config_db = [
    'driver' => 'mysql',
    'host' => $app_config['db']['hostname'],
    'database' => $app_config['db']['database'],
    'username' => $app_config['db']['username'],
    'password' => $app_config['db']['password'],
    'charset' => 'utf8',
];

// connect to db
try {
    $db = new \App\Models\Db($config_db);
} catch (\Exception $e) {
    die('DB connection error.');
}
