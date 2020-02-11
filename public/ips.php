<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$page_title = 'IPs Log';
$ips = $db->getIps();

include_once BASE_PATH . '/includes/header.php';
?>
    <div class="row">
        <div class="col-md-2">&nbsp;</div>
        <div class="col-md-8">
            <div class="card" style="">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8"><h5 class="card-title text-uppercase"><?php echo $page_title ?></h5></div>
                        <div class="col-md-4 text-right">

                        </div>
                    </div>
                    <?php include_once BASE_PATH . '/includes/alert.php' ?>
                    <form method="post" action="./process.php">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <hr>
                            </div>
                            <div id="ip-container" class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">IP</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (!empty($ips)) {
                                            foreach ($ips as $ip):
                                                ?>
                                                <tr>
                                                    <th scope="row"><?php echo $ip->id ?></th>
                                                    <td><?php echo $ip->ip ?></td>
                                                    <td><?php echo $ip->type ?></td>
                                                    <td><?php echo $ip->status == '1' ? 'Active' : 'InActive'; ?></td>
                                                </tr>
                                            <?php
                                            endforeach;
                                        } else {
                                            echo 'No Records.';
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-2">&nbsp;</div>
    </div>
    <div id="record-sample" class="col-md-12 d-none">
        <div class="row pt-1 pb-1">
            <div class="col-md-2">
                <select id="{{RECORD_TYPE}}" name="{{RECORD_TYPE}}" class="form-control">
                    <option value="">Type</option>
                    <option>A</option>
                    <option>AAAA</option>
                </select>
            </div>
            <div class="col-md-7">
                <input type="text" class="form-control" name="{{RECORD_NAME}}"
                       id="{{RECORD_NAME}}" placeholder="Record Name" value="">
            </div>
            <div class="col-md-2 text-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1"
                           id="{{RECORD_PROXIED}}" name="{{RECORD_PROXIED}}">
                    <label class="form-check-label" for="{{RECORD_PROXIED}}">
                        Proxied
                    </label>
                </div>
            </div>
            <div class="col-md-1 text-right">
                <button type="button" class="btn btn-danger btn-sm" onclick="deleteRecord(this);"><i
                            class="fa fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
<?php

function footer_content()
{
    ?>
    <script>
        var no_records_text = 'No Records.';
        $(function () {
            // add record
            $('#add-record').on('click', function () {
                var record_id = $('#record-container .row').length + 1;
                var record = $('#record-sample').clone().removeAttr('id');
                var record_container = $('#record-container');

                // replace vars
                record.html(
                    record.html()
                        .replace(/{{RECORD_TYPE}}/g, 'record_type-' + record_id)
                        .replace(/{{RECORD_NAME}}/g, 'record_name-' + record_id)
                        .replace(/{{RECORD_PROXIED}}/g, 'record_proxied-' + record_id)
                );

                // append record
                if (record_container.text().trim() === no_records_text) record_container.text('');
                record_container.append(record.html());
            });
        });

        // delete record
        function deleteRecord(elem, delete_record_id) {
            // insert hidden entry for deletion
            if (typeof delete_record_id !== 'undefined') {
                input_hidden = $('<input>', {type: 'hidden', id: delete_record_id, name: delete_record_id, value: 1});
                $('#record-container').append(input_hidden);
            }
            var element = $(elem);
            element.parent().parent().remove();
            if ($('#record-container .row').length === 0) $('#record-container').text(no_records_text);
        }
    </script>
    <?php
}

include_once BASE_PATH . '/includes/footer.php';
