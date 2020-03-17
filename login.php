<?php session_start(); ?>
<?php require_once("bdcon.php"); ?>
<?php

if (isset($_SESSION["session_username"])) {
// echo "Session is set"; // for testing purposes
    header("Location: index.php");
}
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


if (!empty($_POST['username']) && !empty($_POST['password'])) {

    // принимаем логин и пароль
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ищем количество строк с таким логином и паролем
    $countrow = DBOnce('COUNT(*) as count', 'usertbl', 'username="' . $username . '" and password="' . $password . '"');

    if ($countrow != 0) {

        // сверяемся еще раз
        $dbusername = DBOnce('username', 'usertbl', 'username="' . $username . '" and password="' . $password . '"');
        $dbpassword = DBOnce('password', 'usertbl', 'username="' . $username . '" and password="' . $password . '"');

        if ($username == $dbusername && $password == $dbpassword) {
            $iduser = DBOnce('id', 'usertbl', 'username="' . $username . '" and password="' . $password . '"');
            setcookie("session_username", $username, time() + 3600 * 24 * 30 * 365);
            setcookie("iduser", $iduser, time() + 3600 * 24 * 30 * 365);
            $activity = $pdo->prepare('UPDATE `usertbl` SET activity = :activity where id="' . $iduser . '"');
            $activity->execute(array('activity' => $datetime));
            header("location:/");
        }

    } else {

        $message = "Неверное имя или пароль!";
    }

} else {

}
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css?ver=21">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&display=swap&subset=cyrillic"
          rel="stylesheet">
    <script src="/js/jquery-1.11.0.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <!-- htt p:/ /netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js -->
    <script src="/js/custom.js"></script>
    <script src='/js/sweetalert2.all.js'></script>
    <script src='/js/polyfill.min.js'></script>
    <link rel="stylesheet" href="/css/swiper.min.css">
    <script src="/js/swiper.min.js"></script>
</head>
<body>
<div class="container" id="loginPage">


            <div class="info-block mt-5 mb-4">
                <div class="inside">
                    <h3 class="text-center mb-4">Авторизация</h3>
                    <form name="loginform" id="loginform" action="" method="POST">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Логин</span>
                            </div>
                            <input type="text" class="form-control" placeholder="Введите логин" aria-label="username"
                                   aria-describedby="basic-addon1" name="username" id="username">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Пароль</span>
                            </div>
                            <input type="password" class="form-control" placeholder="Введите пароль" aria-label="password"
                                   aria-describedby="basic-addon1" name="password" id="password">
                        </div>


                        <button type="submit" name="login" class="btn btn-primary w-100">Войти</button>
                    </form>
                </div>
            </div>
            <div class="m-auto d-block text-center">
                <a class="text-black-50 small" href="https://wa.me/79266556988?text=Забыл%20пароль%20">Забыли пароль?</a>
            </div>
</div>

<?php include("footer.php"); ?>

<?php if (!empty($message)) {
    echo "<p class=\"error\">" . "MESSAGE: " . $message . "</p>";
} ?>
	