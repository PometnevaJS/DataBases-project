<?php
require_once('../protected/functions.php');
checkUserLoggedIn();
uploadImagesToAlbum($_GET['id']);
header("Location: " . $_SERVER['HTTP_REFERER']);