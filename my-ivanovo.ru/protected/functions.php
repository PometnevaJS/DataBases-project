<?php
require_once('config.php');

/**
 * Возвращает уникальное название для будущего файла.
 * @param $albumId
 * @param $ext расширение файла
 * @return string
 */
function getUniqueFileName($albumId, $ext)
{
    $albumDir = ALBUMS_SERVER_DIR . $albumId . '/';
    $images = getAlbumImages($albumId);

    $file = end($images);
    $fileInfo = pathinfo($file);
    $filename = $fileInfo['filename']; // Получаем название файла без расширения. Н-р, файл - photo.jpg, filename - photo.

    $uniqueName = (intval($filename) + 1) . '.' . $ext; // Получаем уникальное название файла с расширением $ext.

    return $albumDir . $uniqueName; // Возращаем полное имя будущего файла.
}

/**
 * Возращает уникальное название для будущего альбома
 * (папки с фотографиями).
 * @return string
 */
function getUniqueAlbumName()
{
    $albumIds = getAlbumIds();
    $albumName = ALBUMS_SERVER_DIR . (end($albumIds) + 1);

    return $albumName;
}

/**
 * Создает альбом на сервере сайта
 * (папку с файлами name.txt и description.txt).
 * В файле name.txt хранится название альбома, которое будет отображаться на сайте.
 * В файле description.txt хранится описание альбома.
 * @param $name
 * @param string $description
 * @return mixed
 */
function createAlbum($name, $description = '')
{
    $albumName = getUniqueAlbumName(); // Получаем название альбома.
    $nameFile = $albumName . '/' . 'name.txt'; // Получаем полный путь до файла name.txt
    $descriptionFile = $albumName . '/' . 'description.txt'; // Получаем полный путь до файла description.txt

    // Создаем папку с названием $albumName.
    mkdir($albumName);
    // В файл name.txt записываем содержимое переменной $name.
    file_put_contents($nameFile, $name);
    // В файл description.txt записываем содержимое переменной $description.
    file_put_contents($descriptionFile, $description);

    return str_replace(ALBUMS_SERVER_DIR, '', $albumName);
}

/**
 * Получаем все фотографии альбома с ID $albumId.
 * Например:
 * getAlbumImages(6);
 * Вернет примерно такой массив:
 * Array
 * (
 *  [0] => /albums/6/1.jpg
 *  [1] => /albums/6/2.jpg
 *  [2] => /albums/6/3.jpg
 *  [3] => /albums/6/4.jpg
 *  [4] => /albums/6/5.png
 * )
 * @param $albumId
 * @return array
 */
function getAlbumImages($albumId)
{
    $pattern = ALBUMS_SERVER_DIR . $albumId . '/*.{jpg,gif,png}';
    // Получаем все пути к альбомам по шаблону 'albums/*', где * - любая папка или файл.
    $images = glob($pattern, GLOB_BRACE);
    natsort($images); // Сортируем фотки.

    for ($i = 0; $i < count($images); $i++) {
        // $_SERVER['DOCUMENT_ROOT'] равен 'D:/OpenServer/domains/my-ivanovo.ru'
        // Удаляем $_SERVER['DOCUMENT_ROOT'] из каждого пути к какой-либо фотке, чтобы ссылки на фотки были правльными.
        $images[$i] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $images[$i]);
    }

    return $images;
}

/**
 * Получает все идентификаторы существующих альбомов.
 * @return array
 */
function getAlbumIds()
{
    $paths = scandir(ALBUMS_SERVER_DIR);
    $paths = array_diff($paths, array('.', '..'));

    return $paths;
}

/**
 * Получает ссылку на обложку альбома.
 * Например: getAlbumCover(2) вернет строку '/albums/6/1.jpg'.
 * @param $albumId
 * @return string
 */
function getAlbumCover($albumId)
{
    $images = getAlbumImages($albumId);

    return reset($images); // Получаем первый элемент с массива $images.
}

/**
 * Показывает все обложки альбомов.
 * @param string $mode режим отображения. Их всего два: edit и common.
 * По умолчанию, режим common.
 * В режиме edit обложки альбомов  имеют кнопки 'Удалить'.
 */
function showAlbumCovers($mode = 'common')
{
    $albumIds = getAlbumIds();
    $covers = array(); // Массив обложек альбомов.
    $names = array(); // Массив имен альбомов.

    foreach ($albumIds as $albumId) {
        $covers[] = getAlbumCover($albumId);
        $names[] = getAlbumName($albumId);
    }

    // array_chunk разбивает массив на части. Смотри пояснение: http://www.php.net/manual/ru/function.array-chunk.php
    // В моем случаем разбивает на подмассивы, в каждом подмассиве не больше 4-х элементов.
    $albumIdsChunks = array_chunk($albumIds, 4); // [ [1, 2, 3, 5] [6] ]
    $coversChunks = array_chunk($covers, 4);
    $namesChunks = array_chunk($names, 4);

    for ($i = 0; $i < count($coversChunks); $i++) {
        showAlbumCoversRow($albumIdsChunks[$i], $coversChunks[$i], $namesChunks[$i], $mode);
    }
}

