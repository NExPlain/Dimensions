<?php
/**
 * Login API for Kinect Client
 *
 * @author Renfei Song
 */

require_once "../define.php";

$email = urldecode(@$_GET['email']);
$password = urldecode(@$_GET['password']);

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
mysqli_query($dbc, "SET NAMES UTF8");
$query = "SELECT * FROM dimensions_users WHERE email = '$email' AND userpswd = SHA('$password')";
$result = mysqli_query($dbc, $query);
if ($row = mysqli_fetch_array($result)) {
    echo $row['id'];
} else {
    echo "false";
}