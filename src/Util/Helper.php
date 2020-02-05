<?php

namespace App\Util;

class Helper
{
    private function __construct()
    {
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    public static function url($path = '', $return = false)
    {
        $url = SITE_URL . ltrim($path, '/');
        if ($return) return $url;
        echo $url;
    }

    public static function getCheckTimes()
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

    public static function getIPTypes()
    {
        return [
            0 => 'IPV4',
            1 => 'IPV6',
        ];
    }

    public static function getPostValue($field_name)
    {
        return isset($_POST[$field_name]) ? trim($_POST[$field_name]) : '';
    }

    public static function getUrlWithPort($site_url, $site_port)
    {
        $url_parts = parse_url($site_url);
        $protocol = (isset($url_parts['scheme']) ? $url_parts['scheme'] : 'http') . '://';
        $port = !isset($site_port) || $site_port == 80 ? '' : ':' . $site_port;
        $host = (isset($url_parts['host']) ? trim($url_parts['host']) : '') . $port;
        $path = isset($url_parts['path']) ? $url_parts['path'] : '/';
        $site_url = $protocol . $host . $path;
        return $site_url;
    }
}