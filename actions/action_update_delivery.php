<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if ($_SESSION['csrf'] !== $_POST['csrf']) {
    $session->addMessage('error', 'Illegitimate request.');
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}


require_once(__DIR__ . '/../database/track_item.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();

if (TrackItem::update_delivery($dbh, $_POST["purchase"], $_POST['new-date'])) {
    $session->addMessage('success', 'Delivery updated successfully.');
}
else {
    $session->addMessage('error', 'Unable to update delivery.');
}

header('Location: ' . $_SERVER['HTTP_REFERER']);