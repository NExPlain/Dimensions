<?php

class CommentController {

    private $dbc;

    public $comment_count;
    public $comments;

    public function __construct()
    {
        $this->dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        mysqli_query($this->dbc, "set names utf8");
        $this->comments = array();
    }

    public function __destruct()
    {
        mysqli_close($this->dbc);
    }

    public function load($model_id)
    {
        $query = "SELECT comments.id AS comment_id, user_id, username, email, content, comment_date FROM dimensions_comments AS comments INNER JOIN dimensions_users AS users ON comments.user_id = users.id WHERE comments.model_id = '$model_id'";
        $result = mysqli_query($this->dbc, $query);
        $this->comment_count = mysqli_num_rows($result);
        while ($row = mysqli_fetch_array($result)) {
            $comment = array(
                "user_id" => $row['user_id'],
                "user_name" => $row['username'],
                "user_email" => $row['email'],
                "comment_content" => $row['content'],
                "comment_date" => $row['comment_date'],
                "comment_id" => $row['comment_id']
            );
            $this->comments[] = $comment;
        }
    }
} 