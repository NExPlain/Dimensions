<?php
	require_once('define.php');
	
	if (($_GET['fr'] != "myprof" && $_GET['fr'] != "mymodd") || !isset($_GET['id']) || !isset($_COOKIE['user']) || !isset($_COOKIE['mask'])) {
		abort_deletion();
	}
	
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("Cannot connect to database.");	
	
	$mask = $_COOKIE['mask'];
 	$user = $_COOKIE['user'];
	$query = "select * from dimensions_users where username = '$user'";
	$result = mysqli_query($dbc, $query);
	$row = mysqli_fetch_array($result);
        
	if (sha1($user.COOKIE_SECURE) == $mask) { 	
		$current_userid = $row['id'];
	} else {
		abort_deletion();
	}
	
	$id = $_GET['id'];
	$query = "select uploader from dimensions_models where id = '$id'";
	$result = mysqli_query($dbc, $query) or die("Cannot execute database query.");
	$row = mysqli_fetch_array($result);
	if ($row['uploader'] == $current_userid) {
		$query = "delete from dimensions_models where id = '$id'";
		mysqli_query($dbc, $query) or die("Cannot execute database query.");
		$tar_url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $_GET['fr'] . '.php';
     		header('Location: ' . $tar_url);
	} else {
		abort_deletion();
	}
	
	function abort_deletion() {
		$p = $_GET['fr'];
		if ($_GET['fr'] != "myprof" && $_GET['fr'] != "mymodd") {
			$p = "myprof";
		}
		$tar_url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $p . '.php';
     		header('Location: ' . $tar_url);
     		exit;
	}

?>