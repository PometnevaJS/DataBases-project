<?php
require_once('../protected/functions.php');
checkUserLoggedIn();
show403ErrorIfUserIsNotAdmin();

$id = 0;
if (isset($_GET['id'])) $id = $_GET['id'];

$newName = '';
$newDescription = '';
$error = '';

if (isset($_POST['description'])) $newDescription = $_POST['description'];
if (isset($_POST['name'])) $newName = $_POST['name'];

if ($newName != '' && $id != null) {
    setAlbumName($id, $newName);
    setAlbumDescription($id, $newDescription);
    header("Location: album.php?id=" . $id);
} else if (!empty($_POST)) {
    $error = 'Вы не написали название альбома!';
}

require_once('../protected/admin_header.php');
?>
    <div class="content">
        <h1><?php echo getAlbumName($id); ?>
            <span>редактирование названия и описания альбома</span>
        </h1>

        <?php if ($error != ''): ?>

            <div class="error-message">
                <h1>Ошибка!</h1>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form id="contact" method="post" action="edit_album.php?id=<?php echo $id; ?>">

            <label for="name">Название</label>
            <input type="text" id="name" name="name" value="<?php echo getAlbumName($id); ?>">

            <label for="description">Описание</label>
            <textarea rows="4" cols="50" id="description" name="description"><?php echo getAlbumDescription($id); ?></textarea>

            <input class="button" type="submit" name="submit" id="submit" value="Сохранить изменения">

        </form>
    </div>

<?php require_once('../protected/footer.php'); ?>