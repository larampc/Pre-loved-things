<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();

if (isset($_SESSION['user_id'])) User::add_cart($dbh, $session->get_id(), $_GET['item']);
else {
    $session->add_to_cart($_GET['item']);
}
