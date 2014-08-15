<?php
/**
 * The template for uploading a new model.
 *
 * @author Renfei Song
 */

require_once "functions.php";

get_header("上传模型"); ?>

<h1 class="page-title">上传模型</h1>

<ul class="upload-mode-selector">
    <li class="shared current">
        <span class="mode-icon shared-icon glyphicon glyphicon-cloud"></span>
        <strong class="mode">免费模型</strong>
        所有用户均可浏览并下载
    </li>
    <li class="paied">
        <span class="mode-icon paid-icon glyphicon glyphicon-credit-card"></span>
        <strong class="mode">收费模型</strong>
        只有购买模型的用户可浏览或下载
    </li>
</ul>

<div class="upload-area">
    <form class="form upload-form" action="handle-upload.php" enctype="multipart/form-data" method="POST" accept-charset="UTF-8">
        <div class="form-field">
            <label class="field-label" for="title">模型名称</label>
            <input type="text" class="text-input" name="title" id="title">
        </div>
        <div class="form-field">
            <label class="field-label" for="cover_image">封面图片</label>
            <input type="file" class="file-selector" name="cover_image" id="cover_image" accept="image/*">
            <input type="text" class="file-confirm cover_image disabled" disabled>
        </div>
        <div class="form-field">
            <label class="field-label" for="model_file">模型</label>
            <input type="file" class="file-selector" name="model_file" id="model_file">
            <input type="text" class="file-confirm model_file disabled" disabled>
        </div>
        <div class="form-field">
            <label class="field-label" for="textures">贴图和其他<span class="optional">（可选）</span></label>
            <input type="file" class="file-selector" name="textures[]" id="textures" multiple>
            <input type="text" class="file-confirm textures disabled" disabled>
            <p class="field-note">您可以一次选择多个文件。</p>
        </div>
        <div class="paid-model-fields" style="display: none">
            <hr>
            <div class="form-field">
                <label class="field-label" for="images">预览图</label>
                <input type="file" class="file-selector" name="images[]" id="images" accept="image/*" multiple>
                <input type="text" class="file-confirm images disabled" disabled>
                <p class="field-note">提示：您最多可上传 5 张预览图。</p>
            </div>
            <div class="form-field">
                <label class="field-label" for="price">定价</label>
                <input type="text" class="text-input" name="price" id="price">
            </div>
        </div>
        <hr>
        <div class="form-field">
            <label class="field-label" for="tags">添加一些标签<span class="optional">（可选）</span></label>
            <select name="tags[]" id="tags" multiple placeholder="添加一些标签...">
                <option value="">添加一些标签...</option>
            </select>
        </div>
        <div class="form-field">
            <label class="field-label" for="license">添加一个许可协议<span class="optional">（可选）</span></label>
            <select name="license" id="license" placeholder="选择一个...">
                <option value="">选择一个协议...</option>
                <option value="1">CreativeCommons BY</option>
                <option value="2">CreativeCommons BY-ND</option>
                <option value="3">CreativeCommons BY-SA</option>
                <option value="4">CreativeCommons BY-NC</option>
                <option value="5">CreativeCommons BY-NC-ND</option>
                <option value="6">CreativeCommons BY-NC-SA</option>
            </select>
        </div>
        <div class="form-field">
            <label class="field-label" for="description">简要介绍<span class="optional">（可选）</span></label>
            <textarea name="description" id="description" rows="4"></textarea>
        </div>
        <hr>
        <div class="form-field">
            <input type="submit" class="button button-green" name="submit" value="立即上传模型">
        </div>
        <input type="hidden" name="uploader_id" value="<?php echo $user_controller->id ?>">
    </form>
</div>

<script>
    $(".upload-mode-selector li").click(function() {
        if ($(this).hasClass("current") == false) {
            $(".upload-mode-selector li.current").removeClass("current");
            $(this).addClass("current");
            if ($(this).hasClass("paied")) {
                $(".paid-model-fields").slideDown();
            } else {
                $(".paid-model-fields").slideUp(400, function() {
                    $("#price").val("");
                    $("#images").val("");
                    $(".file-confirm.images").val("");
                });
            }
        }
    });
    $(".file-selector").on('change', function(event) {
        var files = event.originalEvent.target.files;
        var output = files[0].name;
        for (var i = 1, f; f = files[i]; ++i) {
            output = output + ", " + f.name;
        }
        $(".file-confirm." + $(this).attr('id')).val(output);
    });
</script>
<script src="lib/core/selectize/selectize.min.js"></script>
<script>
    $("#license").selectize();
    $("#tags").selectize({
        plugins: ['remove_button','restore_on_backspace'],
        persist: false,
        create: true
    });
</script>
<link href="lib/core/selectize/selectize.css" rel="stylesheet" media="screen">
<?php
get_footer();