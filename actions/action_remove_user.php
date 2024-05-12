<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();
$user = $_POST['remove-user'];


if (User::delete_user($dbh, $user)) {
    $session->addMessage('success', 'User banished successfully.');
}
else {
    $session->addMessage('error', 'Unable to banish user.');
}

header('Location: ../pages' );