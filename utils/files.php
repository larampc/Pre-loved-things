<?php

require_once ('../database/connection.db.php');
require_once ('uuid.php');

function upload_user_image($img) : string {
    $dbh = get_database_connection();

    $tempFileName = $_FILES[$img]['tmp_name'];

    $original = @imagecreatefrompng($tempFileName);
    if (!$original) $original = @imagecreatefromjpeg($tempFileName);
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
    imagepng($small, $FileName);
    return $id;
}

function upload_item_image($img) : string {
    $dbh = get_database_connection();

    // PHP saves the file temporarily here
    // Image is the name of the file input in the form
    $tempFileName = $_FILES[$img]['tmp_name'];

    // Create an image representation of the original image
    // @ before function is to prevent warning messages
    $original = @imagecreatefrompng($tempFileName);
    if (!$original) $original = @imagecreatefromjpeg($tempFileName);
    if (!$original) $original = @imagecreatefromgif($tempFileName);

    if (!$original) die('Unknown image format!');

    // Insert image data into database
    $id = generate_uuid();
    $stmt = $dbh->prepare("INSERT INTO images(id) VALUES (?)");
    $stmt->execute(array($id));

    // Generate filenames for original, small and medium files
    $mediumFileName = "../uploads/medium/$id.png";

    $width = imagesx($original);     // width of the original image
    $height = imagesy($original);    // height of the original image
    $square = min($width, $height);
    $medium = imagecreatetruecolor(600, 600);
    imagecopyresized($medium, $original, 0, 0, ($width>$square)?($width-$square)/2:0, ($height>$square)?($height-$square)/2:0, 600, 600, $square, $square);
    imagepng($medium, $mediumFileName);
    return $id;
}

function remove_uploaded_item_imgs(array $imgs) : bool {
    $dbh = get_database_connection();
    $success = true;
    foreach ($imgs as $img) {
        if(!unlink(__DIR__ . "/../uploads/medium" . $img . ".png")) $success = false;
        $stmt = $dbh->prepare('DELETE FROM images WHERE id = ?');
        if(!$stmt->execute([$img])) $success = false;
    }
    return $success;
}

function remove_uploaded_user_img(string $img): bool
{
    $dbh = get_database_connection();
    if(!unlink(__DIR__ . "/../uploads/profile_pics/" . $img . ".png")) return false;
    $stmt = $dbh->prepare('DELETE FROM images WHERE id = ?');
    return $stmt->execute([$img]);
}
