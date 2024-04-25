<?php
function upload_image($img)
{

    $target_dir = "../images/";
    $target_file = $target_dir . basename($_FILES[$img]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is an actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES[$img]["tmp_name"]);
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
    if ($_FILES[$img]["size"] > 500000) {
        $uploadOk = 0;
    }

// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 1) {
        if (!move_uploaded_file($_FILES[$img]["tmp_name"], $target_file)) {
            echo "Sorry, there was an error uploading your file.";
        }
    }

}