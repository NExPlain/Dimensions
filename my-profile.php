<?php
/**
 * The template for displaying user's profile page.
 *
 * @author Renfei Song
 */
require_once "functions.php";
get_header("个人资料");

if ($user_controller->logged_in == false) {
    exit;
}

if (isset($_POST['changepw'])) {
    $user_controller->change_password($_POST['newpw'], $_POST['oldpw'], $msg, $msg_type);
}

function enqueue_item($title, $content, $class = "") {
    echo "<div class=\"control-group ".$class."\"><label class=\"control-label\">".$title."</label><div class=\"controls\">".$content."</div></div>";
}
?>
<script>
    function changepw() {
        $(".changepw .controls").html("<form method='post' action='my-profile.php' class='inline-form'><div class='form-line'><label for='oldpw'>当前密码</label><input type='password' name='oldpw' id='oldpw'></div><div class='form-line'><label for='newpw'>新密码</label><input type='password' name='newpw' id='newpw'></div><div class='form-line'><button type='submit' class='btn' name='changepw'>修改密码</button></div></form>");
        $(".changepw .controls").addClass("nesting-form");
    }
</script>

<h1 class="page-title">个人档案</h1>

<?php if (!empty($msg)): ?>
    <div class="alert alert-<?php echo $msg_type?> fade in"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $msg?></div>
<?php endif; ?>

<section class="account">
    <h2 class="section-title">账户</h2>
    <div class="form-horizontal">
        <?php enqueue_item("用户名", $user_controller->username) ?>
        <?php enqueue_item("Email", $user_controller->email) ?>
        <?php enqueue_item("密码", "<a class=\"clickable\" onclick=\"changepw();\">修改密码</a>", "changepw"); ?>
        <?php enqueue_item("账户余额", $user_controller->balance." 点") ?>
        <?php enqueue_item("加入时间", $user_controller->join_date) ?>
        <?php enqueue_item("上传数量", $user_controller->upload_count) ?>
    </div>
</section>

<section class="models">
    <h2 class="section-title">我的模型</h2>
    <?php $user_controller->list_models_uploaded() ?>
</section>

<?php
get_footer();