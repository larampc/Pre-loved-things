<?php

declare(strict_types=1);

session_start();

require_once ('database/item.class.php');
require_once ('database/connection.db.php');

$dbh = get_database_connection();

Item::register_item($dbh, $_POST['iname'], $_POST['description'],  $_POST['price'], $_POST['category'], $_SESSION['username']);
Item::register_item_images($dbh, array($_FILES['img1']['name'], $_FILES['img2']['name']));


$target_dir = "images/";
$target_file = $target_dir . basename($_FILES["img1"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is an actual image or fake image
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["img1"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    $uploadOk = 0;
}

// Check file size
if ($_FILES["img1"]["size"] > 500000) {
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 1) {
    if (!move_uploaded_file($_FILES["img1"]["tmp_name"], $target_file)) {
        echo "Sorry, there was an error uploading your file.";
    }
}


header('Location: profile.php');