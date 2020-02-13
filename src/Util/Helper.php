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

    public static function getPostValue($field_name, $default_value = '')
    {
        return isset($_POST[$field_name]) ? trim($_POST[$field_name]) : $default_value;
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

    public static function getTimezoneList()
    {
        static $timezones = null;

        if ($timezones === null) {
            $timezones = [];
            $offsets = [];
            $now = new \DateTime('now', new \DateTimeZone('UTC'));

            foreach (\DateTimeZone::listIdentifiers() as $timezone) {
                $now->setTimezone(new \DateTimeZone($timezone));
                $offsets[] = $offset = $now->getOffset();
                $timezones[$timezone] = '(' . self::formatGmtOffset($offset) . ') ' . self::formatTimezoneName($timezone);
            }

            array_multisort($offsets, $timezones);
        }

        return $timezones;
    }

    public static function formatGmtOffset($offset)
    {
        $hours = intval($offset / 3600);
        $minutes = abs(intval($offset % 3600 / 60));
        return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '+00:00');
    }

    public static function formatTimezoneName($name)
    {
        $name = str_replace('/', ', ', $name);
        $name = str_replace('_', ' ', $name);
        $name = str_replace('St ', 'St. ', $name);
        return $name;
    }
}