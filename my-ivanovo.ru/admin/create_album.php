<?php
require_once('../protected/functions.php');
checkUserLoggedIn();
show403ErrorIfUserIsNotAdmin();

$name = '';
$description = '';
$error = '';

if (isset($_POST['description'])) $description = $_POST['description'];
if (isset($_POST['name'])) $name = $_POST['name'];

if ($name != '') {
    $id = createAlbum($name, $description);
    session_start();
    header("Location: album.php?id=" . $id);
} else if (!empty($_POST)) {
    $error = 'Вы забыли написать название альбома!';
}
require_once('../protected/admin_header.php');
?>
    <div class="content">
        <h1>Создать альбом</h1>

        <?php if ($error != ''): ?>
            <div class="error-message">
                <h1>Ошибка!</h1>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form id="contact" method="post" action="create_album.php">

            <label for="name">Название</label>
            <input type="text" id="name" name="name">

            <label for="description">Описание</label>
            <textarea rows="4" cols="50" id="description" name="description"></textarea>

            <input class="button" type="submit" name="submit" id="submit" value="Создать альбом">

        </form>
    </div>

<?php require_once('../protected/footer.php'); ?>