<?php
require_once('../protected/functions.php');
checkUserLoggedIn();

if (isset($_GET['image']) && isset($_GET['album_id'])) {
    $image = ALBUMS_SERVER_DIR . '/' . $_GET['album_id'] . '/' . $_GET['image'];
    unlink($image);
    header("Location: " . $_SERVER['HTTP_REFERER']);
}
