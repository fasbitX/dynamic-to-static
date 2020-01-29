<?php
require_once dirname(__FILE__) . '/bootstrap.php';

$page_title = 'DNS Records';

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
                            <div id="record-container" class="col-md-12">No Records.</div>
                            <div class="col-md-12">
                                <hr>
                                <button id="save-record" type="button" class="btn btn-success btn-sm"> Save
                                </button>
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
                <select class="form-control">
                    <option>Type</option>
                    <option>A</option>
                    <option>AAAA</option>
                    <option>TXT</option>
                </select>
            </div>
            <div class="col-md-7">
                <input type="text" class="form-control" name="[[RECORD_NAME]]"
                       id="[[RECORD_NAME]]" placeholder="Record Name" value="">
            </div>
            <div class="col-md-2 text-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value=""
                           id="[[RECORD_PROXIED]]">
                    <label class="form-check-label" for="[[RECORD_PROXIED]]">
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
        $(function () {
            // add record
            $('#add-record').on('click', function () {
                var record = $('#record-sample').clone().removeAttr('id');
                var record_container = $('#record-container');
                if (record_container.text().trim() === 'No Records.') record_container.text('');
                record_container.append(record.html());

            });
        });

        // delete record
        function deleteRecord(elem) {
            var element = $(elem);
            element.parent().parent().remove();
        }
    </script>
    <?php
}

include_once BASE_PATH . '/includes/footer.php';
