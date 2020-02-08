<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$page_title = 'Configurations';
$ip_4 = trim(file_get_contents('https://api.ipify.org/'));
$ip_6 = trim(file_get_contents('https://api6.ipify.org/'));
if (strcmp($ip_4, $ip_6) === 0) $ip_6 = '';

include_once BASE_PATH . '/includes/header.php';
?>
    <div class="row">
        <div class="col-md-2">&nbsp;</div>
        <div class="col-md-8">
            <div class="card" style="">
                <div class="card-body">
                    <h5 class="card-title text-uppercase"><?php echo $page_title ?></h5>
                    <?php include_once BASE_PATH . '/includes/alert.php' ?>
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
                            <label for="site_name" class="col-sm-3 col-form-label">Site Title</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="site_name" id="site_name"
                                       value="<?php echo $config->site_name ?>">
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

                        <!-- Cloudflare Settings -->
                        <div class="form-group row">
                            <div class="col-sm-12"><br><br></div>
                            <div class="col-sm-12">
                                <h6 class="text-uppercase">CloudFlare Configurations</h6>
                                <hr>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cloudflare_email" class="col-sm-3 col-form-label">CloudFlare Email</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="cloudflare_email" id="cloudflare_email"
                                       value="<?php echo $config->cloudflare_email ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cloudflare_api_key" class="col-sm-3 col-form-label">Global API
                                Key</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="cloudflare_api_key"
                                       id="cloudflare_api_key" value="<?php echo $config->cloudflare_api_key ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cloudflare_zone_id" class="col-sm-3 col-form-label">API Zone
                                ID</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="cloudflare_zone_id"
                                       id="cloudflare_zone_id" value="<?php echo $config->cloudflare_zone_id ?>">
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
                            <label for="notification_send_email" class="col-sm-3 col-form-label">Send Email</label>
                            <div class="col-sm-9">
                                <select id="notification_send_email" name="notification_send_email"
                                        class="form-control">
                                    <?php $alert_status = [0 => 'No', 1 => 'Yes',]; ?>
                                    <?php foreach ($alert_status as $status_index => $status): ?>
                                        <?php $selected = $status_index == (int)$config->notification_send_email ? ' selected="selected"' : '' ?>
                                        <option value="<?php echo $status_index; ?>" <?php echo $selected ?>><?php echo $status ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="notification_to_name" class="col-sm-3 col-form-label">Receiver Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="notification_to_name"
                                       id="notification_to_name"
                                       value="<?php echo $config->notification_to_name; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="notification_to_email" class="col-sm-3 col-form-label">Receiver Email</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="notification_to_email"
                                       id="notification_to_email"
                                       value="<?php echo $config->notification_to_email; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="notification_host" class="col-sm-3 col-form-label">SMTP Host</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="notification_host"
                                       id="notification_host" value="<?php echo $config->notification_host; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="notification_from_email" class="col-sm-3 col-form-label">SMTP Email</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="notification_from_email"
                                       id="notification_from_email"
                                       value="<?php echo $config->notification_from_email; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="notification_from_password" class="col-sm-3 col-form-label">SMTP
                                Password</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="notification_from_password"
                                       id="notification_from_password"
                                       value="<?php echo $config->notification_from_password; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="notification_port" class="col-sm-3 col-form-label">SMTP
                                Port</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="notification_port"
                                       id="notification_port"
                                       value="<?php echo $config->notification_port; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="notification_from_name" class="col-sm-3 col-form-label">Sender Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="notification_from_name"
                                       id="notification_from_name"
                                       value="<?php echo $config->notification_from_name; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <hr>
                            </div>
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <input type="hidden" name="action" value="config">
                                <button type="submit" class="btn btn-primary mb-2">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-2">&nbsp;</div>
    </div>
<?php
include_once BASE_PATH . '/includes/footer.php';
