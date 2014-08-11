<?php
/**
 * The template for registering an account.
 *
 * @author Renfei Song
 */

require_once "functions.php";

get_header("注册");

$success = false;
if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];
    $success = $user_controller->register($email, $username, $password1, $password2, $msg, $msg_type);
}
?>

<?php if (!empty($msg)) echo "<div class='alert alert-".$msg_type."'>" . $msg . "</div>"; ?>

<?php if (!$success): ?>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
            <legend>注册账号</legend>
            <label for="email">Email</label>
            <input type="text" id="email" name="email" value="<?php if (!empty($email)) echo $email; ?>"><br>
            <label for="username">昵称</label>
            <input type="text" id="username" name="username" value="<?php if (!empty($username)) echo $username; ?>"><br>
            <label for="password1">密码</label>
            <input type="password" id="password1" name="password1"><br>
            <label for="password2">确认密码</label>
            <input type="password" id="password2" name="password2"><br>
            <input type="submit" class="btn btn-primary" value="注册" name="submit">
            <a href="browse.php" class="btn">返回</a>
        </fieldset>
    </form>
<?php endif; ?>

<?php
get_footer();