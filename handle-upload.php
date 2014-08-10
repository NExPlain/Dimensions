<?php
/**
 * Receives POST request when uploading a model.
 *
 * @see upload.php
 * @author Renfei Song
 */

require_once "define.php";
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_query($dbc, "SET NAMES UTF8");

/* Get uploader_id and file_stamp */

$uploader_id = $_POST['uploader_id'];
$query = "SELECT id FROM dimensions_models ORDER BY id DESC LIMIT 0, 1";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
$to_upload = $row['id'] + 1;
$file_stamp = $uploader_id . "/" . $to_upload;

/* Make destination directory */

$dir = "upload" . FILE_SLASH . $uploader_id . FILE_SLASH . $to_upload;

if (!is_dir($dir)) {
    if (!mkdir($dir, 0777, true)) {
        echo "cannot-mkdir";
        exit;
    }
}

/* Move files in */

if (isset($_FILES["model_file"])) {
    move_uploaded_file($_FILES["model_file"]["tmp_name"], $dir . FILE_SLASH . $_FILES["model_file"]["name"]);
    $model_name = $_FILES["model_file"]["name"];
}
if (isset($_FILES["cover_image"])) {
    move_uploaded_file($_FILES["cover_image"]["tmp_name"], $dir . FILE_SLASH . $_FILES["cover_image"]["name"]);
    $images[0] = $_FILES["cover_image"]["name"];
}
if (isset($_FILES["textures"])) {
    for ($i = 0; $i < count($_FILES["textures"]["tmp_name"]); ++$i) {
        if (isset($_FILES["textures"]["tmp_name"][$i])) {
            move_uploaded_file($_FILES["textures"]["tmp_name"][$i], $dir . FILE_SLASH . $_FILES["textures"]["name"][$i]);
        }
    }
}
if (isset($_FILES["images"])) {
    for ($i = 0; $i < 5; ++$i) {
        if (isset($_FILES["images"]["tmp_name"][$i])) {
            move_uploaded_file($_FILES["images"]["tmp_name"][$i], $dir . FILE_SLASH . $_FILES["images"]["name"][$i]);
            $images[$i + 1] = $_FILES["images"]["name"][$i];
        } else {
            $images[$i + 1] = "";
        }
    }
}

/* Get other fields */

$title = $_POST['title'];
$scale = $_POST['scale'];
$price = $_POST['price'];
$description = $_POST['description'];

/* Update database */

$query = "INSERT INTO dimensions_models (title, uploader_id, model_name, file_stamp, scale, is_private, price, description, image_0, image_1, image_2, image_3, image_4, image_5) ".
    "VALUES ('$title','$uploader_id','$model_name','$file_stamp','$scale',0,'$price','$description','$images[0]','$images[1]','$images[2]','$images[3]','$images[4]','$images[5]')";
mysqli_query($dbc, $query);

header("Location: showcase.php?id=" . mysqli_insert_id($dbc));