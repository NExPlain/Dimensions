<?php
/**
 * The template for displaying all models as a list.
 *
 * @author Renfei Song
 */

require_once "functions.php";
require_once "ModelController.class.php";

$model_controller = new ModelController();
$page = 1;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

get_header("浏览所有模型"); ?>

<h1 class="page-title">所有模型</h1>

<div class="all-models">
    <?php $model_controller->list_all_models($page); ?>
</div>
<img class="loading-indicator" src="lib/img/loading.gif" style="display: none">
<div class="page-navigation">
    <?php $model_controller->pagination($page) ?>
</div>

<script>
    activateAJAXPagination();
    function activateAJAXPagination() {
        $(".pages .page").click(function(e) {
            if ($(this).hasClass("current") == false) {
                e.preventDefault();
                var page = $(this).data('target');
                var model_url = "model-operations.php?op=request_models_by_page&page=" + page;
                var pagination_url = "model-operations.php?op=request_pagination&page=" + page;
                var model_data, pagination_data;
                beginLoading();
                $.when(
                        $.get(model_url, function(data) {
                            model_data = data;
                        }),
                        $.get(pagination_url, function(data) {
                            pagination_data = data;
                        })
                    ).then(function() {
                        setTimeout(function() {
                            $(".all-models").html(model_data);
                            $(".page-navigation").html(pagination_data);
                            endLoading();
                            activateAJAXPagination(); // re-attach event to the *new* elements
                        }, 2000);

                    });
            }
        });
    }

    function beginLoading() {
        $(".all-models").addClass("loading");
        var $loadingIndicator = $(".loading-indicator");
        var $siteContent = $('.site-content');
        $loadingIndicator.fadeIn(500);
        $loadingIndicator.css('left', $siteContent.width() / 2 - $loadingIndicator.width() / 2 + 'px');
        $loadingIndicator.css('top', $siteContent.height() / 2 - $loadingIndicator.height() / 2 + 'px');
    }

    function endLoading() {
        $(".loading-indicator").hide();
        $(".all-models").removeClass("loading");
    }

</script>

<?php
get_footer();