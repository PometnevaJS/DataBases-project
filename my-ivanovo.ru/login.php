<?php
require_once('protected/functions.php');
redirectToAdminPanelIfUserLoggedIn(); // Если пользователь уже вошел, то перенаправляем его в админ. раздел.

$error = '';
$username = '';
$password = '';
if (isset($_POST['username']) && isset($_POST['password'])) { // Если введен логин и пароль...
    $username = $_POST['username'];
    $password = $_POST['password'];
}
if ($username != '' && $password != '') {
    $user = null;
    $stmt = executeDbQuery("SELECT * FROM user WHERE username='$username'");
    if ($stmt) $user = $stmt->fetch();

    // Проверяем, правильно ли введены логин и пароль...
    if ($user != null && $user['password'] == $password) {
        $_SESSION['loggedIn'] = true;
        $_SESSION['role'] = $user['role'];
        header('Location: /admin/'); // Переходим на страницу site-name.ru/admin/index.php.
    } else {
        $error = 'Не удается войти. Пожалуйста, проверьте правильность написания логина и пароля.';
    }
} else if (!empty($_POST)) {
    $error = 'Пожалуйста, заполните все поля';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Вход в админ. раздел | Моё Иваново</title>
    <link rel="stylesheet" href="css/login-form.css">
</head>
<body>
<div id="login">
    <h1>Вход в админ. раздел</h1>

    <?php if ($error != ''): ?>
        <div class="error-message">
            <h1>Ошибка!</h1>
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form name="loginform" id="loginform" action="/login.php" method="post">


        <label for="user_name">Имя пользователя</label>
        <input type="text" name="username" id="user_name" class="input" value="<?php echo $username; ?>" size="20"/>

        <label for="user_pass">Пароль</label>
        <input type="password" name="password" id="user_pass" class="input" value="<?php echo $password; ?>" size="20"/>

        <input type="submit" class="button" value="Войти"/>
    </form>
</div>
</body>
</html>