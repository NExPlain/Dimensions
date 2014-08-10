<?php

$model = $_GET['model'];
$user = $_GET['user'];
if(!isset($model)||!isset($user)||$model==""||$user==""){echo "错误：您尚未登录或令牌丢失。";exit;}
require_once('define.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("错误：服务器连接失败。");

$result=mysqli_query($dbc,"select * from models where id = '$model'")or die("错误：服务器连接失败。");
$row=mysqli_fetch_array($result);
$stamp=$row['stamp'];
$check=mysqli_query($dbc,"select * from models where stamp='$stamp' and uploader='$user'");
if(mysqli_num_rows($check)!=0){echo "错误：您已经拥有这个模型。";exit;}

if($_GET['do']=='1'){$query = "insert into models (title, author, uploader, modelfile, stamp, scale, isprivate, description) ".
"values ('".$row['title']."','".$row['author']."','".$user."','".$row['modelfile']."','".$row['stamp']."','".$row['scale']."',1,'".$row['description']."')";
mysqli_query($dbc, $query)or die("错误：服务器连接失败。");
echo "success";}

?>