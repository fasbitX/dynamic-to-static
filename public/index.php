<?php

define('BASE_PATH', dirname(__FILE__));

require BASE_PATH . '/_helper.php';

$page_title = 'IP Changer';
$config = getConfig();
$ip_4 = trim(file_get_contents('https://ipv4.icanhazip.com/'));
$ip_6 = trim(file_get_contents('https://api6.ipify.org/'));
if (strcmp($ip_4, $ip_6) === 0) $ip_6 = '';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $page_title; ?></title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="<?php url('css/main.css') ?>" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="<?php url() ?>"><?php echo $page_title; ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
            aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="<?php url() ?>">Home <span class="sr-only">(current)</span></a>
            </li>
        </ul>
    </div>
</nav>

<main role="main" class="container">
    <div class="row">
        <div class="col-md-2">&nbsp;</div>
        <div class="col-md-8">
            <div class="card" style="">
                <div class="card-body">
                    <h5 class="card-title text-uppercase">Configurations</h5>
                    <?php
                    if (isset($_SESSION['submission_error']) && isset($_SESSION['submission_message'])) {
                        $error = $_SESSION['submission_error'];
                        $message = $_SESSION['submission_message'];
                        unset($_SESSION['submission_error']);
                        unset($_SESSION['submission_message']);
                        ?>
                        <div class="alert alert-<?php echo $error == 1 ? 'danger' : 'success'; ?>" role="alert">
                            <?php echo $message; ?>
                        </div>
                        <?php
                    }
                    ?>
                    <form method="post" action="./process.php">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <div class="col-sm-12"><br></div>
                                <h6 class="text-uppercase">Status</h6>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="current-ipv4" class="col-sm-3 col-form-label">Current IPV4</label>
                            <div class="col-sm-9">
                                <input type="text" readonly class="form-control" name="current-ipv4" id="current-ipv4"
                                       value="<?php echo $ip_4; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="current-ipv6" class="col-sm-3 col-form-label">Current IPV6</label>
                            <div class="col-sm-9">
                                <input type="text" readonly class="form-control" name="current-ipv6" id="current-ipv6"
                                       value="<?php echo $ip_6 == '' ? 'No IPv6 configured for your internet.' : $ip_6; ?>">
                            </div>
                        </div>

                        <!-- Site Settings -->
                        <div class="form-group row">
                            <div class="col-sm-12"><br><br></div>
                            <div class="col-sm-12">
                                <h6 class="text-uppercase">Site Settings</h6>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="site_url" class="col-sm-3 col-form-label">Site URL</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="site_url" id="site_url"
                                       value="<?php echo $config->site_url ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="site_port" class="col-sm-3 col-form-label">Site Port</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="site_port" id="site_port"
                                       value="<?php echo $config->site_port ?>">
                            </div>
                        </div>

                        <!-- DB Settings -->
                        <div class="form-group row">
                            <div class="col-sm-12"><br><br></div>
                            <div class="col-sm-12">
                                <h6 class="text-uppercase">DB Settings</h6>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="db-driver" class="col-sm-3 col-form-label">DB Driver</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="db-driver" id="db-driver"
                                       value="<?php echo $config->db->driver ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="db-hostname" class="col-sm-3 col-form-label">DB Host</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="db-hostname" id="db-hostname"
                                       value="<?php echo $config->db->hostname ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="db-database" class="col-sm-3 col-form-label">DB Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="db-database" id="db-database"
                                       value="<?php echo $config->db->database ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="db-username" class="col-sm-3 col-form-label">DB Username</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="db-username" id="db-username"
                                       value="<?php echo $config->db->username ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="db-password" class="col-sm-3 col-form-label">DB Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" name="db-password" id="db-password"
                                       value="" placeholder="Leave blank for no change.">
                            </div>
                        </div>

                        <!-- Cloudflare Settings -->
                        <div class="form-group row">
                            <div class="col-sm-12"><br><br></div>
                            <div class="col-sm-12">
                                <h6 class="text-uppercase">CloudFlare Configurations</h6>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cloudflare-email" class="col-sm-3 col-form-label">CloudFlare Email</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="cloudflare-email" id="cloudflare-email"
                                       value="<?php echo $config->cloudflare->email ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cloudflare-api_key" class="col-sm-3 col-form-label">Global API
                                Key</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="cloudflare-api_key"
                                       id="cloudflare-api_key" value="<?php echo $config->cloudflare->api_key ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cloudflare-zone_id" class="col-sm-3 col-form-label">API Zone
                                ID</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="cloudflare-zone_id"
                                       id="cloudflare-zone_id" value="<?php echo $config->cloudflare->zone_id ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3">DNS Records</div>
                            <div class="col-sm-9">
                                <?php foreach ($config->cloudflare->records as $record_index => $record): ?>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <h6 class="text-uppercase"><?php echo 'Record ' . ($record_index + 1) ?></h6>

                                        </div>
                                    </div>
                                    <?php
                                    $form_id = "record_type-{$record_index}";
                                    $form_name = "record_type-{$record_index}";
                                    $title = "Record Type";
                                    $value = $record->record_type;
                                    ?>
                                    <div class="form-group row">
                                        <label for="<?php echo $form_id ?>"
                                               class="col-sm-4 col-form-label"><?php echo $title ?></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="<?php echo $form_name ?>"
                                                   id="<?php echo $form_id ?>" value="<?php echo $value; ?>">
                                        </div>
                                    </div>
                                    <?php
                                    $form_id = "record_name-{$record_index}";
                                    $form_name = "record_name-{$record_index}";
                                    $title = "Record Name";
                                    $value = $record->record_name;
                                    ?>
                                    <div class="form-group row">
                                        <label for="<?php echo $form_id ?>"
                                               class="col-sm-4 col-form-label"><?php echo $title ?></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="<?php echo $form_name ?>"
                                                   id="<?php echo $form_id ?>" value="<?php echo $value; ?>">
                                        </div>
                                    </div>
                                    <?php
                                    $form_id = "proxied-{$record_index}";
                                    $form_name = "proxied-{$record_index}";
                                    $title = "CloudFlare Proxied";
                                    $value = $record->proxied;
                                    $checked = !empty($value) ? ' checked="checked"' : '';
                                    ?>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><?php echo $title ?></div>
                                        <div class="col-sm-8">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       id="<?php echo $form_id ?>"
                                                       name="<?php echo $form_name ?>" <?php echo $checked; ?>>
                                                <label class="form-check-label" for="<?php echo $form_id ?>">
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $form_id = "update_ipv6-{$record_index}";
                                    $form_name = "update_ipv6-{$record_index}";
                                    $title = "Also Update IPv6";
                                    $value = $record->update_ipv6;
                                    $checked = !empty($value) ? ' checked="checked"' : '';
                                    ?>
                                    <div class="form-group row">
                                        <div class="col-sm-4"><?php echo $title ?></div>
                                        <div class="col-sm-8">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       id="<?php echo $form_id ?>"
                                                       name="<?php echo $form_name ?>" <?php echo $checked; ?>>
                                                <label class="form-check-label" for="<?php echo $form_id ?>">
                                                    Yes
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            </div>
                        </div>



                        <!-- Notification Settings -->
                        <div class="form-group row">
                            <div class="col-sm-12"><br><br></div>
                            <div class="col-sm-12">
                                <h6 class="text-uppercase">Notifications</h6>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="notifications-send_email" class="col-sm-3 col-form-label">Send Email</label>
                            <div class="col-sm-9">
                                <select id="notifications-send_email" name="notifications-send_email"
                                        class="form-control">
                                    <?php $alert_status = [0 => 'No', 1 => 'Yes',]; ?>
                                    <?php foreach ($alert_status as $status_index => $status): ?>
                                        <?php $selected = $status_index == (int)$config->notifications->send_email ? ' selected="selected"' : '' ?>
                                        <option value="<?php echo $status_index; ?>" <?php echo $selected ?>><?php echo $status ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="notifications-to_name" class="col-sm-3 col-form-label">Receiver Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="notifications-to_name"
                                       id="notifications-to_name"
                                       value="<?php echo $config->notifications->to_name; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="notifications-to_email" class="col-sm-3 col-form-label">Receiver Email</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="notifications-to_email"
                                       id="notifications-to_email"
                                       value="<?php echo $config->notifications->to_email; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="notifications-host" class="col-sm-3 col-form-label">SMTP Host</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="notifications-host"
                                       id="notifications-host" value="<?php echo $config->notifications->host; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="notifications-from_email" class="col-sm-3 col-form-label">SMTP Email</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="notifications-from_email"
                                       id="notifications-from_email"
                                       value="<?php echo $config->notifications->from_email; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="notifications-from_password" class="col-sm-3 col-form-label">SMTP
                                Password</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="notifications-from_password"
                                       id="notifications-from_password"
                                       value="<?php echo $config->notifications->from_password; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="notifications-port" class="col-sm-3 col-form-label">SMTP
                                Port</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="notifications-port"
                                       id="notifications-port"
                                       value="<?php echo $config->notifications->port; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="notifications-from_name" class="col-sm-3 col-form-label">Sender Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="notifications-from_name"
                                       id="notifications-from_name"
                                       value="<?php echo $config->notifications->from_name; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <hr>
                            </div>
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary mb-2">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-2">&nbsp;</div>
    </div>
</main><!-- /.container -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</body>
</html>
