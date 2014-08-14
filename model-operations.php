<?php
/**
 * The AJAX-backing-up script for operations related to a model.
 *
 * @see showcase.php
 * @author Renfei Song
 */

require_once "define.php";
require_once "ModelController.class.php";

$op = $_GET['op'];
$model_id = @$_GET['model_id'];
$user_id = @$_GET['user_id'];

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_query($dbc, "SET NAMES UTF8");

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

if ($op == "delete") {
    $queries[0] = "DELETE FROM dimensions_models WHERE id = '$model_id'";
    $queries[1] = "DELETE FROM dimensions_comments WHERE model_id = '$model_id'";
    $queries[2] = "DELETE FROM dimensions_favs WHERE model_id = '$model_id'";
    $queries[3] = "DELETE FROM dimensions_likes WHERE model_id = '$model_id'";
    foreach ($queries as $query) {
        mysqli_query($dbc, $query);
    }
    header("Location: my-profile.php");
}

if ($op == "request_models_by_page") {
    $model_controller = new ModelController();
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $model_controller->list_all_models($page);
}

if ($op == "request_pagination") {
    $model_controller = new ModelController();
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $model_controller->pagination($page);
}