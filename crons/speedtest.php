<?php

require dirname(__FILE__) . '/base.php';

chdir(BASE_PATH);

$custom_server = !empty($config->speedtest_server_enabled) && !empty($config->speedtest_server) ? "--server {$config->speedtest_server}" : '';

exec("/usr/local/bin/speedtest-cli {$custom_server} --json", $result);

if (!empty($result[0])) {
    $response_data = $result[0];
    $result = json_decode($response_data);
    if (!empty($result->download)) {
        $download = $result->download;
        $upload = $result->upload;
        $latency = $result->server->latency;
        $db->addSpeedTest($download, $upload, $latency, $response_data);
    }
}
