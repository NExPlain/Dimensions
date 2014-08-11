<?php
/**
 * Class UserController
 *
 * @author Renfei Song
 */
class UserController {

    private $dbc;

    public $logged_in;
    public $username;
    public $email;
    public $id;

    public $hashed_password;
    public $balance;
    public $join_date;
    public $upload_count;

    public function __construct()
    {
        $this->dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        mysqli_query($this->dbc, "SET NAMES UTF8");
        $this->logged_in = false;
        $this->id = 0;
    }

    public function __destruct()
    {
        mysqli_close($this->dbc);
    }

    /**
     * Identify current logged-in user.
     */
    public function init()
    {
        if (isset($_COOKIE['mask']) && isset($_COOKIE['username']) && isset($_COOKIE['email'])) {
            $query = "SELECT * FROM dimensions_users WHERE email = '".$_COOKIE['email']."'";
            $result = mysqli_query($this->dbc, $query);
            $row = mysqli_fetch_array($result);

            if (sha1($_COOKIE['email'].$_COOKIE['username']) == $_COOKIE['mask']) {
                $this->logged_in = true;
                $this->username = $_COOKIE['username'];
                $this->email = $_COOKIE['email'];
                $this->id = $row['id'];
                $this->balance = $row['balance'];
                $this->join_date = $row['joindate'];
                $this->hashed_password = $row['userpswd'];
                $query = "SELECT * FROM dimensions_models WHERE uploader_id = '".$this->id."'";
                $result = mysqli_query($this->dbc, $query);
                $this->upload_count = mysqli_num_rows($result);
            }
        }
    }

    /**
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
     * @see my-profile.php
     */
    public function list_models_uploaded()
    {
        echo "<div id=\"models-list\">";
        $query = "SELECT * FROM dimensions_models WHERE uploader_id = '".$this->id."'";
        $result = mysqli_query($this->dbc, $query);
        while ($row = mysqli_fetch_array($result)) {
            echo <<<HTML
            <div class="model-item">
                <a href="showcase.php?id={$row["id"]}">{$row["title"]}</a>
                <span class="model-options">
                    <a class="model-option edit-link" href="editor/index.php?id={$row["id"]}"><i class="icon icon-pencil"></i>编辑</a>
                    <a class="model-option del-link" href="model-operations.php?op=delete&model_id={$row["id"]}"><i class="icon icon-trash"></i>删除</a>
                </span>
            </div>
HTML;
        }
        echo "</div>";
    }

    /**
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
            $msg = '必须填写合法的用户名、密码才能登陆。';
            $msg_type = "error";
            return false;
        }

        $query = "SELECT * FROM dimensions_users WHERE email = '$email' AND userpswd = SHA('$password')";
        $result = mysqli_query($this->dbc, $query);
        if (mysqli_num_rows($result) == 0) {
            $msg = '用户名或密码错误。';
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
        $query = "SELECT * FROM dimensions_likes WHERE model_id = '$model_id' AND user_id = '".$this->id."'";
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
        $query = "SELECT * FROM dimensions_favs WHERE model_id = '$model_id' AND user_id = '".$this->id."'";
        $result = mysqli_query($this->dbc, $query);
        return mysqli_num_rows($result) != 0;
    }
}