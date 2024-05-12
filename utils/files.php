<?php

require_once ('../database/connection.db.php');
require_once ('uuid.php');

function upload_user_image($img) : string {
    $dbh = get_database_connection();

    $tempFileName = $_FILES[$img]['tmp_name'];

    $original = @imagecreatefromjpeg($tempFileName);
    if (!$original) $original = @imagecreatefrompng($tempFileName);
    if (!$original) $original = @imagecreatefromgif($tempFileName);

    if (!$original) die('Unknown image format!');

    $id = generate_uuid();
    $stmt = $dbh->prepare("INSERT INTO images(id) VALUES (?)");
    $stmt->execute(array($id));

    $FileName = "../uploads/profile_pics/$id.png";

    $width = imagesx($original);     // width of the original image
    $height = imagesy($original);    // height of the original image
    $square = min($width, $height);  // size length of the maximum square

    $small = imagecreatetruecolor(200, 200);
    imagecopyresized($small, $original, 0, 0, ($width>$square)?($width-$square)/2:0, ($height>$square)?($height-$square)/2:0, 200, 200, $square, $square);
    imagejpeg($small, $FileName);
    return $id;
}

function upload_item_image($img) : string {
    $dbh = get_database_connection();

    // PHP saves the file temporarily here
    // Image is the name of the file input in the form
    $tempFileName = $_FILES[$img]['tmp_name'];

    // Create an image representation of the original image
    // @ before function is to prevent warning messages
    $original = @imagecreatefromjpeg($tempFileName);
    if (!$original) $original = @imagecreatefrompng($tempFileName);
    if (!$original) $original = @imagecreatefromgif($tempFileName);

    if (!$original) die('Unknown image format!');

    // Insert image data into database
    $id = generate_uuid();
    $stmt = $dbh->prepare("INSERT INTO images(id) VALUES (?)");
    $stmt->execute(array($id));

    // Generate filenames for original, small and medium files
    $thumbnailFileName = "../uploads/thumbnails/$id.png";
    $mediumFileName = "../uploads/medium/$id.png";

    $width = imagesx($original);     // width of the original image
    $height = imagesy($original);    // height of the original image
    $square = min($width, $height);  // size length of the maximum square


    // We could also copy the file directly without converting to jpeg
    // move_uploaded_file($_FILES['image']['tmp_name'], $originalFileName);

    // Create and save a small square thumbnail
    $small = imagecreatetruecolor(200, 200);
    imagecopyresized($small, $original, 0, 0, ($width>$square)?($width-$square)/2:0, ($height>$square)?($height-$square)/2:0, 200, 200, $square, $square);
    imagejpeg($small, $thumbnailFileName);

    // Calculate width and height of medium sized image (max width: 400)
    $medium_width = $width;
    $medium_height = $height;
    if ($medium_width > 400) {
        $medium_width = 400;
        $medium_height = $medium_height * ( $medium_width / $width );
    }

    // Create and save a medium image
    $medium = imagecreatetruecolor($medium_width, $medium_height);
    imagecopyresized($medium, $original, 0, 0, 0, 0, $medium_width, $medium_height, $width, $height);
    imagejpeg($medium, $mediumFileName);
    return $id;
}