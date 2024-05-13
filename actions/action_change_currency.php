<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
if ($_SESSION['csrf'] !== $_POST['csrf']) {
    $session->addMessage('error', 'Illegitimate request.');
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();

if ($session->isLoggedIn()) {
    if (!User::set_user_currency($dbh, $session->getId(), $_GET['currency'])) {
        $session->addMessage("error", "Error setting user currency");
    }
}
$session->setCurrency($_GET['currency']);

header('Location: ' . $_SERVER['HTTP_REFERER']);