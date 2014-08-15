<?php
/**
 * Receives POST request when posting a comment.
 *
 * @see showcase.php
 * @author Renfei Song
 */

require_once "functions.php";

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_query($dbc, "SET NAMES UTF8");

if (isset($_POST['respond'])) {
    $user_id = $_POST['user_id'];
    $model_id = $_POST['model_id'];
    $content = $_POST['content'];

    if (!empty($user_id) && !empty($model_id) && !empty($content)) {
        $query = "INSERT INTO dimensions_comments (user_id, model_id, content) VALUES ('$user_id', '$model_id', '$content')";
        mysqli_query($dbc, $query);
        $comment_id = mysqli_insert_id($dbc);
        header("Location: showcase.php?id=" . $model_id . "#comment-" . $comment_id);
    } else {
        header("Location: showcase.php?id=" . $model_id . "&msg_id=1&token=" . time());
    }
}