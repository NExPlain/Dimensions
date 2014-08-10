<?php

/**
 * Class ModelController
 *
 * @author Renfei Song
 */
class ModelController {
    private $dbc;

    public $id;
    public $scale;
    public $model_location;
    public $title;
    public $private;
    public $likes;
    public $views;
    public $downloads;
    public $last_update;
    public $description;
    public $uploader_id;
    public $uploader_username;

    public function __construct()
    {
        $this->dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        mysqli_query($this->dbc, "set names utf8");
    }

    public function __destruct()
    {
        mysqli_close($this->dbc);
    }

    /**
     * Load details of specified model and increase times of view by one.
     *
     * @param $id
     *
     * @return bool Success or not (failures are mostly caused by invalid model IDs).
     */
    public function load($id)
    {
        $query = "SELECT * FROM dimensions_models INNER JOIN dimensions_users ON dimensions_users.id = dimensions_models.uploader WHERE dimensions_models.id = '$id'";
        $result = mysqli_query($this->dbc, $query);
        if ($row = mysqli_fetch_array($result)) {
            $this->id = $id;
            $this->scale = $row["scale"] != "" ? $row["scale"] : 1.0;
            $this->model_location = "upload/".$row["stamp"]."/".$row["modelfile"];
            $this->title = $row["title"];
            $this->private = $row['isprivate'];
            $this->last_update = $row["lastupdate"];
            $this->description = $row["description"];
            $this->uploader_username = $row["username"];
            $this->uploader_id = $row["uploader"];
            $this->views = $row["views"] + 1;
            $this->downloads = $row["downloads"];

            // Calculate times of "like" operation
            $query = "SELECT count(*) AS likes FROM dimensions_models INNER JOIN dimensions_likes ON dimensions_models.id = dimensions_likes.model_id WHERE dimensions_models.id = '$id'";
            $result = mysqli_query($this->dbc, $query);
            $row = mysqli_fetch_array($result);
            $this->likes = $row['likes'];

            // Update times of view
            mysqli_query($this->dbc, "UPDATE dimensions_models SET views = '".$this->views."' WHERE id = '".$this->id."'");

            return true;
        } else {
            return false;
        }
    }

    /**
     * List all models.
     *
     * @see browse.php
     */
    public function list_all_models()
    {
        $query = "SELECT isprivate, dimensions_models.id AS id, title, username FROM dimensions_models INNER JOIN dimensions_users ON dimensions_models.uploader = dimensions_users.id";
        $result = mysqli_query($this->dbc, $query);
        echo <<<HTML
        <div class="model-grid">
            <ol class="model-line">
HTML;
        $counter = 0;
        while ($row = mysqli_fetch_array($result)) {
            if ($row['isprivate'] == 1)
                continue;
            if ((++$counter - 1) % 4 == 0) {
                echo <<<HTML
                    </ol>
                <ol class="model-line">
HTML;
            }
            echo <<<HTML
            <li class="model-cell">
                <div class="model-preview">
                    <a href='showcase.php?id={$row["id"]}'>
                        <img src="image/screenshot/{$row["id"]}.png" class="model-image">
                    </a>
                </div>
                <div class="model-info">
                    <div class="model-title">{$row["title"]}</div>
                    <div class="model-author">{$row["username"]}</div>
                </div>
            </li>
HTML;
        }
        echo <<<HTML
            </ol>
        </div>
HTML;
    }

    /**
     * @param $title
     * @param $uploader_id
     * @param $model
     * @param $scale
     * @param $private
     * @param $desc
     *
     * @return bool Always return true.
     */
    public function upload_model($title, $uploader_id, $model, $scale, $private, $desc)
    {
        $query = "SELECT * FROM dimensions_models WHERE uploader = '$uploader_id'";
        $result = mysqli_query($this->dbc, $query);
        $to_upload = mysqli_num_rows($result) + 1;
        $location = $uploader_id . "/" . $to_upload;
        $query = "INSERT INTO dimensions_models (title, uploader, modelfile, stamp, scale, isprivate, description) ".
            "VALUES ('$title','$uploader_id','$model','$location','$scale','$private','$desc')";
        mysqli_query($this->dbc, $query);
        return true;
    }

}