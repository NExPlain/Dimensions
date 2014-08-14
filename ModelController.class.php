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
    public $is_private;
    public $likes;
    public $views;
    public $downloads;
    public $last_update;
    public $description;
    public $uploader_id;
    public $uploader_username;
    public $uploader_avatar_url;

    public $cover_image_url;
    public $price;
    public $image_urls;

    public function __construct()
    {
        $this->dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        mysqli_query($this->dbc, "SET NAMES UTF8");
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
        $query = "SELECT * FROM dimensions_models INNER JOIN dimensions_users ON dimensions_users.id = dimensions_models.uploader_id WHERE dimensions_models.id = '$id'";
        $result = mysqli_query($this->dbc, $query);
        if ($row = mysqli_fetch_array($result)) {
            $this->id = $id;
            $this->scale = $row["scale"] != "" ? $row["scale"] : 1.0;
            $this->model_location = UPLOAD_PATH . "/" . $row["file_stamp"] . "/" . $row["model_name"];
            $this->title = $row["title"];
            $this->is_private = $row['is_private'];
            $this->last_update = $row["last_update"];
            $this->description = $row["description"];
            $this->uploader_username = $row["username"];
            $this->uploader_id = $row["uploader_id"];
            $this->uploader_avatar_url = AVATAR_PATH . "/" . ($row['avatar'] == NULL ? DEFAULT_AVATAR : $row['avatar']);
            $this->views = $row["views"] + 1;
            $this->downloads = $row["downloads"];
            $this->cover_image_url = UPLOAD_PATH . "/" . $row["file_stamp"] . "/" . $row["image_0"];

            $this->price = intval($row['price']);
            for ($ix = 0; $ix <= 5; ++$ix) {
                if (!empty($row['image_' . $ix])) {
                    $this->image_urls[] = UPLOAD_PATH . "/" . $row["file_stamp"] . "/" . $row['image_' . $ix];
                }
            }

            // Calculate times of "like" operation
            $query = "SELECT count(*) AS likes FROM dimensions_models INNER JOIN dimensions_likes ON dimensions_models.id = dimensions_likes.model_id WHERE dimensions_models.id = '$id'";
            $result = mysqli_query($this->dbc, $query);
            $row = mysqli_fetch_array($result);
            $this->likes = $row['likes'];

            // Update times of view
            mysqli_query($this->dbc, "UPDATE dimensions_models SET views = '" . $this->views . "' WHERE id = '" . $this->id . "'");

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
    public function list_all_models($page = 1)
    {
        $start = MODELS_PER_PAGE * ($page - 1);
        $query = "SELECT id FROM dimensions_models WHERE is_private = 0 ORDER BY id DESC LIMIT " . $start . ", " . MODELS_PER_PAGE;
        $result = mysqli_query($this->dbc, $query);
        echo '<ul class="models-list">';
        while ($row = mysqli_fetch_array($result)) {
            $id = $row['id'];
            $this->load($id);
            $badge = $this->price == 0 ? '<div class="item pricing-badge free">Free</div>' : '<div class="item pricing-badge premium">Premium</div>';
            echo <<<HTML
            <li class="model-item">
                <div class="model-card">
                    <div class="inner">
                        <a class="preview-image" href="showcase.php?id=$this->id" style="background-image:url($this->cover_image_url)"></a>
                        <div class="floating">
                            $badge
                            <div class="item views"><span class="icon glyphicon glyphicon-eye-open"></span>$this->views</div>
                            <div class="item likes"><span class="icon glyphicon glyphicon-heart"></span>$this->likes</div>
                        </div>
                        <div class="title">$this->title</div>
                    </div>
                </div>
                <div class="author">
                    <img class="avatar" src="$this->uploader_avatar_url">
                    <div class="name"><a href="user.php?id=$this->uploader_id">$this->uploader_username</a></div>
                </div>
            </li>
HTML;
        }
        echo '</ul>';
    }

    public function pagination($current_page = 1)
    {
        $query = "SELECT id FROM dimensions_models WHERE is_private = 0";
        $result = mysqli_query($this->dbc, $query);
        $models_count = mysqli_num_rows($result);
        $pages_count = ceil($models_count / MODELS_PER_PAGE);
        if ($pages_count < 2) {
            return;
        }
        echo '<ul class="pages">';
        for ($ix = 1; $ix <= $pages_count; ++$ix) {
            if ($ix == $current_page)
                echo '<span class="page current">' . $ix . '</span>';
            else
                echo '<a class="page" data-target="' . $ix . '" href="browse.php?page=' . $ix . '">' . $ix . '</a>';
        }
        echo '</ul>';
    }

    /**
     * Determine if there are related models for current shown model.
     *
     * @return bool
     * @see list_related_models
     * @see showcase.php
     */
    public function has_related_models()
    {
        $query = "SELECT * FROM dimensions_models WHERE uploader_id = '" . $this->uploader_id . "' AND id != '" . $this->id . "'";
        $result = mysqli_query($this->dbc, $query);
        return mysqli_num_rows($result) != 0;
    }

    /**
     * List related models (uploaded by the same user)
     *
     * @see showcase.php
     */
    public function list_related_models()
    {
        $query = "SELECT * FROM dimensions_models WHERE uploader_id = '" . $this->uploader_id . "' AND id != '" . $this->id . "'";
        $result = mysqli_query($this->dbc, $query);
        $upload_path = UPLOAD_PATH;
        echo "<ul>";
        while ($row = mysqli_fetch_array($result)) {
            echo <<<HTML
            <li>
                <a href="showcase.php?id={$row['id']}" title="{$row['title']}">
                    <img alt="{$row['title']}" src="{$upload_path}/{$row['file_stamp']}/{$row['image_0']}">
                </a>
            </li>
HTML;
        }
        echo "</ul>";
    }

    /**
     * Determine if the model is free.
     *
     * @return bool
     */
    public function is_free()
    {
        return $this->price == 0;
    }
}