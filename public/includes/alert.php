<?php if (!defined('BASE_PATH')) die('Forbidden.');
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
