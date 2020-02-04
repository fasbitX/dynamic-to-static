<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$page_title = 'DNS Records';
$dns_records = $db->getDnsRecords();

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
                            <button id="add-record" type="button" class="btn btn-primary btn-sm"><i
                                        class="fa fa-plus"></i> Add Record
                            </button>
                        </div>
                    </div>
                    <?php include_once BASE_PATH . '/includes/alert.php' ?>
                    <form method="post" action="./process.php">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <hr>
                            </div>
                            <div id="record-container" class="col-md-12"><?php
                                if (!empty($dns_records)) {
                                    $counter = 0;
                                    foreach ($dns_records as $dns_record):
                                        $counter++;
                                        $dns_record_id = $dns_record->dns_record_id;
                                        $record_type = "record_type-{$counter}";
                                        $record_name = "record_name-{$counter}";
                                        $record_proxied = "record_proxied-{$counter}";
                                        ?>
                                        <div class="row pt-1 pb-1">
                                            <div class="col-md-2">
                                                <select id="<?php echo $record_type ?>"
                                                        name="<?php echo $record_type ?>" class="form-control">
                                                    <?php
                                                    $options = ['Type' => '', 'A' => 'A', 'AAAA' => 'AAAA'];
                                                    foreach ($options as $option_key => $option_value):
                                                        $selected = $dns_record->record_type == $option_value ? ' selected="selected"' : '';
                                                        ?>
                                                        <option value="<?php echo $option_value; ?>" <?php echo $selected; ?>><?php echo $option_key; ?></option>
                                                    <?php
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-7">
                                                <input type="text" class="form-control"
                                                       name="<?php echo $record_name ?>"
                                                       id="<?php echo $record_name ?>" placeholder="Record Name"
                                                       value="<?php echo $dns_record->record_name ?>">
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <?php
                                                $checked = $dns_record->is_proxied == 1 ? ' checked="checked"' : '';
                                                ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                           id="<?php echo $record_proxied ?>"
                                                           name="<?php echo $record_proxied ?>" <?php echo $checked; ?>>
                                                    <label class="form-check-label" for="<?php echo $record_proxied ?>">
                                                        Proxied
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-1 text-right">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="deleteRecord(this, 'delete_record-<?php echo $dns_record_id ?>');">
                                                    <i
                                                            class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php
                                    endforeach;
                                } else {
                                    echo 'No Records.';
                                }
                                ?>
                            </div>
                            <div class="col-md-12">
                                <hr>
                                <button id="save-record" type="submit" class="btn btn-success btn-sm"> Save
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="action" value="dns-records">
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
