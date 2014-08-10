<?php
/**
 * Receives POST request when posting a comment.
 *
 * @see showcase.php
 * @author Renfei Song
 */
require_once "define.php";
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_query($dbc, "set names utf8");

if (isset($_POST['respond'])) {
    $user_id = $_POST['user_id'];
    $model_id = $_POST['model_id'];
    $content = $_POST['content'];
    $query = "INSERT INTO dimensions_comments (user_id, model_id, content) VALUES ('$user_id', '$model_id', '$content')";
    mysqli_query($dbc, $query);
    $comment_id = mysqli_insert_id($dbc);

    header("Location: showcase.php?id=".$model_id."#comment-".$comment_id);
}