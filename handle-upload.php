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

/* Get & validate uploader_id */

$uploader_id = @$_POST['uploader_id'];
$query = "SELECT * FROM dimensions_users WHERE id = '$uploader_id'";
$result = mysqli_query($dbc, $query);
if (mysqli_num_rows($result) == 0) {
    echo json_encode(array(
        "result" => "false",
        "message" => "Invalid uploader_id."
    ));
    exit;
}

/* Get file_stamp */

$query = "SELECT id FROM dimensions_models ORDER BY id DESC LIMIT 0, 1";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
$to_upload = $row['id'] + 1;
$file_stamp = $uploader_id . "/" . $to_upload;

/* Make destination directory */

$dir = "upload" . FILE_SLASH . $uploader_id . FILE_SLASH . $to_upload;

if (!is_dir($dir)) {
    if (!mkdir($dir, 0777, true)) {
        echo json_encode(array(
            "result" => "false",
            "message" => "Cannot execute mkdir command, please check your privileges."
        ));
        exit;
    }
}

/* Move files in */

if ($_FILES["model_file"]["error"] != UPLOAD_ERR_OK || $_FILES["cover_image"]["error"] != UPLOAD_ERR_OK) {
    echo json_encode(array(
        "result" => "false",
        "message" => "model_file or cover_image not found."
    ));
    exit;
}

move_uploaded_file($_FILES["model_file"]["tmp_name"], $dir . FILE_SLASH . $_FILES["model_file"]["name"]);
$model_name = $_FILES["model_file"]["name"];
move_uploaded_file($_FILES["cover_image"]["tmp_name"], $dir . FILE_SLASH . $_FILES["cover_image"]["name"]);
$images[0] = $_FILES["cover_image"]["name"];

for ($i = 0; $i < count($_FILES["textures"]["tmp_name"]); ++$i) {
    if ($_FILES["textures"]["error"][$i] == UPLOAD_ERR_OK) {
        move_uploaded_file($_FILES["textures"]["tmp_name"][$i], $dir . FILE_SLASH . $_FILES["textures"]["name"][$i]);
    }
}

for ($i = 0; $i < 5; ++$i) {
    if ($_FILES["images"]["error"][$i] == UPLOAD_ERR_OK) {
        move_uploaded_file($_FILES["images"]["tmp_name"][$i], $dir . FILE_SLASH . $_FILES["images"]["name"][$i]);
        $images[$i + 1] = $_FILES["images"]["name"][$i];
    } else {
        $images[$i + 1] = "";
    }
}

/* Get other fields */

if (empty($_POST['title'])) {
    echo json_encode(array(
        "result" => "false",
        "message" => "title not found."
    ));
    exit;
}

$title = $_POST['title'];
$price = empty($_POST['price']) ? 0 : $_POST['price'];
$description = $_POST['description'];
$tags = isset($_POST['tags']) ? $_POST['tags'] : array();
$license_id = empty($_POST['license']) ? 0 : $_POST['license'];
array_unique($tags);

/* Update database */

// insert model

$query = "INSERT INTO dimensions_models (title, uploader_id, model_name, file_stamp, scale, is_private, price, description, license_id, image_0, image_1, image_2, image_3, image_4, image_5) ".
    "VALUES ('$title','$uploader_id','$model_name','$file_stamp','1.0',0,'$price','$description','$license_id','$images[0]','$images[1]','$images[2]','$images[3]','$images[4]','$images[5]')";
mysqli_query($dbc, $query);

$uploaded_id = mysqli_insert_id($dbc);

// update tags

foreach ($tags as $tag) {
    $query = "SELECT * FROM dimensions_tags WHERE display_name = '$tag'";
    $result = mysqli_query($dbc, $query);
    if ($row = mysqli_fetch_array($result)) {
       $tag_id = $row['id'];
    } else {
        mysqli_query($dbc, "INSERT INTO dimensions_tags (display_name) VALUES ('$tag')");
        $tag_id = mysqli_insert_id($dbc);
    }
    mysqli_query($dbc, "INSERT INTO dimensions_tagging (tag_id, model_id) VALUES ('$tag_id', '$uploaded_id')");
}

/**
 * If it is a API call then echo the destination URL.
 * Otherwise redirect to it.
 *
 * @see api/upload.php
 */
if (isset($_GET['api']) && $_GET['api'] == "true") {
    echo json_encode(array(
        "result" => "true",
        "message" => "$uploaded_id"
    ));
} else {
    header("Location: showcase.php?id=" . $uploaded_id);
}