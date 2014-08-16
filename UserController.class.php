<?php
/**
 * Class UserController
 *
 * @author Renfei Song
 */
class UserController {

    private $dbc;

    public $has_user;

    public $id;
    public $email;
    public $username;
    public $bio;
    public $balance;
    public $join_date;
    public $avatar_url;
    public $hashed_password;

    public $upload_count;
    public $favs_received;
    public $likes_received;
    public $views_received;
    public $downloads_received;

    public function __construct()
    {
        $this->dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        mysqli_query($this->dbc, "SET NAMES UTF8");
        $this->has_user = false;
    }

    public function __destruct()
    {
        mysqli_close($this->dbc);
    }

    /**
     * Identify current logged-in user's id.
     *
     * @return int id or 0 (no user)
     */
    public function current_user_id()
    {
        if (isset($_COOKIE['mask']) && isset($_COOKIE['username']) && isset($_COOKIE['email'])) {
            if (sha1($_COOKIE['email'].$_COOKIE['username']) == $_COOKIE['mask']) {
                $query = "SELECT id FROM dimensions_users WHERE email = '" . $_COOKIE['email'] . "'";
                $result = mysqli_query($this->dbc, $query);
                $row = mysqli_fetch_array($result);
                return $row['id'];
            }
        }
        return 0;
    }

    /**
     * Fetch current logged-in user's information
     */
    public function init()
    {
        if (($id = $this->current_user_id()) > 0) {
            $this->load($id);
        }
    }

    public function load($id)
    {
        $query = "SELECT * FROM dimensions_users WHERE id = '$id'";
        $result = mysqli_query($this->dbc, $query);
        if ($row = mysqli_fetch_array($result)) {
            // basic information
            $this->has_user = true;
            $this->id = $id;
            $this->email = $row['email'];
            $this->username = $row['username'];
            $this->bio = $row['bio'];
            $this->balance = $row['balance'];
            $this->join_date = explode(" ", $row['joindate'])[0];
            $this->avatar_url = AVATAR_PATH . "/" . ($row['avatar'] == NULL ? DEFAULT_AVATAR : $row['avatar']);
            $this->hashed_password = $row['userpswd'];

            // statistics
            $this->views_received = 0;
            $this->downloads_received = 0;
            $query = "SELECT * FROM dimensions_models WHERE uploader_id = '$id'";
            $result = mysqli_query($this->dbc, $query);
            $this->upload_count = mysqli_num_rows($result);
            while ($row = mysqli_fetch_array($result)) {
                $this->views_received += intval($row['views']);
                $this->downloads_received += intval($row['downloads']);
            }
            $query = "SELECT * FROM dimensions_models INNER JOIN dimensions_likes ON dimensions_models.id = dimensions_likes.model_id WHERE dimensions_models.uploader_id = '$id'";
            $result = mysqli_query($this->dbc, $query);
            $this->likes_received = mysqli_num_rows($result);
            $query = "SELECT * FROM dimensions_models INNER JOIN dimensions_favs ON dimensions_models.id = dimensions_favs.model_id WHERE dimensions_models.uploader_id = '$id'";
            $result = mysqli_query($this->dbc, $query);
            $this->favs_received = mysqli_num_rows($result);
        }
    }

    /**
     * Change current user's password
     *
     * @param $new_password
     * @param $old_password
     * @param $msg
     * @param $msg_type
     *
     * @return bool Success or not.
     */
    public function change_password($new_password, $old_password, &$msg, &$msg_type)
    {
        if (empty($new_password)) {
            $msg = "新密码不能为空";
            $msg_type = "error";
            return false;
        }
        if ($this->hashed_password != sha1($old_password)) {
            $msg = "当前密码输入错误";
            $msg_type = "error";
            return false;
        }
        $query = "UPDATE dimensions_users SET userpswd = SHA('$new_password') WHERE id = '".$this->id."'";
        mysqli_query($this->dbc, $query);
        $msg = "密码修改成功。";
        $msg_type = "success";
        return true;
    }

