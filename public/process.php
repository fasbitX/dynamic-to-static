<?php

define('BASE_PATH', dirname(__FILE__));

require BASE_PATH . '/bootstrap.php';

$error = 1;
$msg = "Bad Request.";

if (!empty($_POST)) {

    $old_config = getConfig();
    $config = [
        'site_url' => getPostValue('site_url'),
        'site_port' => getPostValue('site_port'),
        'db' => [
            'driver' => getPostValue('db-driver'),
            'hostname' => getPostValue('db-hostname'),
            'database' => getPostValue('db-database'),
            'username' => getPostValue('db-username'),
            'password' => getPostValue('db-password') == '' ? $old_config->db->password : getPostValue('db-password'),
            'charset' => 'utf8',
        ],
        'cloudflare' => [
            'email' => getPostValue('cloudflare-email'),
            'api_key' => getPostValue('cloudflare-api_key'), // global api key
            'zone_id' => getPostValue('cloudflare-zone_id'),
            'records' => [
                [
                    'record_type' => getPostValue('record_type-0'),
                    'record_name' => getPostValue('record_name-0'),
                    'proxied' => in_array(getPostValue('proxied-0'), ['on', 1]) ? true : false,
                    'update_ipv6' => in_array(getPostValue('update_ipv6-0'), ['on', 1]) ? true : false,
                ],
                [
                    'record_type' => getPostValue('record_type-1'),
                    'record_name' => getPostValue('record_name-1'),
                    'proxied' => in_array(getPostValue('proxied-1'), ['on', 1]) ? true : false,
                    'update_ipv6' => in_array(getPostValue('update_ipv6-1'), ['on', 1]) ? true : false,
                ],
                [
                    'record_type' => getPostValue('record_type-2'),
                    'record_name' => getPostValue('record_name-2'),
                    'proxied' => in_array(getPostValue('proxied-2'), ['on', 1]) ? true : false,
                    'update_ipv6' => in_array(getPostValue('update_ipv6-2'), ['on', 1]) ? true : false,
                ],
                [
                    'record_type' => getPostValue('record_type-3'),
                    'record_name' => getPostValue('record_name-3'),
                    'proxied' => in_array(getPostValue('proxied-3'), ['on', 1]) ? true : false,
                    'update_ipv6' => in_array(getPostValue('update_ipv6-3'), ['on', 1]) ? true : false,
                ],
                [
                    'record_type' => getPostValue('record_type-4'),
                    'record_name' => getPostValue('record_name-4'),
                    'proxied' => in_array(getPostValue('proxied-4'), ['on', 1]) ? true : false,
                    'update_ipv6' => in_array(getPostValue('update_ipv6-4'), ['on', 1]) ? true : false,
                ],
            ],
        ],
        'notifications' => [
            'send_email' => in_array(getPostValue('notifications-send_email'), ['on', 1]) ? 1 : 0,
            'host' => getPostValue('notifications-host'),
            'from_email' => getPostValue('notifications-from_email'),
            'from_password' => getPostValue('notifications-from_password'),
            'from_name' => getPostValue('notifications-from_name'),
            'to_email' => getPostValue('notifications-to_email'),
            'to_name' => getPostValue('notifications-to_name'),
            'port' => getPostValue('notifications-port'),
        ]
    ];


    $fp = fopen(dirname(__FILE__) . '/config.json', 'w+');
    $response = fwrite($fp, json_encode($config));
    fclose($fp);

    if ($response === false) {
        $error = 1;
        $msg = 'Could not save config.';
    } else {
        $error = 0;
        $msg = 'Successfully saved.';
    }
    $old_config = null;
}

$config = getConfig();
$_SESSION['submission_error'] = $error;
$_SESSION['submission_message'] = $msg;
header('Location: ' . getUrlWithPort($config->site_url, $config->site_port));
