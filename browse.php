<?php
/**
 * The template for displaying all models as a list.
 *
 * @author Renfei Song
 */

require_once "functions.php";
require_once "ModelController.class.php";

$model_controller = new ModelController();
get_header("浏览所有模型"); ?>

<h1 class="page-title">所有模型</h1>

<div class="all-models">
    <?php $model_controller->list_all_models(); ?>
</div>

<?php
get_footer();