<?php
/**
 * The log-out script (non-presentative)
 *
 * @author Renfei Song
 */
setcookie('username', '', time() - 3600);
setcookie('mask', '', time() - 3600);
setcookie('email', '', time() - 3600);
header("Location: index.php");