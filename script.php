<?php

require 'vendor/autoload.php';

$filename = dirname(__FILE__) . '/public/config.json';
$handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
fclose($handle);
$config = json_decode($contents);
$client = new GuzzleHttp\Client();
$response = $client->request('GET', 'http://httpbin.org/ip');
if ($response->getStatusCode() !== 200) throw new \Exception('Could not pull contents from IP checker.');

// get current ip (ipv4)
$ip_data = json_decode($response->getBody());
$ips = explode(',', $ip_data->origin);
$ip = isset($ips[0]) ? trim($ips[0]) : '';

// get ipv6
$ipv6 = $ip;
$response = $client->request('GET', 'https://api6.ipify.org/');
if ($response->getStatusCode() === 200) {
    $ipv6 = (string)$response->getBody();
}

$config_db = [
    'driver' => $config->db->driver,
    'hostname' => $config->db->hostname,
    'database' => $config->db->database,
    'username' => $config->db->username,
    'password' => $config->db->password,
    'charset' => 'utf8',
];

// connect to db
$adapter = new Zend\Db\Adapter\Adapter($config_db);

// check if currently selected ip is same
$sql = new Zend\Db\Sql\Sql($adapter);
$select = $sql->select('ips');
$select->where('status', 1);
$select->order('id DESC');
$select->limit(1);

$selectString = $sql->buildSqlString($select);
$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
$count = $results->count();
$latest_ip = $results->count() ? $results->current() : null;

// Insert IP into db if it's not the same as current one
if (empty($latest_ip) || strcmp($latest_ip->ip, $ip) !== 0) {

    // change any other ip status to 0
    $update = $sql->update('ips');
    $update->set(array(
        'status' => 0,
    ));
    $query = $sql->buildSqlString($update);
    $results = $adapter->query($query, $adapter::QUERY_MODE_EXECUTE);

    // insert new ip with status 1
    $insert = $sql->insert('ips');
    $insert->values(array(
        'ip' => $ip,
        'status' => 1,
        'date_created' => new Zend\Db\Sql\Expression('NOW()'),
    ));
    $query = $sql->buildSqlString($insert);
    $results = $adapter->query($query, $adapter::QUERY_MODE_EXECUTE);

    // update cloudflare records
    $key = new Cloudflare\API\Auth\APIKey($config->cloudflare->email, $config->cloudflare->api_key);
    $adapter = new Cloudflare\API\Adapter\Guzzle($key);
    $dns = new Cloudflare\API\Endpoints\DNS($adapter);

    // update dns records
    foreach ($config->cloudflare->records as $cloudflare_record) {
        if (empty($cloudflare_record->record_type)) continue;
        $zone_id = $config->cloudflare->zone_id; // zone id
        $record_type = $cloudflare_record->record_type; // type of your record
        $record_name = $cloudflare_record->record_name; // you could add www.your-domain.com or your-domain.com for your A record
        $proxied = (bool)$cloudflare_record->proxied; // whether you want cloudflare proxy or not
        $record_value = $ip; // your ip
        $record_id = $dns->getRecordID($zone_id, $record_type, $record_name);
        if (!empty($record_id)) {
            $details = array(
                'type' => $record_type,
                'name' => $record_name,
                'content' => $record_value,
                'proxied' => $proxied,
            );
            $dns->updateRecordDetails($zone_id, $record_id, $details);
        }

        // update ipv6
        // only works with A records
        if (strcmp($ip, $ipv6) !== 0 && $record_type == 'A' && !empty($cloudflare_record->update_ipv6)) {
            $record_type = 'AAAA';
            $record_value = $ipv6;
            $record_id = $dns->getRecordID($zone_id, $record_type, $record_name);
            if (!empty($record_id)) {
                $details = array(
                    'type' => $record_type,
                    'name' => $record_name,
                    'content' => $record_value,
                    'proxied' => $proxied,
                );
                $dns->updateRecordDetails($zone_id, $record_id, $details);
            }
        }
    }

    // send email with new ip information
    // Instantiation and passing `true` enables exceptions
    if (!empty($config->notifications->send_email)) {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {

            $mail_host = $config->notifications->host;
            $from_email = $config->notifications->from_email;
            $from_password = $config->notifications->from_password;
            $from_name = $config->notifications->from_name;
            $to_email = $config->notifications->to_email;
            $to_name = $config->notifications->to_name;
            $port = $config->notifications->port;

            //Server settings
            //$mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host = $mail_host;                    // Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = $from_email;                     // SMTP username
            $mail->Password = $from_password;                               // SMTP password
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port = $port;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom($from_email, $from_name);
            $mail->addAddress($to_email, $to_name);

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Your IP has changed.';
            $old_ip = isset($latest_ip->ip) ? $latest_ip->ip : '';
            $mail->Body = "We've just detected that your IP has changed. Here is your new IP: {$ip}";
            if (!empty($old_ip)) $mail->Body .= "<br><br>Your old IP was {$old_ip}";
            $mail->send();

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
