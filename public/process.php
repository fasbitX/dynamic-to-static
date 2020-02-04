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
            print_r($_POST);
            exit;
            break;

        case 'dns-records':

            $records = [];
            $delete_records = [];
            // get records to save or delete
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'record_') === 0) {
                    var_dump($key);
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
                    $msg .= "Couldn't insert `{$record_type}` Record for `{$record_name}`";
                }
            }

            // delete records
            foreach ($delete_records as $delete_record) {
                $success = $db->deleteRecord($delete_record);
                if (!$success) {
                    $error = 1;
                    $msg .= "Couldn't delete dns_record_id={$delete_record}";
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
