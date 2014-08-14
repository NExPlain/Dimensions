<?php
/**
 * The template for displaying user's profile page.
 *
 * @author Renfei Song
 */

require_once "functions.php";
require_once "UserController.class.php";

// Get desired user id

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $user_controller = new UserController();
    $id = $user_controller->current_user_id();
    if ($id == 0) {
        exit;
    }
}

// Load the user

$target_user = new UserController();
$target_user->load($id);
if ($target_user->has_user == false) {
    exit;
}
get_header($target_user->username . " - Dimensions");
$self = $user_controller->has_user && $user_controller->id == $id;
?>

<img class="avatar" src="<?php echo $target_user->avatar_url ?>">
<div class="user-profile">

    <h1 class="name"><?php echo $target_user->username ?></h1>
    <p class="bio"><?php echo $target_user->description ?></p>
    <ul class="details">
        <li class="join-date"><span class="icon glyphicon glyphicon-calendar"></span><?php echo $target_user->join_date ?> 加入</li>
        <li class="upload-count"><span class="icon glyphicon glyphicon-cloud-upload"></span><?php echo $target_user->upload_count ?> 上传</li>
    </ul>
</div>

<div class="user-main">
    <div class="models">
    <?php $target_user->list_models_uploaded($self) ?>
    </div>
    <div class="sidebar">
        <?php if ($self): ?>
        <div class="widget account">
            <h3 class="widget-title">我的余额</h3>
            <div class="balance"><?php echo $target_user->balance ?> 元</div>
            <div class="options">
                <button class="button button-white">充值</button>
                <button class="button button-green">加入会员</button>
            </div>
        </div>
        <?php endif; ?>
        <div class="widget stats">
            <h3 class="widget-title">已获得</h3>
            <ul>
                <li class="views"><span class="icon glyphicon glyphicon-eye-open"></span><?php echo $target_user->views_received ?> 次浏览</li>
                <li class="likes"><span class="icon glyphicon glyphicon-heart"></span><?php echo $target_user->likes_received ?> 次喜欢</li>
                <li class="favs"><span class="icon glyphicon glyphicon-bookmark"></span><?php echo $target_user->favs_received ?> 次收藏</li>
                <li class="downloads"><span class="icon glyphicon glyphicon-download-alt"></span><?php echo $target_user->downloads_received ?> 次下载</li>
            </ul>
        </div>
    </div>
</div>

<?php
get_footer();