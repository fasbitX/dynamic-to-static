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
$ipv4 = trim(file_get_contents('https://ipv4.icanhazip.com/'));
$ipv6 = trim(file_get_contents('https://api6.ipify.org/'));
if (strcmp($ipv4, $ipv6) === 0) $ipv6 = '';


// check if currently selected ip is same
$current_ipv4 = $db->getCurrentIp('IPv4');
$current_ipv6 = $db->getCurrentIp('IPv6');

// Insert IPv4
if (empty($current_ipv4) || strcmp($current_ipv4, $ipv4) !== 0) {
    // update ip records
    updateIpv4Records($config, $db, $ipv4);
    // send email with new ip information
    sendEmail($config, $current_ipv4, $ipv4);
}

// Insert IPv6
if ((!empty($ipv6) && empty($current_ipv6)) || strcmp($current_ipv6, $ipv6) !== 0) {
    // update ip records
    updateIpv6Records($config, $db, $ipv6);
    // send email with new ip information
    sendEmail($config, $current_ipv6, $ipv6);
}

function updateIpv4Records(object $config,  App\Models\Db $db, $ipv4)
{
    // change any other ip status to 0
    $db->disableIp('IPv4');
    $db->addIp($ipv4, 'IPv4');

    // update cloudflare records
    $key = new Cloudflare\API\Auth\APIKey($config->cloudflare_email, $config->cloudflare_api_key);
    $adapter = new Cloudflare\API\Adapter\Guzzle($key);
    $dns = new Cloudflare\API\Endpoints\DNS($adapter);

    // update dns records
    $records = $db->getDnsRecords();
    foreach ($records as $record) {
        if (empty($record->record_type)) continue;
        $zone_id = $config->cloudflare_zone_id; // zone id
        $record_type = $record->record_type; // type of your record
        $record_name = $record->record_name; // you could add www.your-domain.com or your-domain.com for your A record
        $proxied = (bool)$record->is_proxied; // whether you want cloudflare proxy or not
        if ($record_type == 'AAAA') continue; // skip ivp6 records

        $record_value = $ipv4; // your ip
        $record_id = $dns->getRecordID($zone_id, $record_type, $record_name);
        $details = array(
            'type' => $record_type,
            'name' => $record_name,
            'content' => $record_value,
            'proxied' => $proxied,
        );

        if (!empty($record_id)) {
            $dns->updateRecordDetails($zone_id, $record_id, $details);
        } else {
            $dns->addRecord($zone_id, $record_type, $record_name, $record_value, 0, $proxied);
        }
    }
}

function updateIpv6Records(object $config,  App\Models\Db $db, $ipv6)
{
    // change any other ip status to 0
    $db->disableIp('IPv6');
    $db->addIp($ipv6, 'IPv6');

    // update cloudflare records
    $key = new Cloudflare\API\Auth\APIKey($config->cloudflare_email, $config->cloudflare_api_key);
    $adapter = new Cloudflare\API\Adapter\Guzzle($key);
    $dns = new Cloudflare\API\Endpoints\DNS($adapter);

    // update ipv6
    // update dns records
    $records = $db->getDnsRecords();
    foreach ($records as $record) {
        if (empty($record->record_type)) continue;
        $zone_id = $config->cloudflare_zone_id; // zone id
        $record_type = $record->record_type; // type of your record
        $record_name = $record->record_name; // you could add www.your-domain.com or your-domain.com for your A record
        $proxied = (bool)$record->is_proxied; // whether you want cloudflare proxy or not
        if ($record_type != 'AAAA') continue; // skip ivp4 records

        $record_value = $ipv6;
        $record_id = $dns->getRecordID($zone_id, $record_type, $record_name);
        $details = array(
            'type' => $record_type,
            'name' => $record_name,
            'content' => $record_value,
            'proxied' => $proxied,
        );

        if (!empty($record_id)) {
            $dns->updateRecordDetails($zone_id, $record_id, $details);
        } else {
            $dns->addRecord($zone_id, $record_type, $record_name, $record_value, 0, $proxied);
        }
    }
}

function sendEmail($config, $old_ip, $new_ip)
{
    // Instantiation and passing `true` enables exceptions
    if (!empty($config->notifications_send_email)) {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {

            $mail_host = $config->notifications_host;
            $from_email = $config->notifications_from_email;
            $from_password = $config->notifications_from_password;
            $from_name = $config->notifications_from_name;
            $to_email = $config->notifications_to_email;
            $to_name = $config->notifications_to_name;
            $port = $config->notifications_port;

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

            $mail->Body = "We've just detected that your IP has changed. Here is your new IP: {$new_ip}";
            if (!empty($old_ip)) $mail->Body .= "<br><br>Your old IP was {$old_ip}";
            $mail->send();

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}