<?php

require dirname(__FILE__) . '/bootstrap.php';

use App\Util\Helper;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = 0;
$msg = "";

if (!empty($_POST)) {

    $action = Helper::getPostValue('action');
    $ip_4 = trim(file_get_contents('https://ipv4.icanhazip.com/'));
    $ip_6 = trim(file_get_contents('https://api6.ipify.org/'));
    if (strcmp($ip_4, $ip_6) === 0) $ip_6 = '';

    switch ($action) {
        case 'config':

            $site_name = Helper::getPostValue('site_name');
            $site_url = Helper::getPostValue('site_url');
            $site_port = Helper::getPostValue('site_port');

            if (empty($site_name) || empty($site_url) || empty($site_port)) {
                $error = 1;
                $msg .= "Site Title, URL or Port cannot be empty.";
                break;
            }

            $config_settings = [
                'site_name' => Helper::getPostValue('site_name'),
                'site_url' => Helper::getPostValue('site_url'),
                'site_port' => Helper::getPostValue('site_port'),
                'cloudflare_email' => Helper::getPostValue('cloudflare_email'),
                'cloudflare_api_key' => Helper::getPostValue('cloudflare_api_key'), // global api key
                'cloudflare_zone_id' => Helper::getPostValue('cloudflare_zone_id'),
                'notification_send_email' => in_array(Helper::getPostValue('notification_send_email'), ['on', 1]) ? 1 : 0,
                'notification_host' => Helper::getPostValue('notification_host'),
                'notification_from_email' => Helper::getPostValue('notification_from_email'),
                'notification_from_password' => Helper::getPostValue('notification_from_password'),
                'notification_from_name' => Helper::getPostValue('notification_from_name'),
                'notification_to_email' => Helper::getPostValue('notification_to_email'),
                'notification_to_name' => Helper::getPostValue('notification_to_name'),
                'notification_port' => Helper::getPostValue('notification_port'),
                'cron_speedtest' => Helper::getPostValue('cron_speedtest', 0),
                'cron_frequency_speedtest' => Helper::getPostValue('cron_frequency_speedtest'),
                'cron_ipchecker' => Helper::getPostValue('cron_ipchecker', 0),
                'cron_frequency_ipchecker' => Helper::getPostValue('cron_frequency_ipchecker'),
            ];

            foreach ($config_settings as $config_key => $config_setting) {
                $success = $db->updateConfig($config_key, $config_setting);
                if (!$success) {
                    $error = 1;
                    $msg .= "Couldn't update config for `{$config_key}`<br>";
                }
            }

            if (!$error) $msg = 'Successfully updated.';

            break;

        case 'dns-records':

            $records = [];
            $delete_records = [];
            // get records to save or delete
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'record_') === 0) {
                    $key_parts = explode('-', $key);
                    $records[$key_parts[1]][$key_parts[0]] = $value;
                } elseif (strpos($key, 'delete_record-') === 0) {
                    $key_parts = explode('-', $key);
                    $delete_records[] = $key_parts[1];
                }
            }

            // save records
            foreach ($records as $record) {
                $record_id = isset($record['record_id']) ? $record['record_id'] : '';
                $record_type = isset($record['record_type']) ? $record['record_type'] : '';
                $record_name = isset($record['record_name']) ? $record['record_name'] : '';
                $record_proxied = isset($record['record_proxied']) ? (int)$record['record_proxied'] : 0;
                $data = [
                    'dns_record_id' => $record_id,
                    'record_type' => $record_type,
                    'record_name' => $record_name,
                    'record_value' => $record_type === 'AAAA' ? $ip_6 : $ip_4,
                    'is_proxied' => $record_proxied,
                ];
                $success = $db->addRecord($data);
                if (!$success) {
                    $error = 1;
                    $msg .= "Couldn't insert `{$record_type}` Record for `{$record_name}`<br>";
                }
            }

            // delete records
            foreach ($delete_records as $delete_record) {
                $success = $db->deleteRecord($delete_record);
                if (!$success) {
                    $error = 1;
                    $msg .= "Couldn't delete dns_record_id={$delete_record}<br>";
                }
            }

            //echo '<pre>', print_r($records, true), '</pre>';
            if (!$error) $msg = 'Successfully added.';

            break;

        default:
            $error = 1;
            $msg = "Bad Request";
            break;
    }
}

$url = $_SERVER['HTTP_REFERER'] ?? './';
$config = $db->getConfig();
$_SESSION['submission_error'] = $error;
$_SESSION['submission_message'] = $msg;
header('Location: ' . $url);