    /**
     * List all models that current user has uploaded.
     *
     * @see user.php
     */
    public function list_models_uploaded($self = false)
    {
        echo '<ul class="models-list">';
        $query = "SELECT * FROM dimensions_models WHERE uploader_id = '" . $this->id . "' ORDER BY id DESC";
        $result = mysqli_query($this->dbc, $query);
        while ($row = mysqli_fetch_array($result)) {
            $cover_image_url = UPLOAD_PATH . "/" . $row["file_stamp"] . "/" . $row["image_0"];
            $badge = $row['price'] == '0' ? '<div class="item pricing-badge free">Free</div>' : '<div class="item pricing-badge premium">Premium</div>';
            $edit_button = "";
            if ($self) {
                $edit_button = '<a class="edit-link" title="在编辑器中编辑此模型" href="editor/edit.php?id=' . $row["id"] . '"><span class="icon glyphicon glyphicon-edit"></span> 编辑</a>';
            }
            echo <<<HTML
            <li class="model-item">
                <div class="model-card">
                    <div class="inner">
                        <a class="preview-image" href="showcase.php?id={$row['id']}" style="background-image:url($cover_image_url)"></a>
                        <div class="floating">
                            $badge
                            <div class="item views"><span class="icon glyphicon glyphicon-eye-open"></span>{$row['views']}</div>
                        </div>
                        <div class="title">{$row['title']}$edit_button</div>
                    </div>
                </div>
            </li>
HTML;
        }
        echo "</ul>";
    }

    /**
     * Write database.
     *
     * @param $email
     * @param $username
     * @param $password1
     * @param $password2
     * @param $msg
     * @param $msg_type
     *
     * @return bool Success or not.
     */
    public function register($email, $username, $password1, $password2, &$msg, &$msg_type)
    {
        $query = "SELECT * FROM dimensions_users WHERE email = '$email'";
        $result = mysqli_query($this->dbc, $query);

        if (empty($email) || empty($username) || empty($password1) || empty ($password2)) {
            $msg_type = "warning";
            $msg = "必须填写所有项目，请重试。";
            return false;
        }

        if (mysqli_num_rows($result) != 0) {
            $msg_type = "warning";
            $msg = "邮箱已被占用，请重新选择一个邮箱地址或直接<a href='login.php'>登陆</a>。";
            return false;
        }

        if ($password1 != $password2) {
            $msg_type = "warning";
            $msg = "两次密码不一致。";
            return false;
        }

        $query = "INSERT INTO dimensions_users (username, userpswd, email) VALUES ('$username', SHA('$password1'), '$email')";
        mysqli_query($this->dbc, $query);
        $msg = "注册成功！您可以<a href='login.php'>从这里登陆</a>。";
        $msg_type = "success";
        return true;
    }

    /**
     * Write database, cookie & redirect
     *
     * @param $email
     * @param $password
     * @param $msg
     * @param $msg_type
     *
     * @return bool Success or not.
     */
    public function login($email, $password, &$msg, &$msg_type)
    {
        if (empty($email) || empty($password)) {
            $msg = '必须填写合法的邮箱地址、密码才能登陆。';
            $msg_type = "error";
            return false;
        }

        $query = "SELECT * FROM dimensions_users WHERE email = '$email' AND userpswd = SHA('$password')";
        $result = mysqli_query($this->dbc, $query);
        if (mysqli_num_rows($result) == 0) {
            $msg = '邮箱地址或密码错误。';
            $msg_type = "error";
            return false;
        }

        $row = mysqli_fetch_array($result);
        $username = $row['username'];
        setcookie('username', $username, time() + (60 * 60 * 24 * 30));
        setcookie('mask', sha1($email.$username), time() + (60 * 60 * 24 * 30));
        setcookie('email', $email, time() + (60 * 60 * 24 * 30));
        header("Location: index.php");
        return true;
    }

    /**
     * Determines if current user likes the specified model.
     *
     * @param $model_id
     *
     * @return bool
     */
    public function current_user_like($model_id)
    {
        $query = "SELECT * FROM dimensions_likes WHERE model_id = '$model_id' AND user_id = '" . $this->id . "'";
        $result = mysqli_query($this->dbc, $query);
        return mysqli_num_rows($result) != 0;
    }

    /**
     * Determines if current user has favourited the specified model.
     *
     * @param $model_id
     *
     * @return bool
     */
    public function current_user_fav($model_id)
    {
        $query = "SELECT * FROM dimensions_favs WHERE model_id = '$model_id' AND user_id = '" . $this->id . "'";
        $result = mysqli_query($this->dbc, $query);
        return mysqli_num_rows($result) != 0;
    }
}