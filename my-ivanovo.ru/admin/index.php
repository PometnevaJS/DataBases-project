<?php
require_once('../protected/functions.php');
checkUserLoggedIn();
?>
<?php require_once('../protected/admin_header.php'); ?>
    <div class="content">
        <h1>УПРАВЛЕНИЕ АЛЬБОМАМИ</h1>

        <?php if (isUserAdmin()): ?>
            <div class="button-wrapper">
                <a class="button" href="create_album.php">Создать альбом</a>
            </div>
        <?php endif; ?>

        <?php showAlbumCovers('edit'); ?>

    </div>

<?php require_once('../protected/footer.php'); ?>