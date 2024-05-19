<?php

    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../utils/files.php');
    $session = new Session();
    if(!validate_csrf_token($_POST['csrf'])) {
        die(header('Location: ' . $_SERVER['HTTP_REFERER']));
    }

    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../database/connection.db.php');

    $dbh = get_database_connection();

    $user = User::get_user($dbh, $session->get_id());
    if ($user == null) {
        $session->add_message('error', 'Error editing profile. Please try again.');
        die(header('Location: ' . $_SERVER['HTTP_REFERER']));
    }

    if(!isset($_POST["hiddenimg1"])){
        if($user->image !== "0") remove_uploaded_user_img($user->image);
        if(!empty($_FILES['img1']['name'])) {
            $image_id = upload_user_image("img1");
        }
        else {
            $image_id = "0";
        }
    }

    if (!User::update_user($dbh, $session->get_id(), $_POST['username'], $_POST['email'], $_POST['phone'], $_POST['name'],
        $image_id ?? $user->image)) {
        $session->add_message('error', 'Error editing profile. Please try again.');
        die(header('Location: ' . $_SERVER['HTTP_REFERER']));
    }
    $session->add_message('success', 'Profile updated successfully.');
    header('Location: ../pages/profile.php');