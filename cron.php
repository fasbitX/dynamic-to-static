<?php

require 'vendor/autoload.php';
// set base path
define('BASE_PATH', dirname(__FILE__));

// load app config
if (!is_file(BASE_PATH . '/public/config.php')) die('No configuration file found. Please read documentation.');
$app_config = include_once BASE_PATH . '/public/config.php';

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
$config = $db->getConfig();
$cron_times = [
    0 => '* * * * *',
    5 => '*/5 * * * *',
    15 => '*/15 * * * *',
    30 => '*/30 * * * *',
    45 => '*/45 * * * *',
    60 => '0 * * * *',
];

// ip checker
if(!empty($config->cron_ipchecker)) {
    if(array_key_exists($config->cron_frequency_ipchecker, $cron_times)) {
        $cron = Cron\CronExpression::factory($cron_times[$config->cron_frequency_ipchecker]);
        if($cron->isDue()) {
            chdir(BASE_PATH);
            exec('/usr/bin/php ./crons/ipchecker.php');
        }
    }
}
// speed test
if(!empty($config->cron_speedtest)) {
    if(array_key_exists($config->cron_frequency_speedtest, $cron_times)) {
        $cron = Cron\CronExpression::factory($cron_times[$config->cron_frequency_speedtest]);
        if($cron->isDue()) {
            chdir(BASE_PATH);
            exec('/usr/bin/php ./crons/speedtest.php');
        }
    }
}
