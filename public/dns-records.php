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
                    <h5 class="card-title text-uppercase"><?php echo $page_title ?></h5>
                    <?php include_once BASE_PATH . '/includes/alert.php' ?>
                    <form method="post" action="./process.php">

                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-2">&nbsp;</div>
    </div>
<?php
include_once BASE_PATH . '/includes/footer.php';
