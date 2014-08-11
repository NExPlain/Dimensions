<?php
/**
 * The template for log in page.
 *
 * @author Renfei Song
 */

require_once "functions.php";
require_once "UserController.class.php";

$user_controller = new UserController();

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $user_controller->login($email, $password, $msg, $msg_type);
}
/* Notice
 * If the login is successful, user will be redirected within the execution
 * of UserController's login() function, therefore get_header() function below
 * will never be invoked.
 */
get_header("登录"); ?>

<?php 
	if (!empty($msg)) echo "<div class='alert alert-".$msg_type."'>" . $msg . "</div>";
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset>
		<legend>登录</legend>
		<label for="email">用户名</label>
		<input type="text" id="email" name="email" value="<?php if (!empty($email)) echo $email; ?>"><br>
		<label for="password">密码</label>
		<input type="password" id="password" name="password"><br>
		<input type="submit" class="btn btn-primary" value="登录" name="submit">
		<a href="browse.php" class="btn">返回</a>
	</fieldset>
</form>

<?php
get_footer();