/**
 * Показывает ряд обложек альбомов. Макс. количество обложек в ряду - 4.
 * @param $ids идентификаторы альбомов
 * @param $images ссылки на фотографии
 * @param $names названия альбомов
 * @param string $mode режим отображения
 */
function showAlbumCoversRow($ids, $images, $names, $mode = 'common')
{
    $count = count($images); // Получаем количество обложек.

    echo '<div class="row">';

    for ($i = 0; $i < $count; $i++) {
        $id = $ids[$i];
        $image = $images[$i];
        $name = $names[$i];
        if ($mode == 'edit')
            showAlbumCoverInEditMode($id, $image, $name);
        else {
            showAlbumCover($id, $image, $name);
        }
    }

    echo '</div>';
}

/**
 * Выводит html-код обложки альбома в режиме редактирования.
 * Например такой:
 * <a href="/admin/album.php?id=1">
 *      <div class="grid col-4">
 *          <div class="cover">
 *              <img src="/albums/1/1.jpg">
 *          </div>
 *      <p>Наш любимый город</p>
 *      </div>
 * </a>
 * @param $albumId идентификатор альбома
 * @param $image ссылка на фотографию
 * @param $name название альбома
 */
function showAlbumCoverInEditMode($albumId, $image, $name)
{
    echo '<a href="/admin/album.php?id=' . $albumId . '">
                 <div class="grid col-4">';

    if ($image == null) echo '<div class="cover no-photo"><p>нет фотографий</p></div>';
    else echo '<div class="cover"><img src="' . $image . '"></div>';

    echo '<p>' . $name . '</p>
                </div>
          </a>';
}

/**
 * Выводит html-код обложки альбома.
 * Например такой:
 * <a href="/album.php?id=1">
 *      <div class="grid col-4">
 *          <div class="cover">
 *              <img src="/albums/1/1.jpg">
 *          </div>
 *      <p>Наш любимый город</p>
 *      </div>
 * </a>
 * @param $albumId идентификатор альбома
 * @param $image ссылка на фотографию
 * @param $name название альбома
 */
function showAlbumCover($albumId, $image, $name)
{
    echo '<a href="/album.php?id=' . $albumId . '">';
    echo '<div class="grid col-4" id="album-' . $albumId . '">';
    // Если нет обложки у альбома, то...
    if ($image == null) echo '<div class="cover no-photo"><p>нет фотографий</p></div>';
    else echo '<div class="cover"><img src="' . $image . '"></div>';

    echo '<p>' . $name . '</p>
               </div>
         </a>';
}

/**
 * Показывает фотографии альбома.
 * @param $albumId идентификатор альбома
 * @param string $mode режим отображения
 */
function showAlbumImages($albumId, $mode = 'common')
{
    $images = getAlbumImages($albumId);
    $imagesChunks = array_chunk($images, 4);

    foreach ($imagesChunks as $chunk) {
        showImagesRow($albumId, $chunk, $mode);
    }
}

/**
 * Показывает ряд фотографий. Макс. количество фотографий в одном ряду - 4.
 * @param $albumId
 * @param $images
 * @param string $mode
 */
function showImagesRow($albumId, $images, $mode = 'common')
{
    $count = count($images);

    echo '<div class="row">';

    for ($i = 0; $i < $count; $i++) {
        $imagePath = $images[$i];

        if ($mode == 'edit')
            showImageInEditMode($albumId, $imagePath);
        else
            showImage($imagePath);
    }

    echo '</div>';
}

/**
 * Выводит html-код фотографии альбома в режиме отображения edit.
 * @param $albumId идентификатор альбома
 * @param $imagePath путь к фотографии
 */
function showImageInEditMode($albumId, $imagePath)
{
    $imageName = basename($imagePath);
    echo '<div class="grid col-4">
                <div class="cover">
                    <a class="fancybox" rel="group" href="' . $imagePath . '">
                        <img src="' . $imagePath . '">
                    </a>
                </div>
                <a href="delete_image.php?album_id=' . $albumId . '&image=' . $imageName . '" class="button">Удалить</a>
          </div>';
}

/**
 * Выводит html-код фотографии альбома.
 * @param $imagePath путь к фотографии
 */
function showImage($imagePath)
{
    echo '<div class="grid col-4">
                <div class="cover">
                    <a class="fancybox" rel="group" href="' . $imagePath . '">
                        <img src="' . $imagePath . '">
                    </a>
                </div>
          </div>';
}

/**
 * Получает название альбома.
 * @param $albumId идентификатор альбома
 * @return string
 */
function getAlbumName($albumId)
{
    $albumNameFile = ALBUMS_SERVER_DIR . $albumId . '/name.txt';
    $name = file_get_contents($albumNameFile);

    return $name;
}

