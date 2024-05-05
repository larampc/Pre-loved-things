<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../utils/files.php');
    $session = new Session();

    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');

    $dbh = get_database_connection();
    if(isset($_FILES['img1'])) {
        $image_id = upload_user_image("img1");
    }

    $user = User::get_user($dbh, $session->getId());
    if ($user == null) {
        $session->addMessage('error', 'Error editing profile. Please try again.');
        die(header('Location: ' . $_SERVER['HTTP_REFERER']));
    }
    if (!User::update_user($dbh, $session->getId(), $_POST['username'], $_POST['email'], $_POST['phone'], $_POST['name'],
        $image_id ?? $user->image)) {
        $session->addMessage('error', 'Error editing profile. Please try again.');
        die(header('Location: ' . $_SERVER['HTTP_REFERER']));
    }
    $session->addMessage('success', 'Profile updated successfully.');
    header('Location: ../pages/profile.php');