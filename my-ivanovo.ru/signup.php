<?php
require_once('protected/functions.php');
redirectToAdminPanelIfUserLoggedIn(); // Если пользователь уже вошел, то перенаправляем его в админ. раздел.
$error = '';
$username = $email = $password = '';
if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
}

if ($username != '' && $password != '' && $email != '') {
    if (addUserToDb($username, $email, $password) != false) {
        $_SESSION['loggedIn'] = true;
        $_SESSION['role'] = 'moder';
        header('Location: /admin/'); // Переходим на страницу site-name.ru/admin/index.php.
    } else {
        $error = 'Не удалось вас зарегистрировать. Пожалуйста, обратитесь за помощью к администратору сайта.';
    }
} else if (!empty($_POST)) {
    $error = 'Пожалуйста, заполните все поля';
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Регистрация | Моё Иваново</title>
    <link rel="stylesheet" href="css/login-form.css">
</head>
<body>
<div id="login">
    <h1>Регистрация</h1>

    <form name="loginform" id="loginform" action="/signup.php" method="post">

        <?php if ($error != ''): ?>
            <div class="error-message">
                <h1>Ошибка!</h1>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <label for="user_login">Имя пользователя</label>
        <input type="text" name="username" id="user_name" class="input" value="<?php echo $username; ?>" size="20"/>

        <label for="user_pass">Email</label>
        <input type="email" name="email" id="user_email" class="input" value="<?php echo $email; ?>" size="20"/>

        <label for="user_pass">Пароль</label>
        <input type="password" name="password" maxlength="24" id="user_pass" class="input"
               value="<?php echo $password; ?>" size="20"/>

        <input type="submit" class="button" value="Зарегистрироваться!"/>
    </form>
</div>
</body>
</html>