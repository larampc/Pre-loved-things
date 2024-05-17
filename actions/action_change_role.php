<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
if(!validate_csrf_token($_POST['csrf'])) {
    die(header('Location: ' . $_SERVER['HTTP_REFERER']));
}

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();

$user = User::get_user($dbh, $_POST['role-user']);

$role = $user->role=="admin"?"user":"admin";

if (User::change_role($dbh, $user->user_id, $role)) {
    $session->addMessage('success', $role=="admin"?"User promoted to admin":"User demoted");
}
else {
    $session->addMessage('error', 'Unable to change user role.');
}

header('Location: ' . $_SERVER['HTTP_REFERER']);