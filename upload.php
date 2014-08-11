<?php
/**
 * The template for uploading a new model.
 *
 * @author Renfei Song
 */

require_once "functions.php";

get_header("上传模型"); ?>

<h1 class="page-title">上传模型</h1>

<div class="upload-area">
    <form method="POST" action="handle-upload.php" enctype="multipart/form-data" class="form-horizontal">
        <div class="control-group">
            <label class="control-label" for="title">标题</label>
            <div class="controls">
                <input type="text" name="title" id="title">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="scale">默认缩放比</label>
            <div class="controls">
                <input type="text" name="scale" id="scale">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="cover_image">封面图片</label>
            <div class="controls">
                <input type="file" name="cover_image" id="cover_image" accept="image/*">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="images">预览图<br>（1-5张）</label>
            <div class="controls">
                <input type="file" name="images[]" id="images" accept="image/*" multiple>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="model_file">模型文件</label>
            <div class="controls">
                <input type="file" name="model_file" id="model_file">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="textures">贴图文件</label>
            <div class="controls">
                <input type="file" name="textures[]" id="textures" multiple>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="price">定价</label>
            <div class="controls">
                <input type="text" name="price" id="price">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="description">描述</label>
            <div class="controls">
                <textarea name="description" id="description" rows="4"></textarea>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <input type="submit" class="btn" name="submit" value="提交">
            </div>
        </div>
        <input type="hidden" name="uploader_id" value="<?php echo $user_controller->id ?>">
    </form>
</div>

<?php
get_footer();