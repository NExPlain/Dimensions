<?php
	if (isset($_COOKIE['user'])) {
		setcookie('user', '', time() - 3600);
	}
	if (isset($_COOKIE['mask'])) {
		setcookie('mask', '', time() - 3600);
	}
	
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/dimensions/browse.php';
    header('Location: ' . $home_url);
?>