/**
 * Получает описание альбома.
 * @param $albumId идентификатор альбома
 * @return string
 */
function getAlbumDescription($albumId)
{
    $albumDescriptionFile = ALBUMS_SERVER_DIR . $albumId . '/description.txt';
    $description = file_get_contents($albumDescriptionFile);

    return $description;
}

/**
 * Устанавливает название альбома.
 * @param $albumId идентификатор альбома
 * @param $name
 */
function setAlbumName($albumId, $name)
{
    $albumNameFile = ALBUMS_SERVER_DIR . $albumId . '/name.txt';
    file_put_contents($albumNameFile, $name);
}

/**
 * Устанавливает описание альбома.
 * @param $albumId идентификатор альбома
 * @param $description
 */
function setAlbumDescription($albumId, $description)
{
    $albumDescriptionFile = ALBUMS_SERVER_DIR . $albumId . '/description.txt';
    file_put_contents($albumDescriptionFile, $description);
}

/**
 * Проверяет, существует ли альбом с идентификатором $albumId.
 * @param $albumId идентификатор альбома
 * @return bool
 */
function albumExists($albumId)
{
    $albumDir = ALBUMS_SERVER_DIR . $albumId;

    return file_exists($albumDir) || is_dir($albumDir);
}

/**
 * Загружает фотографии в альбом.
 * @param $albumId идентификатор альбома
 */
function uploadImagesToAlbum($albumId)
{
    // Цикл по каждому файлу.
    for ($i = 0; $i < count($_FILES['upload']['name']); $i++) {
        // Получаем временный путь к файлу на сервере.
        $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
        // Получаем имя файла.
        $filename = $_FILES['upload']['name'][$i];
        // Получаем расширение файла.
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        // Убеждаемся, что временный путь файла не пуст.
        if ($tmpFilePath != '') {
            // Создаем новый путь для файла.
            $newFilePath = getUniqueFileName($albumId, $ext);

            // Переносим загруженный файл в альбом.
            move_uploaded_file($tmpFilePath, $newFilePath);
        }
    }
}

/**
 * Удаляет папку.
 * @param $dir полный путь к папке, которую нужно удалить.
 * @return bool
 */
function deleteDir($dir)
{
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? deleteDir("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

/**
 * Проверяет, вошел ли пользователь в админ. раздел.
 * Если пользователь еще не заходил в админ. раздел, то, всякий раз, когда он будет пытаться зайти
 * на страницы типа /admin/index.php, /admin/album.php, он будет перенаправляться на страницу входа в админ. раздел.
 * @return bool
 */
function checkUserLoggedIn()
{
    if (!isUserLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
    return true;
}

function isUserLoggedIn()
{
    return isset($_SESSION['loggedIn']);
}

/**
 * Перенаправляет пользователя в админ. раздел, если он уже авторизировался.
 */
function redirectToAdminPanelIfUserLoggedIn()
{
    if (isset($_SESSION['loggedIn'])) {
        header('Location: /admin');
        exit;
    }
}

/**
 * Позволяет разлогинить текущего пользователя сайта.
 */
function logout()
{
    unset($_SESSION['loggedIn']);
    unset($_SESSION['role']);
    session_destroy();
}

/**
 * Получает соединение с базой данных MySQL 'myivanovo'.
 * @return PDO
 */
function getDbConnection()
{
    $user = 'root';
    $password = '';
    return new PDO('mysql:host=localhost;dbname=myivanovo', $user, $password);
}

/**
 * Выполняет запрос к базе данных.
 * @param $query запрос
 * @return PDOStatement
 */
function executeDbQuery($query)
{
    try {
        $dbConnection = getDbConnection();
        return $dbConnection->query($query);

    } catch (PDOException $e) {
        print "Ошибка!: " . $e->getMessage() . "<br/>";
        die();
    }
}

/**
 * Добавляет пользователя в базу данных.
 * @param $username
 * @param $email
 * @param $password
 * @return PDOStatement
 */
function addUserToDb($username, $email, $password)
{
    $sql = "INSERT INTO user (username, email, password, role) VALUES ('$username', '$email','$password', 'moder')";
    return executeDbQuery($sql);
}

/**
 * Получает роль пользователя.
 * Существует всего 2 роли: аdmin и moder.
 * @return mixed
 */
function getUserRole()
{
    return $_SESSION['role'];
}

/**
 * Возращает 'true', если пользователь admin, иначе 'false'.
 * @return bool
 */
function isUserAdmin()
{
    $userRole = getUserRole();
    return $userRole == 'admin';
}

/**
 * Если пользователь не администратор, отображает страницу 'Доступ запрещен'.
 */
function show403ErrorIfUserIsNotAdmin()
{
    if (!isUserAdmin()) {
        header("HTTP/1.0 403 Access Forbidden");
        require_once('../protected/403.php');
        exit();
    }
}