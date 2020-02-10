<?php

require dirname(__FILE__) . '/base.php';

chdir(BASE_PATH);
exec('/usr/local/bin/speedtest-cli --json', $result);

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
