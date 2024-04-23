<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');

    $dbh = get_database_connection();

    $user = User::get_user($dbh, $session->getId());
    User::update_user($dbh, $session->getId(), $_POST['username'], $_POST['email'], $_POST['phone'], $_POST['name'],
        empty($_POST['profilePhoto'])? $user->photoPath: $_POST['profilePhoto']);

    header('Location: ../pages/profile.php');