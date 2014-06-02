<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Моё Иваново</title>
    <script type="text/javascript" src="fancybox/lib/jquery-1.10.1.min.js"></script>
    <script type="text/javascript" src="fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

    <!-- Add fancyBox main JS and CSS files -->
    <script type="text/javascript" src="fancybox/source/jquery.fancybox.js?v=2.1.5"></script>
    <link rel="stylesheet" type="text/css" href="fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen"/>

    <!-- Add Button helper (this is optional) -->
    <link rel="stylesheet" type="text/css" href="fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5"/>
    <script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

    <!-- Add Thumbnail helper (this is optional) -->
    <link rel="stylesheet" type="text/css" href="fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7"/>
    <script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
    <!-- Add Media helper (this is optional) -->
    <script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
<div class="navbar-wrapper">
    <div class="navbar">
        <div class="brand">
            <a href="/">МОЁ ИВАНОВО</a>
        </div>
        <ul class="nav">
            <?php if (!isUserLoggedIn()): ?>
                <li><a href="/signup.php">Регистрация</a></li>
                <li><a href="/login.php">Войти</a></li>
            <?php else: ?>
                <li><a href="/admin">Вернуться в админ. раздел сайта</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>