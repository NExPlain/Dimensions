<?php

require_once "define.php";

$op = $_GET['op'];
$model_id = $_GET['model_id'];
$user_id = $_GET['user_id'];

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_query($dbc, "set names utf8");

if ($op == "add_fav") {
    $query = "SELECT * FROM dimensions_favs WHERE model_id = '$model_id' AND user_id = '$user_id'";
    $result = mysqli_query($dbc, $query);
    if (mysqli_num_rows($result) == 0) {
        $query = "INSERT INTO dimensions_favs (user_id, model_id) VALUES ('$user_id', '$model_id')";
        mysqli_query($dbc, $query);
    }
}

if ($op == "add_like") {
    $query = "SELECT * FROM dimensions_likes WHERE model_id = '$model_id' AND user_id = '$user_id'";
    $result = mysqli_query($dbc, $query);
    if (mysqli_num_rows($result) == 0) {
        $query = "INSERT INTO dimensions_likes (user_id, model_id) VALUES ('$user_id', '$model_id')";
        mysqli_query($dbc, $query);
    }
}