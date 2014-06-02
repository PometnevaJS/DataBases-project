<?php
require_once('../protected/functions.php');
checkUserLoggedIn();

if (isset($_GET['id']) && isUserAdmin()) {
    $dir = ALBUMS_SERVER_DIR . $_GET['id'];
    deleteDir($dir);
}
header("Location: /admin/index.php");