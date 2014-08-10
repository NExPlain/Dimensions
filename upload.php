<?php
/**
 * The template for uploading a new model.
 *
 * @author Renfei Song
 */
require_once "functions.php";
require_once "ModelController.class.php";
get_header("上传模型");

$model_controller = new ModelController();
$show_form = true;
if ($user_controller->logged_in == false) {
    exit;
}

if (isset($_POST["submit"])) {
    $model_controller->upload_model($_POST["title"], $user_controller->id, $_POST["model-name"],
        $_POST["scale"], ($_POST["isprivate"] == "on" ? 1 : 0), $_POST["description"]);
    $msg = "上传成功。";
    $msg_type = "success";
    $show_form = false;
}

if (!empty($msg)):
    ?>

    <div class="alert alert-<?= $msg_type ?>">
        <?php echo $msg ?>
    </div>

<?php
endif;
if ($show_form):
    ?>

    <script src="lib/util/dropzone.js"></script>
    <link href="lib/util/dropzone.css" rel="stylesheet" media="screen">

    <script>
        function validateNumber() {
            if (isNaN($("#scale").val())) {
                $("#error1").css({"display":"inline-block"});
                $("#tip1").css({"color":"#b94a48"});
                $("#tip1").css({"display":"inline-block"});
            } else {
                $("#tip1").css({"color":"#595959"});
                $("#error1").css({"display":"none"});
            }
        }
        function validateInput() {
            if ($("#title").val()!="" && $("#author").val()!="" && $("#model-name").val()!="") {
                $("#upload").removeClass("disabled").addClass("btn-primary").removeAttr("disabled");
            } else {
                $("#upload").removeClass("btn-primary").addClass("disabled").attr("disabled","disabled");
            }
        }
    </script>

    <h3>上传模型</h3>
    <div id="basic-info">
        <div class="well" style="width:500px;">
            <form class="form-horizontal" id="model-upload" action="<?=$_SERVER['PHP_SELF']?>" method="post" style="margin-bottom:0;">
                <div class="control-group">
                    <label class="control-label" for="title">标题</label>
                    <div class="controls">
                        <input type="text" name="title" id="title" onkeyup="validateInput();">
                    </div>
                </div><!-- title -->
                <div class="control-group">
                    <label class="control-label" for="scale">默认缩放比</label>
                    <div class="controls">
                        <input type="text" name="scale" id="scale" onkeyup="validateNumber();" value="1.0"><span class="label label-important" id="error1" style="display:none;">ERROR</span>
                        <span class="help-block text-error" id="tip1" style="margin-top:10px;">请输入数字，例如 <code>1.0</code></span>
                    </div>
                </div><!-- scale -->
                <div class="control-group">
                    <label class="control-label" for="files-model">模型文件</label>
                    <div class="controls">
                        <div class="dropz" id="files-model"></div>
                    </div>
                </div><!-- model -->
                <div class="control-group">
                    <label class="control-label" for="scale">贴图<br>(可选)</label>
                    <div class="controls">
                        <div class="dropz" id="files-dependency"></div>
                    </div>
                </div><!-- dependency -->
                <div class="control-group">
                    <label class="control-label" for="description">描述<br>(可选)</label>
                    <div class="controls">
                        <textarea name="description" id="description" rows="4" style="width: 306px;"></textarea>
                    </div>
                </div><!-- description -->
                <div class="control-group">
                    <div class="controls">
                        <label class="checkbox"><input type="checkbox" name="isprivate" id="isprivate"> 不公开这个模型。</label>
                    </div>
                </div><!-- private option -->
                <input type="hidden" id="model-name" name="model-name">
                <input class="btn btn-block btn-large disabled" type="submit" name="submit" value="Submit" id="upload" disabled >
            </form>
        </div>
    </div>

    <script>
        $("#files-model").dropzone({
            url: "handle-upload.php",
            addRemoveLinks: true,
            dictRemoveFile: "x",
            dictCancelUpload: "x",
            maxFilesize: 128,
            maxFiles: 1,
            dictMaxFilesExceeded: "You may not upload more than 1 model file.",
            acceptedFiles: ".js",// ".dae,.obj,.js",
            dictInvalidFileType: "Model type not supported.",
            init: function() {
                this.on("removedfile", function(file) {
                    if ($("#model-name").val() == file.name) {
                        $("#model-name").val("");
                        validateInput();
                    }
                });
                this.on("success", function(file) {
                    $("#model-name").val(file.name);
                    validateInput();
                });
            }
        });
        $("#files-dependency").dropzone({
            url: "handle-upload.php",
            addRemoveLinks: true,
            dictRemoveFile: "x",
            dictCancelUpload: "x",
            maxFilesize: 128,
            maxFiles: 10,
            dictMaxFilesExceeded: "You may not upload anymore files.",
            acceptedFiles: "image/*",
            dictInvalidFileType: "You can only upload image files."
        });
    </script>

<?php
endif;
get_footer();