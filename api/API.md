# 登录

* 请求地址：`/api/login.php`
* 请求方法：GET

## 参数

* `email`：登录用邮箱，必需
* `password`：用户密码（明文），必需

## 返回数据

* 成功：用户 id，整数。例如
```
42
```
* 失败：固定值
```
false
```

# 上传模型

* 请求地址：`/api/upload.php`
* 请求方法：POST

## 参数

* `uploader_id`：用户 id，整数，必需
* `model_file`：模型文件，必需
* `cover_image`：封面图片文件，必需
* `title`：模型标题，必需
* `description`：模型描述，可选

## 返回数据

* 成功：JSON 字符串。其中`result`为`true`；`message`为刚刚上传的模型的 id。若`message`为`811`，则可引导用户访问`showcase.php?id=811`来查看刚刚上传的模型。例如
```
{"result":"true","message":"811"}
```
* 失败：JSON 字符串，其中`result`为`false`；`message`为错误信息。例如
```
{"result":"false","message":"title not found."}
```
