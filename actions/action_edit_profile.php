<?php

declare(strict_types=1);

session_start();

require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$dbh = get_database_connection();

$user = User::get_user($dbh, (int)$_SESSION['user_id']);
User::update_user($dbh, (int)$_SESSION['user_id'],
    empty($_POST['username'])? $user->username: $_POST['username'],
    empty($_POST['email'])? $user->email: $_POST['email'],
    empty($_POST['phone'])? $user->phone: $_POST['phone'],
    empty($_POST['name'])? $user->name: $_POST['name'],
    empty($_POST['profilePhoto'])? $user->photoPath: $_POST['profilePhoto']);

header('Location: ../pages/profile.php');