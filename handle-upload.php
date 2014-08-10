<?php
/**
 * No Comment
 *
 * @author Renfei Song
 */
require_once("define.php");
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("cannot-connect");

if (!isset($_COOKIE['user']) || !isset($_COOKIE['mask']) || sha1($_COOKIE['user'].COOKIE_SECURE) != $_COOKIE['mask']) {
    echo "unauthorized-user";
    exit;
}

$username = $_COOKIE['user'];
$query = "select id from dimensions_users where username = '$username'";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
$current_userid = $row['id'];

$query = "select * from dimensions_models where uploader_id = '$current_userid'";
$result = mysqli_query($dbc, $query) or die("cannot-execute");
$to_upload = mysqli_num_rows($result) + 1;

$dir = "upload\\" . $current_userid . "\\" . $to_upload;

if (!is_dir($dir)) {
    if (!mkdir($dir, 0777, true)) {
        echo "cannot-mkdir";
        exit;
    }
}

if (isset($_FILES["file"])) {
    move_uploaded_file($_FILES["file"]["tmp_name"], $dir . "\\" . $_FILES["file"]["name"]);
}

// if (mkdir("upload/" . $time_stamp) == false) {
// 			$msg = "Failed to make a directory.";
// 			$msg_type = "error";
// 			// TODO: This message cannot be displayed bacause the page is put into a hidden iframe.
// 		}
// 		move_uploaded_file($_FILES["file-model"]["tmp_name"], "upload/" . $time_stamp . "/" . $file_model_name);
// 		$num_textures_uploaded = count($_FILES["file-texture"]["name"]);
// 		for ($i = 0; $i < $num_textures_uploaded; ++$i) {
// 			move_uploaded_file($_FILES["file-texture"]["tmp_name"][$i], "upload/" . $time_stamp . "/" . $_FILES["file-texture"]["name"][$i]);
// 		}