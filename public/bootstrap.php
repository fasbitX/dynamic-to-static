<?php

session_start();
if (getenv('ENVIRONMENT') === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// set base path
define('BASE_PATH', dirname(__FILE__));

// load autoload config
require BASE_PATH . '/../vendor/autoload.php';

// load app config
if (!is_file(BASE_PATH . '/config.php')) die('No configuration file found. Please read documentation.');
$app_config = include_once BASE_PATH . '/config.php';

// load db config
$config_db = [
    'driver' => $app_config['db']['driver'],
    'hostname' => $app_config['db']['hostname'],
    'database' => $app_config['db']['database'],
    'username' => $app_config['db']['username'],
    'password' => $app_config['db']['password'],
    'charset' => $app_config['db']['charset'],
];
// connect to db
$db = new \App\Models\Db($config_db);
$config = $db->getConfig();
define('SITE_URL', \App\Util\Helper::getUrlWithPort($config->site_url, $config->site_port));
