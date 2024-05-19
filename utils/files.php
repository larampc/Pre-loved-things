<?php

require_once ('../database/connection.db.php');
require_once ('uuid.php');
function create_image($tempFileName)
{
    $original = @imagecreatefrompng($tempFileName);
    if (!$original) $original = @imagecreatefromjpeg($tempFileName);
    if (!$original) $original = @imagecreatefromgif($tempFileName);
    if (!$original) die('Unknown image format');
    return $original;
}

function upload_image($img, $type = 'profile'): string
{
    $dbh = get_database_connection();
    $tempFileName = $_FILES[$img]['tmp_name'];

    $original = create_image($tempFileName);


    $id = generate_uuid();
    $stmt = $dbh->prepare("INSERT INTO images(id) VALUES (?)");
    $stmt->execute(array($id));

    $path = ($type === 'profile') ? "../uploads/profile_pics/$id.png" : "../uploads/medium/$id.png";
    $size = ($type === 'profile') ? 200 : 600;

    $width = imagesx($original);     // width of the original image
    $height = imagesy($original);    // height of the original image
    $square = min($width, $height);  // size length of the maximum square
    $resized = imagecreatetruecolor($size, $size);
    imagecopyresized($resized, $original, 0, 0, ($width > $square) ? ($width - $square) / 2 : 0, ($height > $square) ? ($height - $square) / 2 : 0, $size, $size, $square, $square);
    imagepng($resized, $path);
    return $id;
}

function upload_user_image($img): string
{
    return upload_image($img, 'profile');
}

function upload_item_image($img): string
{
    return upload_image($img, 'item');
}

function remove_uploaded_image($img, $type = 'profile'): bool
{
    $dbh = get_database_connection();
    $path = ($type === 'profile') ? "/../uploads/profile_pics/" : "/../uploads/medium/";
    if (!unlink(__DIR__ . $path . $img . ".png")) return false;
    $stmt = $dbh->prepare('DELETE FROM images WHERE id = ?');
    return $stmt->execute([$img]);
}

function remove_uploaded_user_img(string $img): bool
{
    return remove_uploaded_image($img, 'profile');
}

function remove_uploaded_item_imgs(array $imgs): bool
{
    $success = true;
    foreach ($imgs as $img) {
        if (!remove_uploaded_image($img, 'item')) $success = false;
    }
    return $success;
}

