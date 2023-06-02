<?php
session_start();
$_SESSION = array();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="logout.css">
    <title>ログアウト画面</title>
</head>
<body>
    <p>
        ログアウトしました。ご利用ありがとうございました。
    </p>
    <p>
        <form action="../login/login.php">
            <input type="submit" value="ログイン画面へ" class="button">
        </form>
    </p>
</body>
</html>
