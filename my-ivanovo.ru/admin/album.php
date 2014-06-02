<?php
require_once('../protected/functions.php');
checkUserLoggedIn();

$id = 0;
if (isset($_GET['id'])) $id = $_GET['id'];

?>

<?php require_once('../protected/admin_header.php'); ?>
<div class="content">
    <?php if ($id != 0 && albumExists($id)): ?>

        <h1><?php echo getAlbumName($id); ?>
            <span>Редактирование альбома</span>
        </h1>

        <div class="button-wrapper">
            <a class="button" href="#" id="add-photos-button">Добавить фотографии</a>
            <?php if (isUserAdmin()): ?>
                <a class="button" href="edit_album.php?id=<?php echo $id; ?>" id="edit-album-button">
                    Редактировать название и описание альбома
                </a>
                <a class="button" href="delete_album.php?id=<?php echo $id; ?>" id="delete-album-button"
                   onClick="return window.confirm('Вы уверены, что хотите удалить альбом?')">
                    Удалить альбом
                </a>
            <?php endif; ?>
        </div>

        <?php showAlbumImages($id, 'edit'); ?>

        <form action="load_images.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data"
              style="display: none;">
            <label for="files">Изображения:</label>
            <input name="upload[]" type="file" multiple="multiple" id="files"/>
            <input type="submit" value="Отправить" id="load-images-button"/>
        </form>
    <?php else: ?>
        <h1>ЗАПРАШИВАЕМОГО АЛЬБОМА НЕ СУЩЕСТВУЕТ</h1>
    <?php endif; ?>
</div>

<?php require_once('../protected/footer.php'); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $(".fancybox").fancybox({
            openEffect: 'none',
            closeEffect: 'none',
            prevEffect: 'none',
            nextEffect: 'none'
        });
    });

    $('#add-photos-button').click(function () {
        $('input[type="file"]').click();
    });

    $("#files").change(function () {
        $('#load-images-button').click();
    });
</script>
