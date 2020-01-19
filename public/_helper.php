<?php if (!defined('BASE_PATH')) die('Forbidden.');

session_start();

$config = getConfig();

define('SITE_URL', getUrlWithPort($config->site_url, $config->site_port));

function url($path = '', $return = false)
{
    $url = SITE_URL . ltrim($path, '/');
    if ($return) return $url;
    echo $url;
}

function getCheckTimes()
{
    return [
        0 => 'Disabled',
        1 => 'Every Minute',
        2 => 'Every 5 Minutes',
        3 => 'Every 10 Minutes',
        4 => 'Every 30 Minutes',
        5 => 'Every Hour',
        6 => 'Every 2 Hours',
    ];
}

function getIPTypes()
{
    return [
        0 => 'IPV4',
        1 => 'IPV6',
    ];
}

function getConfig()
{
    $filename = dirname(__FILE__) . '/config.json';
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    fclose($handle);
    $config = json_decode($contents);
    return $config;
}

function getPostValue($field_name)
{
    return isset($_POST[$field_name]) ? trim($_POST[$field_name]) : '';
}

function getUrlWithPort($site_url, $site_port)
{
    $url_parts = parse_url($site_url);
    $protocol = (isset($url_parts['scheme']) ? $url_parts['scheme'] : 'http') . '://';
    $port = !isset($site_port) || $site_port == 80 ? '' : ':' . $site_port;
    $host = (isset($url_parts['host']) ? trim($url_parts['host']) : '') . $port;
    $path = isset($url_parts['path']) ? $url_parts['path'] : '/';
    $site_url = $protocol . $host . $path;
    return $site_url;
}
