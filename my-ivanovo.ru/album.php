<?php
require_once('protected/functions.php');

$id = 0;
if (isset($_GET['id'])) $id = $_GET['id'];

?>

<?php require_once('protected/site_header.php'); ?>

    <div class="content">
        <?php if ($id != 0 && albumExists($id)): ?>

            <a class="button" id="back-button" href="/">&larr; Назад</a>

            <h1><?php echo getAlbumName($id); ?>
                <span><?php echo getAlbumDescription($id) ?></span>
            </h1>

            <?php showAlbumImages($id); ?>
        <?php else: ?>
            <h1>ЗАПРАШИВАЕМОГО АЛЬБОМА НЕ СУЩЕСТВУЕТ</h1>
        <?php endif; ?>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".fancybox").fancybox({
                openEffect: 'none',
                closeEffect: 'none',
                prevEffect: 'none',
                nextEffect: 'none'
            });
        });
    </script>
<?php require_once('protected/footer.php'); ?>