<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

if(!validate_csrf_token($_POST['csrf'])) {
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}


require_once(__DIR__ . '/../database/track_item.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();

$confirmCode = $_POST['confirmationCode'];

if (!TrackItem::valid_code($dbh, $confirmCode)) {
    $session->addMessage("error", "Invalid confirmation code");
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

if (TrackItem::update_shipping($dbh, $_POST["purchase"])) {
    $session->addMessage('success', 'Shipping process updated successfully.');
}
else {
    $session->addMessage('error', 'Unable to update process.');
}

header('Location: ../pages');