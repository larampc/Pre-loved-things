<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/logger.php');
$session = new Session();

log_to_stdout($_GET['csrf']);
log_to_stdout($_SESSION['csrf']);

if (!validate_csrf_token($_GET['csrf'])) {
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();

if ($session-> is_logged_in()) {
    if (!User::set_user_currency($dbh, $session->get_id(), $_GET['currency'])) {
        $session->add_message("error", "Error setting user currency");
    }
}
$session->set_currency($_GET['currency']);

header('Location: ' . $_SERVER['HTTP_REFERER']);